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

        return view('customer.keranjang.index', compact('keranjang'));
    }

    /**
     * Tambahkan paket layanan ke keranjang
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

        // Cek apakah item sudah ada di keranjang
        $existingDetail = DetailKeranjang::where('id_keranjang', $keranjang->id_keranjang)
            ->where('id_paket', $request->id_paket)
            ->first();

        $hargaSatuan = $layanan->harga ?? 0;
        $subtotal    = $hargaSatuan * $request->jumlah;

        if ($existingDetail) {
            $existingDetail->update([
                'jumlah'   => $existingDetail->jumlah + $request->jumlah,
                'subtotal' => ($existingDetail->jumlah + $request->jumlah) * $hargaSatuan
            ]);
        } else {
            // Tambahkan item ke detail keranjang
            DetailKeranjang::create([
                'id_keranjang'   => $keranjang->id_keranjang,
                'id_paket'       => $request->id_paket,
                'jumlah'         => $request->jumlah,
                'catatan_custom' => $request->catatan_custom,
                'harga_satuan'   => $hargaSatuan,
                'subtotal'       => $subtotal,
            ]);
        }

        // Jika request datang dari tombol "Pesan Sekarang", langsung ke checkout
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
}
