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
        // Query dasar: Hanya pesanan yang tuntas / Selesai
        $completedQuery = Pesanan::where('status', 'Selesai');

        return [
            // Total Pendapatan Hari Ini
            'totalPendapatanHariIni' => (clone $completedQuery)
            ->whereDate('tanggal_pesan', Carbon::today())
            ->sum('total_harga'),

            'butuhDiverifikasi' => Pesanan::where('status', 'menunggu_konfirmasi_admin')->count(),

            'jumlahPesananSelesaiBulanIni' => (clone $completedQuery)
            ->whereMonth('tanggal_pesan', Carbon::now()->month)
            ->whereYear('tanggal_pesan', Carbon::now()->year)
            ->count(),
        ];
    }
}