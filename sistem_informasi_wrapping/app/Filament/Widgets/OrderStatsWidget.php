<?php

namespace App\Filament\Widgets;

use App\Models\Pesanan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrderStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Pesanan', Pesanan::count())
                ->description('Jumlah semua pesanan')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('primary'),

            Stat::make('Total Pendapatan', 'Rp ' . number_format(Pesanan::sum('total_harga'), 0, ',', '.'))
                ->description('Total akumulasi pendapatan')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Perlu Verifikasi', Pesanan::where('status', 'menunggu_verifikasi')->count())
                ->description('Pesanan baru masuk')
                ->descriptionIcon('heroicon-m-magnifying-glass')
                ->color('warning'),

            Stat::make('Pesanan Selesai', Pesanan::where('status', 'selesai')->count())
                ->description('Total pengerjaan tuntas')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
        ];
    }
}
