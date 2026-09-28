<?php

namespace App\Services;

use App\Events\RatingCreated;
use App\Events\RatingUpdated;
use App\Models\Layanan;
use App\Models\Pesanan;
use App\Models\Rating;
use App\Models\RatingMedia;
use Closure;
use Heyitsmi\ContentGuard\Facades\ContentGuard;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class RatingService
{
    public const MAX_PHOTOS = 2;

    public const MAX_PHOTO_SIZE_KB = 5120; // 5MB

    public const ALLOWED_MIME = ['image/jpeg', 'image/png', 'image/webp'];

    public const MAX_ULASAN_LENGTH = 500;

    /**
     * Simpan rating untuk Alur 1 (via pesanan, per layanan dalam pesanan).
     * Menyimpan satu record per layanan yang dipilih.
     */
    public function storeForPesanan(Pesanan $pesanan, int $userId, array $layananRatings): array
    {
        return DB::transaction(function () use ($pesanan, $userId, $layananRatings) {
            $saved = [];

            foreach ($layananRatings as $idLayanan => $data) {
                // Validasi layanan benar-benar ada di pesanan ini
                $ada = $pesanan->details->contains(fn ($d) => $d->id_paket == $idLayanan);
                if (! $ada) {
                    continue;
                }

                $idLayanan = (int) $idLayanan;

                // Unique: (id_pesanan, id_layanan) — satu rating per layanan per pesanan.
                // Cek lebih dulu; jika sudah ada maka lakukan update (edit).
                $existing = Rating::where('id_pesanan', $pesanan->id_pesanan)
                    ->where('id_layanan', $idLayanan)
                    ->first();

                $saved[] = $existing
                    ? $this->update($existing, $data)
                    : $this->createOrUpdateOnDuplicate(
                        userId: $userId,
                        idLayanan: $idLayanan,
                        idPesanan: $pesanan->id_pesanan,
                        data: $data,
                        resolveExisting: fn () => Rating::where('id_pesanan', $pesanan->id_pesanan)
                            ->where('id_layanan', $idLayanan)
                            ->first(),
                    );
            }

            return $saved;
        });
    }

    // [DISABLED] Alur 2 — service ini hanya dipakai oleh endpoint Alur 2 yang sedang non-aktif.

    /**
     * Simpan rating untuk Alur 2 (via dropdown layanan tanpa pesanan).
     * Cek unique secara manual: id_user + id_layanan + id_pesanan IS NULL.
     * Jika sudah ada, maka lakukan update (edit).
     */
    public function storeForLayanan(Layanan $layanan, int $userId, array $data): Rating
    {
        return DB::transaction(function () use ($layanan, $userId, $data) {
            // Cek apakah user sudah pernah rate layanan ini tanpa pesanan.
            $existing = Rating::where('id_user', $userId)
                ->where('id_layanan', $layanan->id_layanan)
                ->whereNull('id_pesanan')
                ->first();

            if ($existing) {
                return $this->update($existing, $data);
            }

            return $this->createOrUpdateOnDuplicate(
                userId: $userId,
                idLayanan: $layanan->id_layanan,
                idPesanan: null,
                data: $data,
                resolveExisting: fn () => Rating::where('id_user', $userId)
                    ->where('id_layanan', $layanan->id_layanan)
                    ->whereNull('id_pesanan')
                    ->first(),
            );
        });
    }

    /**
     * Update rating existing (bintang/ulasan/foto).
     */
    public function update(Rating $rating, array $data): Rating
    {
        $this->validateData($data);

        $rating->rating = $data['rating'];
        $rating->ulasan = $data['ulasan'] ?? null;
        $rating->save();

        // Ganti foto: hapus yang lama, simpan yang baru (jika ada).
        if (! empty($data['foto_baru'])) {
            $this->deleteMedia($rating);
            $this->saveMedia($rating, $data['foto_baru']);
        }

        RatingUpdated::dispatch($rating);

        return $rating;
    }

    /**
     * Hapus rating beserta file fisik media-nya.
     */
    public function delete(Rating $rating): void
    {
        $this->deleteMedia($rating);
        $rating->delete();
    }

    protected function validateAndCreate(int $userId, int $idLayanan, ?int $idPesanan, array $data): Rating
    {
        $this->validateData($data);

        return DB::transaction(function () use ($userId, $idLayanan, $idPesanan, $data) {
            $rating = Rating::create([
                'id_user' => $userId,
                'id_pesanan' => $idPesanan,
                'id_layanan' => $idLayanan,
                'order_ref' => $idPesanan ?? 0,
                'rating' => $data['rating'],
                'ulasan' => $data['ulasan'] ?? null,
            ]);

            if (! empty($data['foto_baru'])) {
                $this->saveMedia($rating, $data['foto_baru']);
            }

            RatingCreated::dispatch($rating);

            return $rating;
        });
    }

    /**
     * Coba create rating. Jika gagal karena unique constraint (duplikat submit
     * dari request paralel), ambil record yang sudah ada lalu update sebagai gantinya.
     */
    protected function createOrUpdateOnDuplicate(
        int $userId,
        int $idLayanan,
        ?int $idPesanan,
        array $data,
        Closure $resolveExisting,
    ): Rating {
        try {
            return $this->validateAndCreate(
                userId: $userId,
                idLayanan: $idLayanan,
                idPesanan: $idPesanan,
                data: $data,
            );
        } catch (QueryException $e) {
            $existing = $resolveExisting();

            if (! $existing) {
                throw $e;
            }

            return $this->update($existing, $data);
        }
    }

    protected function validateData(array $data): void
    {
        $ulasan = $data['ulasan'] ?? null;
        if ($ulasan !== null && mb_strlen($ulasan) > self::MAX_ULASAN_LENGTH) {
            throw ValidationException::withMessages([
                'ulasan' => 'Ulasan maksimal '.self::MAX_ULASAN_LENGTH.' karakter.',
            ]);
        }

        self::assertCleanContent($ulasan);
    }

    /**
     * Tolak ulasan yang mengandung kata kasar / konten tidak pantas.
     * Dipakai sebagai choke point oleh service maupun controller web/API.
     */
    public static function assertCleanContent(?string $ulasan): void
    {
        if ($ulasan !== null && trim($ulasan) !== '' && ContentGuard::hasBadWords($ulasan)) {
            throw ValidationException::withMessages([
                'ulasan' => 'Ulasan mengandung kata yang tidak pantas.',
            ]);
        }
    }

    /**
     * Simpan file foto ke storage/public/rating dan buat record RatingMedia.
     * $fotos adalah array file upload (dari request).
     */
    protected function saveMedia(Rating $rating, array $fotos): void
    {
        $urutan = 0;
        foreach ($fotos as $file) {
            if ($urutan >= self::MAX_PHOTOS) {
                break;
            }

            $this->assertValidPhoto($file);

            if ($file->isValid()) {
                $path = $file->store('rating', 'public');
                RatingMedia::create([
                    'id_rating' => $rating->id,
                    'path' => $path,
                    'urutan' => $urutan,
                ]);
                $urutan++;
            }
        }
    }

    protected function assertValidPhoto($file): void
    {
        $valid = $file->isValid()
            && $file->getSize() <= self::MAX_PHOTO_SIZE_KB * 1024
            && in_array(strtolower((string) $file->getMimeType()), self::ALLOWED_MIME, true);

        if (! $valid) {
            throw ValidationException::withMessages([
                'foto' => 'Foto tidak valid. Gunakan jpg/jpeg/png/webp maksimal 5MB.',
            ]);
        }
    }

    /**
     * Hapus semua media milik rating (file + record).
     */
    protected function deleteMedia(Rating $rating): void
    {
        foreach ($rating->medias as $media) {
            Storage::disk('public')->delete($media->path);
            $media->delete();
        }
    }
}
