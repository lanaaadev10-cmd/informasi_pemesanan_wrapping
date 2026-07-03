<?php

namespace App\Filament\Widgets;

use App\Models\Pesanan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Enums\OrderStatus;

class OrderStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $selesai = OrderStatus::SELESAI->value;

        return [
            // Alternatif A: hitung SEMUA pesanan (termasuk ditolak/pending)
            Stat::make('Total Pesanan', Pesanan::count())
                ->description('Semua pesanan (termasuk ditolak/pending)')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('primary'),

            // Fix: HANYA pesanan status 'selesai' yg diakui sebagai pendapatan
            Stat::make('Total Pendapatan', 'Rp ' . number_format(
                Pesanan::where('status', $selesai)->sum('total_harga'),
                0, ',', '.'
            ))
                ->description('Akumulasi dari pesanan selesai')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Perlu Verifikasi', Pesanan::where('status', OrderStatus::MENUNGGU_VERIFIKASI_PEMBAYARAN->value)->count())
                ->description('Pesanan menunggu validasi pembayaran')
                ->descriptionIcon('heroicon-m-magnifying-glass')
                ->color('warning'),

            // Alternatif B: khusus pesanan sukses (sejalan dgn Total Pendapatan)
            Stat::make('Pesanan Selesai', Pesanan::where('status', $selesai)->count())
                ->description('Total pengerjaan tuntas')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
        ];
    }
}
