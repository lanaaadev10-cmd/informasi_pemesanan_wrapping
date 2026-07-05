<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\FormPesanan;
use App\Models\Pembayaran;
use App\Models\Notifikasi;
use App\Models\Keranjang;
use App\Models\User;
use App\Events\OrderCreated;
use App\Events\PaymentUploaded;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PesananController extends Controller
{
    /**
     * Daftar pesanan milik user yang login
     */
    public function index(Request $request)
    {
        $query = Pesanan::where('id_user', Auth::id())
            ->with(['form', 'pembayaran', 'details.layanan'])
            ->latest();

        if ($request->status === 'menunggu_pembayaran') {
            $query->whereIn('status', [
                Pesanan::STATUS_MENUNGGU_KONFIRMASI_ADMIN,
                Pesanan::STATUS_MENUNGGU_PEMBAYARAN,
                Pesanan::STATUS_MENUNGGU_VERIFIKASI_PEMBAYARAN,
            ]);
        } elseif ($request->status === 'berjalan') {
            $query->whereIn('status', [
                Pesanan::STATUS_DIKONFIRMASI,
                Pesanan::STATUS_SEDANG_DIPROSES,
            ]);
        } elseif ($request->status === 'selesai') {
            $query->where('status', Pesanan::STATUS_SELESAI);
        } else {
            // Default "Riwayat Pesanan" (tanpa parameter status):
            // Sesuai permintaan user: "hingga pembayaran selesai dan status di terima baru invoice mengunkan pdf baru bisa di unudh baru masuk ke dalam riwayat pesananan"
            $query->whereIn('status', [
                Pesanan::STATUS_DIKONFIRMASI,
                Pesanan::STATUS_SEDANG_DIPROSES,
                Pesanan::STATUS_SELESAI,
                Pesanan::STATUS_DITOLAK,
            ]);
        }

        $pesanans = $query->paginate(10);

        return view('dashboard.customer.pesanan.index', compact('pesanans'));
    }

    /**
     * Detail satu pesanan
     */
    public function show($id_pesanan)
    {
        $pesanan = Pesanan::where('id_pesanan', $id_pesanan)
            ->where('id_user', Auth::id())
            ->with(['details.layanan', 'form', 'pembayaran'])
            ->firstOrFail();

        return view('dashboard.customer.pesanan.show', compact('pesanan'));
    }

    /**
     * LANGKAH 1: User checkout → status: menunggu_konfirmasi_admin
     */
    public function checkout(Request $request)
    {
        $request->validate([
            'nama_pemesan'        => 'required|string|max:100',
            'alamat_pengiriman'   => 'required|string',
            'no_hp'               => 'required|string|max:20',
            'model_kendaraan'     => 'required|string|max:100',
            'warna_kendaraan'     => 'required|string|max:100',
            'lokasi_pengerjaan'   => 'required|string|in:toko',
            'jadwal_pengerjaan'   => 'required|date',
            'keterangan_tambahan' => 'nullable|string|max:500',
        ]);

        $keranjang = Keranjang::where('id_user', Auth::id())
            ->where('status', 'active')
            ->with('details.layanan')
            ->firstOrFail();

        $totalHarga = $keranjang->details->sum('subtotal');

        // Buat pesanan dengan status PERTAMA: menunggu konfirmasi admin
        $pesanan = Pesanan::create([
            'id_user'       => Auth::id(),
            'kode_pesanan'  => 'PSN-' . strtoupper(Str::random(8)),
            'tanggal_pesan' => now()->toDateString(),
            'status'        => Pesanan::STATUS_MENUNGGU_KONFIRMASI_ADMIN,
            'total_harga'   => $totalHarga,
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

        // Parsing nomor polisi dan tahun dari keterangan_tambahan jika tidak dikirim langsung di request
        $nopol = $request->nomor_polisi;
        $tahun = $request->tahun_produksi;
        if (!$nopol && $request->keterangan_tambahan) {
            if (preg_match('/Nomor Polisi:\s*([^|]+)/i', $request->keterangan_tambahan, $m)) {
                $nopol = trim($m[1]);
            }
        }
        if (!$tahun && $request->keterangan_tambahan) {
            if (preg_match('/Tahun Produksi:\s*([0-9]+)/i', $request->keterangan_tambahan, $m)) {
                $tahun = trim($m[1]);
            }
        }

        $keteranganLengkap = "Model: {$request->model_kendaraan} | Warna: {$request->warna_kendaraan}\n" .
                             "Lokasi: " . ucfirst($request->lokasi_pengerjaan) . "\n" .
                             "Jadwal: " . date('d M Y', strtotime($request->jadwal_pengerjaan)) . "\n" .
                             "Catatan: " . ($request->keterangan_tambahan ?? '-');

        FormPesanan::create([
            'id_pesanan'          => $pesanan->id_pesanan,
            'nama_pemesan'        => $request->nama_pemesan,
            'alamat_pengiriman'   => $request->alamat_pengiriman,
            'no_hp'               => $request->no_hp,
            'keterangan_tambahan' => $keteranganLengkap,
            'status_verifikasi'   => 'pending',
            // Kolom Kendaraan & Jadwal Sesi
            'model_kendaraan'     => $request->model_kendaraan,
            'warna_kendaraan'     => $request->warna_kendaraan,
            'nomor_polisi'        => $nopol,
            'tahun_produksi'      => $tahun,
            'lokasi_pengerjaan'   => $request->lokasi_pengerjaan,
            'jadwal_pengerjaan'   => $request->jadwal_pengerjaan,
            'estimasi_durasi'     => '4 - 5 Hari Kerja',
        ]);

        // Dispatch OrderCreated event (handles notifications)
        OrderCreated::dispatch($pesanan);

        // Notifikasi ke Admin via Filament
        $this->kirimNotifikasiAdmin(
            '🛒 Pesanan Baru dari ' . Auth::user()->name,
            'Pesanan #' . $pesanan->kode_pesanan . ' menunggu konfirmasi Anda.'
        );

        // Kosongkan keranjang
        $keranjang->update(['status' => 'checked_out']);

        return redirect()->route('pesanan.show', $pesanan->id_pesanan)
            ->with('toast_success', 'Pesanan berhasil dibuat! Tunggu konfirmasi dari admin.');
    }

    /**
     * LANGKAH 3: User upload bukti pembayaran → status: menunggu_verifikasi_pembayaran
     */
    public function uploadBukti(Request $request, $id_pesanan)
    {
        $request->validate([
            'metode_pembayaran' => 'required|string',
            'bukti_transfer'    => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $pesanan = Pesanan::where('id_pesanan', $id_pesanan)
            ->where('id_user', Auth::id())
            ->firstOrFail();

        $statusVal = $pesanan->status instanceof \App\Enums\OrderStatus ? $pesanan->status->value : $pesanan->status;
        // Pastikan status memang menunggu pembayaran
        if ($statusVal !== Pesanan::STATUS_MENUNGGU_PEMBAYARAN) {
            return back()->with('toast_error', 'Pesanan ini tidak dalam status menunggu pembayaran.');
        }

        $path = $request->file('bukti_transfer')->store('bukti_transfer', 'public');

        $pembayaran = Pembayaran::updateOrCreate(
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

        // Update ke status: menunggu verifikasi pembayaran
        $pesanan->update(['status' => Pesanan::STATUS_MENUNGGU_VERIFIKASI_PEMBAYARAN]);

        // Dispatch PaymentUploaded event (handles admin notification)
        PaymentUploaded::dispatch($pembayaran);

        // Notifikasi ke Admin via Filament
        $this->kirimNotifikasiAdmin(
            '💳 Bukti Pembayaran Baru dari ' . Auth::user()->name,
            'Pesanan #' . $pesanan->kode_pesanan . ' telah mengunggah bukti pembayaran dan menunggu verifikasi Anda.'
        );

        // Notifikasi ke User
        Notifikasi::create([
            'id_user'    => Auth::id(),
            'id_pesanan' => $pesanan->id_pesanan,
            'judul'      => '📤 Bukti Pembayaran Terkirim',
            'pesan'      => 'Bukti pembayaran pesanan #' . $pesanan->kode_pesanan . ' berhasil dikirim. Tunggu verifikasi dari admin.',
            'tipe'       => 'info',
            'is_read'    => false,
        ]);

        return redirect()->route('pesanan.show', $pesanan->id_pesanan)
            ->with('toast_success', 'Bukti pembayaran berhasil dikirim! Tunggu verifikasi admin.');
    }

    /**
     * Invoice — hanya bisa diakses jika status sudah dikonfirmasi/diproses/selesai
     */
    public function invoice($id_pesanan)
    {
        $query = Pesanan::where('id_pesanan', $id_pesanan);

        if (!auth()->user()->hasRole('admin')) {
            $query->where('id_user', Auth::id());
        }

        $pesanan = $query->with(['details.layanan', 'form', 'user', 'pembayaran'])->firstOrFail();

        // Hanya tampilkan invoice jika sudah dikonfirmasi
        if (!auth()->user()->hasRole('admin') && !$pesanan->bisaUnduhInvoice()) {
            return back()->with('toast_error', 'Invoice belum tersedia. Tunggu konfirmasi pembayaran dari admin.');
        }

        return view('dashboard.customer.pesanan.invoice', compact('pesanan'));
    }

    /**
     * Helper: Kirim notifikasi ke semua admin via Filament
     */
    private function kirimNotifikasiAdmin(string $judul, string $pesan): void
    {
        try {
            $admins = User::role('admin')->get();
            $filNotif = \Filament\Notifications\Notification::make()
                ->title($judul)
                ->body($pesan)
                ->icon('heroicon-o-shopping-bag')
                ->warning();
            
            \Illuminate\Support\Facades\Notification::sendNow($admins, $filNotif->toDatabase());
        } catch (\Exception $e) {
            // Silently fail
        }
    }
}
