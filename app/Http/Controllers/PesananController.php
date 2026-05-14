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
            ->with(['form', 'pembayaran', 'details.layanan'])
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
            'model_kendaraan'    => 'required|string|max:100',
            'warna_kendaraan'    => 'required|string|max:100',
            'lokasi_pengerjaan'  => 'required|string|in:toko,rumah',
            'jadwal_pengerjaan'  => 'required|date',
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

        // Gabungkan info kendaraan & jadwal ke keterangan tambahan agar tetap kompatibel dengan DB lama
        $keteranganLengkap = "Model: {$request->model_kendaraan} | Warna: {$request->warna_kendaraan}\n" .
                             "Lokasi: " . ucfirst($request->lokasi_pengerjaan) . "\n" .
                             "Jadwal: " . date('d M Y H:i', strtotime($request->jadwal_pengerjaan)) . "\n" .
                             "Catatan: " . ($request->keterangan_tambahan ?? '-');

        // Simpan form pengiriman
        FormPesanan::create([
            'id_pesanan'          => $pesanan->id_pesanan,
            'nama_pemesan'        => $request->nama_pemesan,
            'alamat_pengiriman'   => $request->alamat_pengiriman,
            'no_hp'               => $request->no_hp,
            'keterangan_tambahan' => $keteranganLengkap,
            'status_verifikasi'   => 'pending',
        ]);

        // Kirim notifikasi ke user (Database notification)
        Notifikasi::create([
            'id_user'    => Auth::id(),
            'id_pesanan' => $pesanan->id_pesanan,
            'judul'      => 'Pesanan Berhasil Dibuat',
            'pesan'      => 'Pesanan Anda (' . $pesanan->kode_pesanan . ') telah dibuat. Tunggu konfirmasi admin untuk pembayaran.',
            'tipe'       => 'pesanan',
            'is_read'    => false,
        ]);

        // Kirim notifikasi ke Admin (Filament) - Tanpa Action Link untuk menghindari error class not found
        try {
            $admins = \App\Models\User::role('admin')->get();
            \Filament\Notifications\Notification::make()
                ->title('Pesanan Baru Masuk')
                ->body('Ada pesanan baru dari ' . $request->nama_pemesan . ' (' . $pesanan->kode_pesanan . ')')
                ->icon('heroicon-o-shopping-bag')
                ->color('success')
                ->sendToDatabase($admins);
        } catch (\Exception $e) {
            // Silently fail admin notification if class error occurs
        }

        // Ubah status keranjang menjadi checked_out
        $keranjang->update(['status' => 'checked_out']);

        return redirect()->route('pesanan.show', $pesanan->id_pesanan)
            ->with('toast_success', 'Pesanan Anda telah dibuat!');
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

        $pesanan->update(['status' => 'menunggu_konfirmasi']);

        return redirect()->route('pesanan.show', $pesanan->id_pesanan)
            ->with('toast_success', 'Pembayaran Anda berhasil! Tunggu konfirmasi dari admin melalui WA.');
    }

    /**
     * Tampilkan Invoice untuk dicetak
     */
    public function invoice($id_pesanan)
    {
        $query = Pesanan::where('id_pesanan', $id_pesanan);

        // Jika bukan admin, hanya bisa lihat pesanan sendiri
        if (!auth()->user()->hasRole('admin')) {
            $query->where('id_user', Auth::id());
        }

        $pesanan = $query->with(['details.layanan', 'form', 'user'])
            ->firstOrFail();

        return view('customer.pesanan.invoice', compact('pesanan'));
    }
}
