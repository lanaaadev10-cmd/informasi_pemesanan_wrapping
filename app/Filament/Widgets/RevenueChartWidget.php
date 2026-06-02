<?php

namespace App\Filament\Widgets;

use App\Models\Pesanan;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class RevenueChartWidget extends ChartWidget
{
    protected ?string $heading = 'Pendapatan Bulanan';
    protected ?string $description = 'Pendapatan 12 bulan terakhir';
    protected static ?int $sort = 4;

    protected function getData(): array
    {
        $months = [];
        $revenue = array_fill(0, 12, 0);

        $rawRevenue = Pesanan::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, SUM(total_harga) as total")
            ->where('created_at', '>=', Carbon::now()->subMonths(11)->startOfMonth())
            ->where('status', \App\Enums\OrderStatus::SELESAI->value)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $key = $date->format('Y-m');
            $months[] = $date->format('M Y');
            $revenue[11 - $i] = ($rawRevenue[$key] ?? 0) / 1000000;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Pendapatan (Juta Rupiah)',
                    'data' => array_values($revenue),
                    'borderColor' => '#10B981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'borderWidth' => 3,
                    'fill' => true,
                    'tension' => 0.4,
                    'pointRadius' => 5,
                    'pointBackgroundColor' => '#10B981',
                    'pointBorderColor' => '#fff',
                    'pointBorderWidth' => 2,
                ],
            ],
            'labels' => $months,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
        ];
    }
}
