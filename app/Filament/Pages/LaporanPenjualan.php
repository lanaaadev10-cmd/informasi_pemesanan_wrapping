<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Pages\Page;

class LaporanPenjualan extends Page
{
    protected static ?string $navigationLabel = 'Laporan Penjualan';
    protected static ?string $title = 'Laporan Penjualan';
    protected static string|null|\UnitEnum $navigationGroup = 'Laporan & Pembayaran';
    protected static ?int $navigationSort = 1;

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
}
