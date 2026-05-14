<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type', 'hari');
        $now = Carbon::now();

        $query = Pesanan::with(['user', 'details.layanan'])
            ->whereIn('status', ['dibayar', 'selesai']);

        if ($type === 'hari') {
            $query->whereDate('created_at', Carbon::today());
            $title = "Harian (" . $now->format('d M Y') . ")";
        } elseif ($type === 'minggu') {
            $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            $title = "Mingguan (" . Carbon::now()->startOfWeek()->format('d M') . " - " . Carbon::now()->endOfWeek()->format('d M Y') . ")";
        } elseif ($type === 'bulan') {
            $query->whereMonth('created_at', Carbon::now()->month)
                  ->whereYear('created_at', Carbon::now()->year);
            $title = "Bulanan (" . $now->format('F Y') . ")";
        }

        $pesanans = $query->latest()->get();
        $totalPendapatan = $pesanans->sum('total_harga');

        return view('admin.laporan.print', compact('pesanans', 'title', 'totalPendapatan', 'type'));
    }
}
