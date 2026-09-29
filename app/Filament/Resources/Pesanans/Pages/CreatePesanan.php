<?php

namespace App\Filament\Resources\Pesanans\Pages;

use App\Filament\Resources\Pesanans\PesananResource;
use App\Models\DetailPesanan;
use App\Models\FormPesanan;
use App\Models\Layanan;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Models\User;
use App\Services\SlotKuotaService;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreatePesanan extends CreateRecord
{
    protected static string $resource = PesananResource::class;

    protected Width | string | null $maxContentWidth = Width::Full;

    protected ?string $accountInfoForNotification = null;

    protected function handleRecordCreation(array $data): Model
    {
        return DB::transaction(function () use ($data) {
            $adminId = auth()->id();
            $user = null;
            $this->accountInfoForNotification = null;

            // 1. Tentukan Pelanggan Berdasarkan Pilihan
            $opsiPelanggan = $data['opsi_pelanggan'] ?? 'dengan_akun';

            if ($opsiPelanggan === 'tanpa_akun') {
                // Skenario 1: Pesan Cepat (Tanpa Akun / Guest)
                $customerName = trim($data['guest_customer_name'] ?? 'Pelanggan Walk-In');
                $phone = trim($data['guest_whatsapp_number'] ?? '-');
                $user = null;
                $this->accountInfoForNotification = ' (Pesanan offline dicatat tanpa akun).';
            } else {
                // Skenario 2: Menggunakan Akun (Akun terdaftar atau yang baru dibuat via tombol [+])
                $user = User::find($data['id_user'] ?? null);
                $customerName = $user?->name ?? trim($data['customer_name'] ?? 'Pelanggan Walk-In');
                $phone = !empty($data['whatsapp_number'])
                    ? trim($data['whatsapp_number'])
                    : ($user?->phone ?? '-');

                if ($user) {
                    $this->accountInfoForNotification = " Terhubung ke akun pelanggan: {$user->name} ({$user->email}).";
                }
            }

            // 2. Kode Pesanan Unik Walk-In
            $kodePesanan = 'PSN-OFF-' . strtoupper(Str::random(6));

            // 3. Tanggal & Total Harga
            $bookingDate = $data['booking_date'] ?? now()->toDateString();
            $totalHarga = (float) ($data['total_harga'] ?? 0);
            $orderStatus = $data['status'] ?? Pesanan::STATUS_SEDANG_DIPROSES;

            // 4. Simpan Header Pesanan
            $pesanan = Pesanan::create([
                'id_user'             => $user?->id,
                'kode_pesanan'        => $kodePesanan,
                'tanggal_pesan'       => now(),
                'booking_date'        => $bookingDate,
                'status'              => $orderStatus,
                'total_harga'         => $totalHarga,
                'whatsapp_number'     => $phone,
                'order_source'        => 'offline',
                'customer_name'       => $customerName,
                'address'             => 'Workshop / Bengkel Dantie Stiker (Walk-in)',
                'created_by_admin_id' => $adminId,
                'catatan_admin'       => $data['catatan_admin'] ?? 'Pesanan walk-in offline dibuat langsung oleh admin di bengkel.',
            ]);

            // 5. Simpan Detail Item Paket Layanan
            if (!empty($data['layanan_id'])) {
                DetailPesanan::create([
                    'id_pesanan'     => $pesanan->id_pesanan,
                    'id_paket'       => $data['layanan_id'],
                    'jumlah'         => 1,
                    'harga_satuan'   => $totalHarga,
                    'subtotal'       => $totalHarga,
                    'catatan_custom' => $data['catatan_admin'] ?? null,
                ]);
            }

            // 6. Simpan Form Data Kendaraan & Jadwal Sesi
            FormPesanan::create([
                'id_pesanan'          => $pesanan->id_pesanan,
                'nama_pemesan'        => $customerName,
                'alamat_pengiriman'   => 'Workshop Dantie Stiker',
                'no_hp'               => $phone,
                'model_kendaraan'     => $data['model_kendaraan'] ?? 'Kendaraan Customer',
                'warna_kendaraan'     => $data['warna_kendaraan'] ?? '-',
                'nomor_polisi'        => $data['nomor_polisi'] ?? '-',
                'tahun_produksi'      => $data['tahun_produksi'] ?? date('Y'),
                'lokasi_pengerjaan'   => 'toko',
                'jadwal_pengerjaan'   => $bookingDate . ' 09:00:00',
                'estimasi_durasi'     => $data['estimasi_durasi'] ?? '3 - 4 Hari Kerja',
                'keterangan_tambahan' => $data['catatan_admin'] ?? null,
                'status_verifikasi'   => 'terverifikasi',
            ]);

            // 7. Simpan Transaksi Pembayaran Kasir
            $paymentMethodValue = match ($data['payment_method'] ?? 'cash') {
                'transfer_bank'     => \App\Enums\PaymentMethod::TRANSFER_BANK,
                'transfer_e_wallet' => \App\Enums\PaymentMethod::TRANSFER_E_WALLET,
                default             => \App\Enums\PaymentMethod::CASH,
            };

            $paymentStatusValue = match ($data['payment_status'] ?? 'lunas') {
                'lunas', 'dp' => \App\Enums\PaymentStatus::VERIFIED,
                default       => \App\Enums\PaymentStatus::PENDING,
            };

            $isVerified = ($data['payment_status'] ?? 'lunas') !== 'pending';

            Pembayaran::create([
                'id_pesanan'        => $pesanan->id_pesanan,
                'metode_pembayaran' => $paymentMethodValue,
                'jumlah_bayar'      => $totalHarga,
                'status'            => $paymentStatusValue,
                'tgl_bayar'         => now(),
                'verifikasi_admin'  => $isVerified ? 'diverifikasi' : 'menunggu',
                'catatan_admin'     => 'Pembayaran kasir walk-in (' . strtoupper($data['payment_method'] ?? 'cash') . ')',
            ]);

            // 8. Bersihkan cache slot kuota
            try {
                app(SlotKuotaService::class)->clearCache($bookingDate);
            } catch (\Throwable $e) {
                // Ignore cache clearing failure
            }

            return $pesanan;
        });
    }

    protected function getRedirectUrl(): string
    {
        return PesananResource::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        $kode = $this->record->kode_pesanan ?? '-';
        $extra = $this->accountInfoForNotification ?? '';

        return Notification::make()
            ->success()
            ->title('Pesanan Walk-In Berhasil Dibuat')
            ->body("Pesanan #{$kode} telah tercatat di sistem.{$extra}");
    }
}
