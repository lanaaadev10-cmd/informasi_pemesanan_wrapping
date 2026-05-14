<?php

namespace App\Filament\Widgets;

use App\Models\Pesanan;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class RevenueChartWidget extends ChartWidget
{
    protected ?string $heading = 'Pendapatan Bulanan';
    protected ?string $description = 'Pendapatan 12 bulan terakhir';
    protected static ?int $sort = 4;

    protected function getData(): array
    {
        $months = [];
        $revenue = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $startMonth = $date->clone()->startOfMonth();
            $endMonth = $date->clone()->endOfMonth();

            $months[] = $date->format('M Y');

            $monthlyRevenue = Pesanan::whereBetween('created_at', [$startMonth, $endMonth])
                ->sum('total_harga');

            $revenue[] = $monthlyRevenue / 1000000; // Convert to millions for readability
        }

        return [
            'datasets' => [
                [
                    'label' => 'Pendapatan (Juta Rupiah)',
                    'data' => $revenue,
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
