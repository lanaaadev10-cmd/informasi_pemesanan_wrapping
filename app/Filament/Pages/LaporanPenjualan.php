<?php

namespace App\Filament\Pages;

use App\Models\Pesanan;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Illuminate\Support\Carbon;

class LaporanPenjualan extends Page
{
    protected static ?string $navigationLabel = 'Laporan Penjualan';
    protected static ?string $title = 'Laporan Penjualan';
    protected static \UnitEnum|string|null $navigationGroup = 'Transaksi';
    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'laporan-penjualan';

    protected string $view = 'filament.pages.laporan-penjualan';

    public function getHeaderActions(): array
    {
        return [
            Action::make('cetakHarian')
                ->label('Cetak Harian')
                ->color('info')
                ->url(fn () => route('admin.laporan', ['type' => 'hari'])),
            Action::make('cetakMingguan')
                ->label('Cetak Mingguan')
                ->color('success')
                ->url(fn () => route('admin.laporan', ['type' => 'minggu'])),
            Action::make('cetakBulanan')
                ->label('Cetak Bulanan')
                ->color('warning')
                ->url(fn () => route('admin.laporan', ['type' => 'bulan'])),
        ];
    }

    // Method untuk mengirim data hasil kalkulasi ke view Blade
    protected function getViewData(): array
    {
        $todayBookingRevenue = (float) \App\Models\BookingPayment::whereIn('status', ['verified', 'approved'])
            ->whereDate('created_at', Carbon::today())
            ->sum('amount');

        $completedPesananQuery = Pesanan::whereIn('status', [\App\Enums\OrderStatus::SELESAI->value, 'selesai']);

        $totalPendapatanHariIni = (float) (clone $completedPesananQuery)
            ->whereDate('tanggal_pesan', Carbon::today())
            ->sum('total_harga') + $todayBookingRevenue;

        $butuhDiverifikasi = Pesanan::whereIn('status', [Pesanan::STATUS_MENUNGGU_KONFIRMASI_ADMIN, Pesanan::STATUS_MENUNGGU_VERIFIKASI_PEMBAYARAN])->count()
            + \App\Models\Booking::whereIn('status', [\App\Enums\BookingStatus::PENDING->value, \App\Enums\BookingStatus::PAYMENT_UPLOADED->value])->count();

        $jumlahSelesaiBulanIni = (clone $completedPesananQuery)
            ->whereMonth('tanggal_pesan', Carbon::now()->month)
            ->whereYear('tanggal_pesan', Carbon::now()->year)
            ->count() + \App\Models\Booking::where('status', \App\Enums\BookingStatus::COMPLETED->value)
            ->whereMonth('booking_date', Carbon::now()->month)
            ->whereYear('booking_date', Carbon::now()->year)
            ->count();

        return [
            'totalPendapatanHariIni' => $totalPendapatanHariIni,
            'butuhDiverifikasi' => $butuhDiverifikasi,
            'jumlahPesananSelesaiBulanIni' => $jumlahSelesaiBulanIni,
        ];
    }
}