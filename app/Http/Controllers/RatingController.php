<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use App\Models\Pesanan;
use App\Models\Rating;
use App\Services\RatingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function __construct(
        protected RatingService $ratingService,
    ) {}

    /**
     * Alur 1: Form rating untuk suatu pesanan (per layanan dalam pesanan).
     */
    public function form($idPesanan)
    {
        $pesanan = Pesanan::where('id_pesanan', $idPesanan)
            ->where('id_user', Auth::id())
            ->with('details.layanan')
            ->firstOrFail();

        if ($pesanan->status !== Pesanan::STATUS_SELESAI) {
            abort(403, 'Rating hanya bisa diberikan setelah pesanan selesai.');
        }

        $this->authorize('create', [Rating::class, $pesanan]);

        // Kumpulkan layanan yang ada di pesanan
        $layanans = $pesanan->details
            ->map(fn ($detail) => $detail->layanan)
            ->filter()
            ->values();

        // Rating yang sudah ada per layanan
        $existingRatings = Rating::where('id_pesanan', $pesanan->id_pesanan)
            ->with('medias')
            ->get()
            ->keyBy('id_layanan');

        return view('dashboard.customer.pesanan.rating', compact('pesanan', 'layanans', 'existingRatings'));
    }

    /**
     * Alur 1: Simpan/Update rating untuk satu layanan dalam pesanan.
     */
    public function store(Request $request, $idPesanan)
    {
        $pesanan = Pesanan::where('id_pesanan', $idPesanan)
            ->where('id_user', Auth::id())
            ->with('details')
            ->firstOrFail();

        if ($pesanan->status !== Pesanan::STATUS_SELESAI) {
            abort(403, 'Rating hanya bisa diberikan setelah pesanan selesai.');
        }

        $this->authorize('create', [Rating::class, $pesanan]);

        $validated = $this->validateRequest($request);

        // Validasi bahwa layanan tersebut ada di pesanan (server-side)
        $ada = $pesanan->details->contains(fn ($d) => $d->id_paket == $validated['id_layanan']);
        if (! $ada) {
            return back()->with('toast_error', 'Layanan tidak ditemukan pada pesanan ini.')->withInput();
        }

        $fotos = $request->file('foto', []);

        $this->ratingService->storeForPesanan(
            $pesanan,
            Auth::id(),
            [
                $validated['id_layanan'] => [
                    'rating' => $validated['rating'],
                    'ulasan' => $validated['ulasan'] ?? null,
                    'foto_baru' => $fotos,
                ],
            ]
        );

        return redirect()
            ->route('dashboard')
            ->with('toast_success', 'Terima kasih atas ulasan Anda!');
    }

    /**
     * Alur 2: Form rating tanpa pesanan (dropdown layanan).
     */
    public function formLayanan(Request $request)
    {
        $this->authorize('create', Rating::class);

        $layanans = Layanan::query()
            ->orderBy('nama_layanan')
            ->get(['id_layanan', 'nama_layanan']);

        // Rating yang sudah dibuat user (tanpa pesanan) — untuk mode edit
        $myRatings = Rating::where('id_user', Auth::id())
            ->whereNull('id_pesanan')
            ->with('medias')
            ->get()
            ->keyBy('id_layanan');

        return view('dashboard.customer.rating.layanan', compact('layanans', 'myRatings'));
    }

    /**
     * Alur 2: Simpan/Update rating layanan (tanpa pesanan).
     */
    public function storeLayanan(Request $request)
    {
        $this->authorize('create', Rating::class);

        $validated = $this->validateRequest($request);

        $layanan = Layanan::where('id_layanan', $validated['id_layanan'])->firstOrFail();

        $fotos = $request->file('foto', []);

        $this->ratingService->storeForLayanan(
            $layanan,
            Auth::id(),
            [
                'rating' => $validated['rating'],
                'ulasan' => $validated['ulasan'] ?? null,
                'foto_baru' => $fotos,
            ]
        );

        return redirect()
            ->route('dashboard')
            ->with('toast_success', 'Terima kasih atas ulasan Anda!');
    }

    /**
     * Validasi request rating (dipakai Alur 1 & 2).
     */
    protected function validateRequest(Request $request): array
    {
        $validated = $request->validate([
            'id_layanan' => 'required|exists:layanans,id_layanan',
            'rating' => 'required|integer|min:1|max:5',
            'ulasan' => 'nullable|string|max:500',
            'foto' => 'nullable|array|max:2',
            'foto.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        RatingService::assertCleanContent($validated['ulasan'] ?? null);

        return $validated;
    }
}
