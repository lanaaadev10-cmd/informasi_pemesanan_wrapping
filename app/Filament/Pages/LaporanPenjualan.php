<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Actions\Action;

class LaporanPenjualan extends Page
{
    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static ?string $navigationLabel = 'Laporan Penjualan';
    protected static ?string $title = 'Laporan Penjualan';
    protected static string|null|\UnitEnum $navigationGroup = 'Manajemen Transaksi';
    protected static ?int $navigationSort = 3;

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
