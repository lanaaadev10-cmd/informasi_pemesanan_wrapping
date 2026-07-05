<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Pembayaran;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

/**
 * Admin Dashboard Controller
 *
 * Admin-only endpoints for dashboard statistics
 */
class AdminDashboardController extends Controller
{
    /**
     * GET /api/admin/dashboard/stats
     * Get dashboard statistics with caching
     */
    public function getStats()
    {
        $cacheKey = 'admin_dashboard_stats';
        $cacheDuration = config('app-settings.cache.dashboard_stats', 3600);

        return Cache::remember($cacheKey, $cacheDuration, function () {
            $now = now();
            $thisMonth = $now->copy()->startOfMonth();

            // Combine multiple queries into single query
            $stats = DB::table('pesanans')
                ->select(
                    DB::raw('COUNT(*) as total_orders'),
                    DB::raw('SUM(CASE WHEN tanggal_pesan >= ? THEN 1 ELSE 0 END) as orders_this_month'),
                    DB::raw('SUM(CASE WHEN status = "selesai" THEN total_harga ELSE 0 END) as total_revenue'),
                    DB::raw('SUM(CASE WHEN status = "selesai" AND tanggal_pesan >= ? THEN total_harga ELSE 0 END) as revenue_this_month'),
                    DB::raw('SUM(CASE WHEN status IN ("menunggu_konfirmasi_admin", "menunggu_pembayaran", "menunggu_verifikasi_pembayaran") THEN 1 ELSE 0 END) as pending_orders')
                )
                ->addBinding([$thisMonth, $thisMonth])
                ->first();

            $pendingPayments = Pembayaran::where('status', 'menunggu_pembayaran')->count();
            $totalCustomers = User::whereDoesntHave('roles')->count();

            // Top services
            $topServicesLimit = config('app-settings.dashboard.top_services_limit', 5);
            $topServices = DB::table('detail_pesanans')
                ->join('layanans', 'detail_pesanans.id_paket', '=', 'layanans.id_layanan')
                ->select('layanans.nama_layanan', DB::raw('COUNT(*) as total_orders'))
                ->groupBy('layanans.id_layanan', 'layanans.nama_layanan')
                ->orderByDesc('total_orders')
                ->limit($topServicesLimit)
                ->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Statistik dashboard berhasil diambil',
                'data' => [
                    'total_orders' => (int) $stats->total_orders,
                    'orders_this_month' => (int) $stats->orders_this_month,
                    'total_revenue' => (float) $stats->total_revenue ?? 0,
                    'revenue_this_month' => (float) $stats->revenue_this_month ?? 0,
                    'pending_orders' => (int) $stats->pending_orders,
                    'pending_payments' => $pendingPayments,
                    'total_customers' => $totalCustomers,
                    'top_services' => $topServices,
                ],
            ], 200);
        });
    }

    /**
     * GET /api/admin/dashboard/chart-data
     * Get data for charts with caching and optimized queries
     */
    public function getChartData()
    {
        $cacheKey = 'admin_dashboard_chart_data';
        $cacheDuration = config('app-settings.cache.dashboard_chart_data', 3600);

        return Cache::remember($cacheKey, $cacheDuration, function () {
            $monthsLookback = config('app-settings.dashboard.chart_months_lookback', 12);
            // Monthly revenue for last 12 months - single query with GROUP BY
            $monthlyRevenue = Pesanan::where('status', 'selesai')
                ->where('tanggal_pesan', '>=', now()->subMonths($monthsLookback))
                ->selectRaw('DATE_FORMAT(tanggal_pesan, "%Y-%m") as month')
                ->selectRaw('SUM(total_harga) as revenue')
                ->groupByRaw('DATE_FORMAT(tanggal_pesan, "%Y-%m")')
                ->orderByRaw('DATE_FORMAT(tanggal_pesan, "%Y-%m") DESC')
                ->get()
                ->map(function ($item) {
                    return [
                        'month' => $item->month,
                        'revenue' => (float) $item->revenue,
                    ];
                })
                ->reverse()
                ->values();

            // Order status distribution
            $statusDistribution = Pesanan::select('status', DB::raw('COUNT(*) as count'))
                ->groupBy('status')
                ->get()
                ->map(function ($item) {
                    return [
                        'status' => \App\Enums\OrderStatus::from($item->status)->label(),
                        'count' => $item->count,
                    ];
                });

            // Payment method distribution
            $paymentDistribution = Pembayaran::select('metode_pembayaran', DB::raw('COUNT(*) as count'))
                ->groupBy('metode_pembayaran')
                ->get()
                ->map(function ($item) {
                    return [
                        'method' => \App\Enums\PaymentMethod::from($item->metode_pembayaran)->label(),
                        'count' => $item->count,
                    ];
                });

            return response()->json([
                'status' => 'success',
                'message' => 'Data chart berhasil diambil',
                'data' => [
                    'monthly_revenue' => $monthlyRevenue,
                    'status_distribution' => $statusDistribution,
                    'payment_distribution' => $paymentDistribution,
                ],
            ], 200);
        });
    }
}
