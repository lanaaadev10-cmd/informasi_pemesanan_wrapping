<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PembayaranApiController extends Controller
{
    /**
     * GET /api/pembayaran/{pesanan_id}
     * Get payment status for an order
     */
    public function show($pesanan_id)
    {
        $pesanan = Pesanan::where('id_pesanan', $pesanan_id)
            ->where('id_user', Auth::id())
            ->with('pembayaran')
            ->first();

        if (!$pesanan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pesanan tidak ditemukan.',
            ], 404);
        }

        if (!$pesanan->pembayaran) {
            return response()->json([
                'status' => 'ok',
                'data' => [
                    'id_pesanan' => $pesanan->id_pesanan,
                    'status' => 'belum_membayar',
                    'message' => 'Silakan lakukan pembayaran untuk melanjutkan.',
                ],
            ]);
        }

        return response()->json([
            'status' => 'ok',
            'data' => [
                'id_pesanan' => $pesanan->id_pesanan,
                'metode_pembayaran' => $pesanan->pembayaran->metode_pembayaran,
                'jumlah_bayar' => $pesanan->pembayaran->jumlah_bayar,
                'status' => $pesanan->pembayaran->status,
                'tgl_bayar' => $pesanan->pembayaran->tgl_bayar,
                'verifikasi_admin' => $pesanan->pembayaran->verifikasi_admin,
                'bukti_transfer' => $pesanan->pembayaran->bukti_transfer ? asset('storage/' . $pesanan->pembayaran->bukti_transfer) : null,
                'catatan_admin' => $pesanan->pembayaran->catatan_admin,
            ],
        ]);
    }

    /**
     * POST /api/pembayaran/{pesanan_id}/verify
     * Manual payment status check
     */
    public function verify($pesanan_id)
    {
        $pesanan = Pesanan::where('id_pesanan', $pesanan_id)
            ->where('id_user', Auth::id())
            ->with('pembayaran')
            ->first();

        if (!$pesanan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pesanan tidak ditemukan.',
            ], 404);
        }

        if (!$pesanan->pembayaran) {
            return response()->json([
                'status' => 'error',
                'message' => 'Belum ada data pembayaran untuk pesanan ini.',
            ], 400);
        }

        $payment = $pesanan->pembayaran;

        return response()->json([
            'status' => 'ok',
            'data' => [
                'id_pesanan' => $pesanan->id_pesanan,
                'metode_pembayaran' => $payment->metode_pembayaran,
                'jumlah_bayar' => $payment->jumlah_bayar,
                'status' => $payment->status,
                'verifikasi_admin' => $payment->verifikasi_admin,
                'is_verified' => $payment->verifikasi_admin === 'sudah',
                'verification_message' => $payment->verifikasi_admin === 'sudah'
                    ? 'Pembayaran Anda telah diverifikasi.'
                    : 'Pembayaran Anda sedang dalam proses verifikasi.',
            ],
        ]);
    }

    /**
     * GET /api/pembayaran/methods
     * Get available payment methods
     */
    public function methods()
    {
        $methods = [
            [
                'id' => 'transfer_bank',
                'label' => 'Transfer Bank',
                'description' => 'Transfer ke rekening bank kami',
                'icon' => 'building-2',
            ],
            [
                'id' => 'transfer_e_wallet',
                'label' => 'E-Wallet (GCash/Alipay)',
                'description' => 'Transfer melalui aplikasi dompet digital',
                'icon' => 'wallet',
            ],
        ];

        return response()->json([
            'status' => 'ok',
            'data' => $methods,
        ]);
    }
}
