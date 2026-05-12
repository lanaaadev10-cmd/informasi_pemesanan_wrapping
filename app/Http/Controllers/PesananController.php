<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\FormPesanan;
use App\Models\Pembayaran;
use App\Models\Notifikasi;
use App\Models\Keranjang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PesananController extends Controller
{
    /**
     * Tampilkan daftar pesanan milik user yang login
     */
    public function index()
    {
        $pesanans = Pesanan::where('id_user', Auth::id())
            ->with(['form', 'pembayaran'])
            ->latest()
            ->get();

        return view('customer.pesanan.index', compact('pesanans'));
    }

    /**
     * Tampilkan detail satu pesanan
     */
    public function show($id_pesanan)
    {
        $pesanan = Pesanan::where('id_pesanan', $id_pesanan)
            ->where('id_user', Auth::id())
            ->with(['details.layanan', 'form', 'pembayaran'])
            ->firstOrFail();

        return view('customer.pesanan.show', compact('pesanan'));
    }

    /**
     * Buat pesanan baru dari keranjang (checkout)
     */
    public function checkout(Request $request)
    {
        $request->validate([
            'nama_pemesan'       => 'required|string|max:100',
            'alamat_pengiriman'  => 'required|string',
            'no_hp'              => 'required|string|max:20',
            'keterangan_tambahan'=> 'nullable|string|max:500',
        ]);

        // Ambil keranjang aktif user
        $keranjang = Keranjang::where('id_user', Auth::id())
            ->where('status', 'active')
            ->with('details.layanan')
            ->firstOrFail();

        // Hitung total harga
        $totalHarga = $keranjang->details->sum('subtotal');

        // Buat pesanan baru
        $pesanan = Pesanan::create([
            'id_user'        => Auth::id(),
            'kode_pesanan'   => 'PSN-' . strtoupper(Str::random(8)),
            'tanggal_pesan'  => now()->toDateString(),
            'status'         => 'menunggu_verifikasi',
            'total_harga'    => $totalHarga,
        ]);

        // Salin detail keranjang ke detail pesanan
        foreach ($keranjang->details as $item) {
            DetailPesanan::create([
                'id_pesanan'     => $pesanan->id_pesanan,
                'id_paket'       => $item->id_paket,
                'jumlah'         => $item->jumlah,
                'catatan_custom' => $item->catatan_custom,
                'harga_satuan'   => $item->harga_satuan,
                'subtotal'       => $item->subtotal,
            ]);
        }

        // Simpan form pengiriman
        FormPesanan::create([
            'id_pesanan'          => $pesanan->id_pesanan,
            'nama_pemesan'        => $request->nama_pemesan,
            'alamat_pengiriman'   => $request->alamat_pengiriman,
            'no_hp'               => $request->no_hp,
            'keterangan_tambahan' => $request->keterangan_tambahan,
            'status_verifikasi'   => 'pending',
        ]);

        // Kirim notifikasi ke user
        Notifikasi::create([
            'id_user'    => Auth::id(),
            'id_pesanan' => $pesanan->id_pesanan,
            'judul'      => 'Pesanan Diterima',
            'pesan'      => 'Pesanan Anda (' . $pesanan->kode_pesanan . ') sedang menunggu verifikasi admin.',
            'tipe'       => 'pesanan',
            'is_read'    => false,
        ]);

        // Ubah status keranjang menjadi checked_out
        $keranjang->update(['status' => 'checked_out']);

        return redirect()->route('pesanan.show', $pesanan->id_pesanan)
            ->with('success', 'Pesanan berhasil dibuat! Kode: ' . $pesanan->kode_pesanan);
    }

    /**
     * Upload bukti pembayaran
     */
    public function uploadBukti(Request $request, $id_pesanan)
    {
        $request->validate([
            'metode_pembayaran' => 'required|string',
            'bukti_transfer'    => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $pesanan = Pesanan::where('id_pesanan', $id_pesanan)
            ->where('id_user', Auth::id())
            ->firstOrFail();

        $path = $request->file('bukti_transfer')->store('bukti_transfer', 'public');

        Pembayaran::updateOrCreate(
            ['id_pesanan' => $pesanan->id_pesanan],
            [
                'metode_pembayaran' => $request->metode_pembayaran,
                'jumlah_bayar'      => $pesanan->total_harga,
                'bukti_transfer'    => $path,
                'status'            => 'sudah_dibayar',
                'tgl_bayar'         => now()->toDateString(),
                'verifikasi_admin'  => 'menunggu',
            ]
        );

        $pesanan->update(['status' => 'menunggu_pembayaran']);

        return redirect()->route('pesanan.show', $pesanan->id_pesanan)
            ->with('success', 'Bukti pembayaran berhasil dikirim!');
    }
}
