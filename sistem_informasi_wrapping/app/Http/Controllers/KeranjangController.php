<?php

namespace App\Http\Controllers;

use App\Models\Keranjang;
use App\Models\DetailKeranjang;
use App\Models\Layanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KeranjangController extends Controller
{
    /**
     * Tampilkan isi keranjang milik user yang sedang login
     */
    public function index()
    {
        $keranjang = Keranjang::where('id_user', Auth::id())
            ->where('status', 'active')
            ->with('details.layanan')
            ->first();

        return view('dashboard.customer.keranjang.index', compact('keranjang'));
    }

    /**
     * Tambahkan paket layanan ke keranjang dengan validasi max 3 paket
     */
    public function tambah(Request $request)
    {
        $request->validate([
            'id_paket'       => 'required|exists:layanans,id_layanan',
            'jumlah'         => 'required|integer|min:1',
            'catatan_custom' => 'nullable|string|max:500',
        ]);

        $layanan = Layanan::findOrFail($request->id_paket);

        // Cari atau buat keranjang aktif milik user
        $keranjang = Keranjang::firstOrCreate(
            ['id_user' => Auth::id(), 'status' => 'active']
        );

        // Cek jumlah unique items (max 3)
        $itemCount = $keranjang->details()->count();

        // Cek apakah item sudah ada di keranjang
        $existingDetail = DetailKeranjang::where('id_keranjang', $keranjang->id_keranjang)
            ->where('id_paket', $request->id_paket)
            ->first();

        // Jika item belum ada dan sudah 3 item, tolak
        if (!$existingDetail && $itemCount >= 3) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Maksimal hanya 3 paket dalam keranjang.',
                    'max_items' => 3,
                    'current_items' => $itemCount,
                ], 422);
            }
            return redirect()->route('keranjang.index')
                ->with('error', 'Maksimal hanya 3 paket dalam keranjang. Silakan hapus paket lain terlebih dahulu.');
        }

        $hargaSatuan = $layanan->harga ?? 0;
        $subtotal    = $hargaSatuan * $request->jumlah;

        if ($existingDetail) {
            $existingDetail->update([
                'jumlah'   => $existingDetail->jumlah + $request->jumlah,
                'subtotal' => ($existingDetail->jumlah + $request->jumlah) * $hargaSatuan
            ]);
        } else {
            DetailKeranjang::create([
                'id_keranjang'   => $keranjang->id_keranjang,
                'id_paket'       => $request->id_paket,
                'jumlah'         => $request->jumlah,
                'catatan_custom' => $request->catatan_custom,
                'harga_satuan'   => $hargaSatuan,
                'subtotal'       => $subtotal,
            ]);
        }

        if ($request->wantsJson() || $request->ajax()) {
            $keranjang->load('details.layanan');
            return response()->json([
                'status' => 'success',
                'message' => 'Paket berhasil ditambahkan ke keranjang.',
                'data' => [
                    'nama_paket' => $layanan->nama_layanan,
                    'cart_count' => $keranjang->details()->count(),
                ],
            ], 201);
        }

        if ($request->has('direct_checkout')) {
            return redirect()->route('pesanan.checkout.form');
        }

        return redirect()->route('keranjang.index')
            ->with('success', 'Paket berhasil ditambahkan ke keranjang!');
    }

    /**
     * Hapus satu item dari keranjang
     */
    public function hapus($id_detail)
    {
        $detail = DetailKeranjang::findOrFail($id_detail);

        // Pastikan item ini milik user yang sedang login
        $this->authorize('delete', $detail);

        $detail->delete();

        return redirect()->route('keranjang.index')
            ->with('success', 'Item berhasil dihapus dari keranjang.');
    }

    /**
     * Kosongkan seluruh isi keranjang
     */
    public function kosongkan()
    {
        $keranjang = Keranjang::where('id_user', Auth::id())
            ->where('status', 'active')
            ->first();

        if ($keranjang) {
            $keranjang->details()->delete();
        }

        return redirect()->route('keranjang.index')
            ->with('success', 'Keranjang berhasil dikosongkan.');
    }

    /**
     * Update jumlah unit layanan di keranjang
     */
    public function update(Request $request, $id_detail)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1'
        ]);

        $detail = DetailKeranjang::findOrFail($id_detail);

        if ($detail->keranjang->id_user !== Auth::id()) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Unauthorized.'], 403);
            }
            abort(403, 'Akses ditolak.');
        }

        $hargaSatuan = $detail->harga_satuan;
        $subtotal = $request->jumlah * $hargaSatuan;

        $detail->update([
            'jumlah' => $request->jumlah,
            'subtotal' => $subtotal
        ]);

        if ($request->wantsJson() || $request->header('Accept') === 'application/json') {
            $keranjang = Keranjang::where('id_user', Auth::id())
                ->where('status', 'active')
                ->with('details')
                ->first();

            $total_payment = $keranjang->details->sum('subtotal');

            return response()->json([
                'success' => true,
                'jumlah' => $request->jumlah,
                'subtotal' => 'Rp ' . number_format($subtotal, 0, ',', '.'),
                'total_payment' => 'Rp ' . number_format($total_payment, 0, ',', '.')
            ]);
        }

        return redirect()->route('keranjang.index')
            ->with('success', 'Jumlah unit berhasil diperbarui!');
    }
}
