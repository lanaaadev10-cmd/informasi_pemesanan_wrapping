<?php

namespace App\Services;

use App\Models\User;
use App\Models\Pesanan;
use App\Models\FormPesanan;
use App\Models\DetailPesanan;
use App\Models\Pembayaran;
use App\Models\Layanan;
use App\Exceptions\SlotPenuhException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class OfflinePesananService
{
    public function __construct(
        protected SlotKuotaService $slotKuotaService
    ) {}

    /**
     * Buat pesanan offline (walk-in) oleh admin.
     *
     * @param array{
     *   user_id?: int|null,
     *   customer_name: string,
     *   phone: string,
     *   email?: string|null,
     *   layanan_id: int,
     *   booking_date: string,
     *   model_kendaraan: string,
     *   warna_kendaraan?: string|null,
     *   nomor_polisi?: string|null,
     *   tahun_produksi?: string|null,
     *   catatan?: string|null,
     *   status?: string,
     *   payment_method?: string,
     *   admin_id: int
     * } $data
     * @return Pesanan
     * @throws SlotPenuhException
     */
    public function createOfflineOrder(array $data): Pesanan
    {
        $bookingDate = $data['booking_date'];

        return DB::transaction(function () use ($data, $bookingDate) {
            // 1. Validasi kuota slot harian (maks. 5/hari)
            $this->slotKuotaService->assertSlotAvailable($bookingDate);

            // 2. Dapatkan atau buat akun temporary user walk-in
            $user = null;
            if (!empty($data['user_id'])) {
                $user = User::find($data['user_id']);
            }

            if (!$user) {
                $cleanPhone = preg_replace('/[^0-9]/', '', $data['phone']);
                $email = !empty($data['email']) ? $data['email'] : 'walkin_' . time() . '_' . Str::random(4) . '@dantiestiker.local';

                $user = User::create([
                    'name'               => $data['customer_name'],
                    'email'              => $email,
                    'password'           => Hash::make(Str::random(16)),
                    'email_verified_at'  => now(),
                    'is_walk_in'         => true,
                    'walk_in_created_by' => $data['admin_id'],
                    'walk_in_note'       => 'Akun walk-in dibuat admin untuk pesanan offline',
                ]);

                // Assign role user
                if (method_exists($user, 'assignRole')) {
                    $user->assignRole('user');
                }
            }

            // 3. Ambil data Layanan yang dipesan
            $layanan = Layanan::findOrFail($data['layanan_id']);
            $hargaTotal = (float) ($layanan->harga ?? 0);

            // Kode pesanan offline format: PSN-OFFLINE-XXXXXX
            $kodePesanan = 'PSN-OFF-' . strtoupper(Str::random(6));

            // 4. Buat Record Pesanan
            $status = $data['status'] ?? Pesanan::STATUS_SEDANG_DIPROSES;

            $pesanan = Pesanan::create([
                'id_user'             => $user->id,
                'kode_pesanan'        => $kodePesanan,
                'tanggal_pesan'       => now(),
                'booking_date'        => $bookingDate,
                'status'              => $status,
                'total_harga'         => $hargaTotal,
                'whatsapp_number'     => $data['phone'],
                'order_source'        => 'offline',
                'customer_name'       => $data['customer_name'],
                'address'             => $data['address'] ?? 'Bengkel Dantie Stiker (Walk-in)',
                'created_by_admin_id' => $data['admin_id'],
                'catatan_admin'       => $data['catatan'] ?? 'Pesanan offline / walk-in dibuat oleh admin.',
            ]);

            // 5. Buat Detail Pesanan (Item)
            DetailPesanan::create([
                'id_pesanan'   => $pesanan->id_pesanan,
                'id_layanan'   => $layanan->id_layanan,
                'jumlah'       => 1,
                'harga_satuan' => $hargaTotal,
                'subtotal'     => $hargaTotal,
            ]);

            // 6. Buat Form Pesanan (Jadwal & Data Kendaraan)
            FormPesanan::create([
                'id_pesanan'         => $pesanan->id_pesanan,
                'nama_pemesan'       => $data['customer_name'],
                'alamat_pengiriman'  => $data['address'] ?? 'Bengkel Dantie Stiker',
                'no_hp'              => $data['phone'],
                'model_kendaraan'    => $data['model_kendaraan'],
                'warna_kendaraan'    => $data['warna_kendaraan'] ?? '-',
                'nomor_polisi'       => $data['nomor_polisi'] ?? '-',
                'tahun_produksi'     => $data['tahun_produksi'] ?? date('Y'),
                'lokasi_pengerjaan'  => 'toko',
                'jadwal_pengerjaan'  => $bookingDate . ' 09:00:00',
                'estimasi_durasi'    => $layanan->estimasi_waktu ?? '4 - 5 Hari Kerja',
                'keterangan_tambahan'=> $data['catatan'] ?? null,
                'status_verifikasi'  => 'terverifikasi',
            ]);

            // 7. Buat Payment Record jika langsung bayar (Cash/Transfer)
            $paymentMethod = $data['payment_method'] ?? 'cash';
            Pembayaran::create([
                'id_pesanan'        => $pesanan->id_pesanan,
                'metode_pembayaran' => $paymentMethod,
                'jumlah_bayar'      => $hargaTotal,
                'tgl_bayar'         => now(),
                'verifikasi_admin'  => 'diverifikasi',
                'catatan'           => 'Pembayaran langsung di kasir/bengkel',
            ]);

            // 8. Clear cache kuota tanggal
            $this->slotKuotaService->clearCache($bookingDate);

            return $pesanan;
        });
    }
}
