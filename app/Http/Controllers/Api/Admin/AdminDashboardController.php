<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Pembayaran;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * Admin Dashboard Controller
 * 
 * Admin-only endpoints for dashboard statistics
 */
class AdminDashboardController extends Controller
{
    /**
     * GET /api/admin/dashboard/stats
     * Get dashboard statistics
     */
    public function getStats()
    {
        $now = now();
        $thisMonth = $now->copy()->startOfMonth();
        $lastMonth = $now->copy()->subMonth()->startOfMonth();

        // Total orders
        $totalOrders = Pesanan::count();
        $ordersThisMonth = Pesanan::where('tanggal_pesan', '>=', $thisMonth)->count();

        // Total revenue
        $totalRevenue = Pesanan::where('status', 'selesai')->sum('total_harga');
        $revenueThisMonth = Pesanan::where('status', 'selesai')
            ->where('tanggal_pesan', '>=', $thisMonth)
            ->sum('total_harga');

        // Pending orders
        $pendingOrders = Pesanan::where('status', 'menunggu_konfirmasi_admin')
            ->orWhere('status', 'menunggu_pembayaran')
            ->orWhere('status', 'menunggu_verifikasi_pembayaran')
            ->count();

        // Pending payments
        $pendingPayments = Pembayaran::where('status_pembayaran', 'pending')->count();

        // Total customers
        $totalCustomers = User::whereDoesntHave('roles')->count();

        // Top services
        $topServices = DB::table('detail_pesanans')
            ->join('layanans', 'detail_pesanans.id_layanan', '=', 'layanans.id_layanan')
            ->select('layanans.nama_layanan', DB::raw('COUNT(*) as total_orders'))
            ->groupBy('layanans.id_layanan', 'layanans.nama_layanan')
            ->orderByDesc('total_orders')
            ->limit(5)
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Statistik dashboard berhasil diambil',
            'data' => [
                'total_orders' => $totalOrders,
                'orders_this_month' => $ordersThisMonth,
                'total_revenue' => (float) $totalRevenue,
                'revenue_this_month' => (float) $revenueThisMonth,
                'pending_orders' => $pendingOrders,
                'pending_payments' => $pendingPayments,
                'total_customers' => $totalCustomers,
                'top_services' => $topServices,
            ],
        ], 200);
    }

    /**
     * GET /api/admin/dashboard/chart-data
     * Get data for charts (monthly revenue, orders trend, etc)
     */
    public function getChartData()
    {
        // Monthly revenue for last 12 months
        $monthlyRevenue = collect(range(11, 0))->map(function ($month) {
            $date = now()->subMonths($month);
            $revenue = Pesanan::where('status', 'selesai')
                ->whereYear('tanggal_pesan', $date->year)
                ->whereMonth('tanggal_pesan', $date->month)
                ->sum('total_harga');

            return [
                'month' => $date->format('M Y'),
                'revenue' => (float) $revenue,
            ];
        });

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
    }
}
