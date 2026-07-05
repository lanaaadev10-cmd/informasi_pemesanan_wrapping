<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\FormPesanan;
use App\Models\Keranjang;
use App\Models\DetailKeranjang;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PesananApiController extends Controller
{
    /**
     * GET /api/pesanan
     * List user's orders with pagination
     */
    public function index(Request $request)
    {
        $userId = Auth::id();
        $perPage = $request->get('per_page', 10);
        $status = $request->get('status');

        $query = Pesanan::where('id_user', $userId)
            ->with(['details.layanan', 'form', 'pembayaran']);

        if ($status) {
            $query->where('status', $status);
        }

        $pesanans = $query->orderByDesc('tanggal_pesan')->paginate($perPage);

        return response()->json([
            'status' => 'ok',
            'data' => $pesanans->items(),
            'pagination' => [
                'current_page' => $pesanans->currentPage(),
                'total' => $pesanans->total(),
                'per_page' => $pesanans->perPage(),
                'last_page' => $pesanans->lastPage(),
            ],
        ]);
    }

    /**
     * POST /api/pesanan
     * Create new order from cart (checkout)
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_pemesan' => 'required|string|max:100',
            'no_hp' => 'required|string|max:20',
            'alamat_pengiriman' => 'required|string|max:500',
            'metode_pembayaran' => 'required|string|in:transfer_bank,transfer_e_wallet',
            'keterangan_tambahan' => 'nullable|string|max:1000',
        ]);

        $userId = Auth::id();

        $keranjang = Keranjang::where('id_user', $userId)
            ->where('status', 'active')
            ->with('details')
            ->first();

        if (!$keranjang || $keranjang->details->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Keranjang Anda kosong.',
            ], 400);
        }

        $totalHarga = $keranjang->details->sum('subtotal');

        $pesanan = Pesanan::create([
            'id_user' => $userId,
            'kode_pesanan' => 'PES-' . time() . '-' . rand(1000, 9999),
            'tanggal_pesan' => now()->toDateString(),
            'status' => 'menunggu_konfirmasi_admin',
            'total_harga' => $totalHarga,
        ]);

        foreach ($keranjang->details as $item) {
            DetailPesanan::create([
                'id_pesanan' => $pesanan->id_pesanan,
                'id_paket' => $item->id_paket,
                'jumlah' => $item->jumlah,
                'harga_satuan' => $item->harga_satuan,
                'subtotal' => $item->subtotal,
                'catatan_custom' => $item->catatan_custom,
            ]);
        }

        FormPesanan::create([
            'id_pesanan' => $pesanan->id_pesanan,
            'nama_pemesan' => $request->nama_pemesan,
            'no_hp' => $request->no_hp,
            'alamat_pengiriman' => $request->alamat_pengiriman,
            'status_verifikasi' => 'pending',
            'keterangan_tambahan' => $request->keterangan_tambahan,
        ]);

        $keranjang->update(['status' => 'used']);

        Notifikasi::create([
            'id_user' => $userId,
            'id_pesanan' => $pesanan->id_pesanan,
            'judul' => 'Pesanan Berhasil Dibuat',
            'pesan' => "Pesanan #{$pesanan->kode_pesanan} berhasil dibuat. Silakan lakukan pembayaran untuk melanjutkan.",
            'tipe' => 'order_created',
            'is_read' => false,
        ]);

        return response()->json([
            'status' => 'ok',
            'message' => 'Pesanan berhasil dibuat.',
            'data' => [
                'id_pesanan' => $pesanan->id_pesanan,
                'kode_pesanan' => $pesanan->kode_pesanan,
                'total_harga' => $pesanan->total_harga,
                'status' => $pesanan->status,
            ],
        ], 201);
    }

    /**
     * GET /api/pesanan/{id}
     * Get order details with items
     */
    public function show($id)
    {
        $pesanan = Pesanan::where('id_pesanan', $id)
            ->where('id_user', Auth::id())
            ->with(['details.layanan', 'form', 'pembayaran'])
            ->first();

        if (!$pesanan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pesanan tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'status' => 'ok',
            'data' => [
                'id_pesanan' => $pesanan->id_pesanan,
                'kode_pesanan' => $pesanan->kode_pesanan,
                'tanggal_pesan' => $pesanan->tanggal_pesan,
                'status' => $pesanan->status,
                'total_harga' => $pesanan->total_harga,
                'items' => $pesanan->details->map(function ($detail) {
                    return [
                        'id_detail' => $detail->id_detail,
                        'nama_layanan' => $detail->layanan->nama_layanan,
                        'jumlah' => $detail->jumlah,
                        'harga_satuan' => $detail->harga_satuan,
                        'subtotal' => $detail->subtotal,
                        'catatan_custom' => $detail->catatan_custom,
                    ];
                }),
                'form_pesanan' => $pesanan->form ? [
                    'nama_pemesan' => $pesanan->form->nama_pemesan,
                    'no_hp' => $pesanan->form->no_hp,
                    'alamat_pengiriman' => $pesanan->form->alamat_pengiriman,
                    'status_verifikasi' => $pesanan->form->status_verifikasi,
                    'keterangan_tambahan' => $pesanan->form->keterangan_tambahan,
                ] : null,
                'pembayaran' => $pesanan->pembayaran ? [
                    'metode_pembayaran' => $pesanan->pembayaran->metode_pembayaran,
                    'jumlah_bayar' => $pesanan->pembayaran->jumlah_bayar,
                    'status' => $pesanan->pembayaran->status,
                    'tgl_bayar' => $pesanan->pembayaran->tgl_bayar,
                    'verifikasi_admin' => $pesanan->pembayaran->verifikasi_admin,
                ] : null,
            ],
        ]);
    }

    /**
     * GET /api/pesanan/{id}/status
     * Get current order status
     */
    public function status($id)
    {
        $pesanan = Pesanan::where('id_pesanan', $id)
            ->where('id_user', Auth::id())
            ->first();

        if (!$pesanan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pesanan tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'status' => 'ok',
            'data' => [
                'id_pesanan' => $pesanan->id_pesanan,
                'kode_pesanan' => $pesanan->kode_pesanan,
                'current_status' => $pesanan->status,
                'tanggal_pesan' => $pesanan->tanggal_pesan,
                'total_harga' => $pesanan->total_harga,
            ],
        ]);
    }

    /**
     * POST /api/pesanan/{id}/upload-bukti
     * Upload payment proof
     */
    public function uploadBukti(Request $request, $id)
    {
        $request->validate([
            'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $pesanan = Pesanan::where('id_pesanan', $id)
            ->where('id_user', Auth::id())
            ->first();

        if (!$pesanan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pesanan tidak ditemukan.',
            ], 404);
        }

        if ($request->hasFile('bukti_transfer')) {
            $file = $request->file('bukti_transfer');
            $path = $file->store('pembayaran', 'public');

            $pesanan->update(['status' => 'menunggu_verifikasi_pembayaran']);

            $pesanan->pembayaran()->updateOrCreate(
                ['id_pesanan' => $pesanan->id_pesanan],
                [
                    'bukti_transfer' => $path,
                    'status' => 'menunggu_pembayaran',
                    'tgl_bayar' => now()->toDateString(),
                ]
            );

            Notifikasi::create([
                'id_user' => Auth::id(),
                'id_pesanan' => $pesanan->id_pesanan,
                'judul' => 'Bukti Pembayaran Diterima',
                'pesan' => 'Bukti pembayaran Anda telah diterima. Menunggu verifikasi admin.',
                'tipe' => 'payment_uploaded',
                'is_read' => false,
            ]);

            return response()->json([
                'status' => 'ok',
                'message' => 'Bukti pembayaran berhasil diunggah.',
                'data' => [
                    'id_pesanan' => $pesanan->id_pesanan,
                    'status' => $pesanan->status,
                ],
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'File tidak ditemukan.',
        ], 400);
    }

    /**
     * GET /api/pesanan/{id}/timeline
     * Get status change history
     */
    public function timeline($id)
    {
        $pesanan = Pesanan::where('id_pesanan', $id)
            ->where('id_user', Auth::id())
            ->first();

        if (!$pesanan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pesanan tidak ditemukan.',
            ], 404);
        }

        $timeline = [
            [
                'status' => 'menunggu_konfirmasi_admin',
                'label' => 'Pesanan Dibuat',
                'completed' => true,
                'date' => $pesanan->tanggal_pesan,
            ],
            [
                'status' => 'menunggu_pembayaran',
                'label' => 'Dikonfirmasi Admin',
                'completed' => in_array($pesanan->status, ['menunggu_pembayaran', 'menunggu_verifikasi_pembayaran', 'dikonfirmasi', 'sedang_diproses', 'selesai']),
            ],
            [
                'status' => 'dikonfirmasi',
                'label' => 'Pembayaran Terverifikasi',
                'completed' => in_array($pesanan->status, ['dikonfirmasi', 'sedang_diproses', 'selesai']),
                'date' => $pesanan->pembayaran?->tgl_bayar ?? null,
            ],
            [
                'status' => 'sedang_diproses',
                'label' => 'Sedang Diproses',
                'completed' => in_array($pesanan->status, ['sedang_diproses', 'selesai']),
            ],
            [
                'status' => 'selesai',
                'label' => 'Selesai',
                'completed' => $pesanan->status === 'selesai',
            ],
        ];

        return response()->json([
            'status' => 'ok',
            'data' => [
                'id_pesanan' => $pesanan->id_pesanan,
                'current_status' => $pesanan->status,
                'timeline' => $timeline,
            ],
        ]);
    }
}
