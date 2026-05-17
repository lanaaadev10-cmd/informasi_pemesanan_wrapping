<x-filament-panels::page>
    <style>
        /* Scoped styles for high-fidelity admin panel reporting */
        .lp-grid-stats {
            display: grid;
            grid-template-cols: 1fr;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        @media (min-width: 768px) {
            .lp-grid-stats {
                grid-template-cols: repeat(3, 1fr);
            }
        }
        .lp-stat-card {
            position: relative;
            overflow: hidden;
            padding: 2rem;
            background-color: #111827; /* bg-gray-900 */
            border-radius: 1.5rem;
            border: 1px solid #1f2937;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3);
        }
        .lp-stat-card-title {
            font-size: 0.65rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.25em;
            margin-bottom: 0.75rem;
        }
        .lp-stat-card-val {
            font-size: 1.875rem;
            font-weight: 900;
            color: #ffffff;
            font-style: italic;
            letter-spacing: -0.03em;
        }

        .lp-grid-reports {
            display: grid;
            grid-template-cols: 1fr;
            gap: 2rem;
        }
        @media (min-width: 768px) {
            .lp-grid-reports {
                grid-template-cols: repeat(3, 1fr);
            }
        }
        
        .lp-report-card {
            background-color: #ffffff;
            padding: 2.5rem;
            border-radius: 2rem;
            border: 1px solid #f3f4f6;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            display: flex;
            flex-direction: column;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }
        .dark .lp-report-card {
            background-color: #18181b; /* zinc-900 */
            border-color: #27272a;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2);
        }
        .lp-report-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.08);
        }
        .dark .lp-report-card:hover {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.4);
        }

        .lp-icon-box {
            width: 3.5rem;
            height: 3.5rem;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            transition: transform 0.3s ease;
        }
        .lp-report-card:hover .lp-icon-box {
            transform: scale(1.08);
        }

        .lp-report-title {
            font-size: 1.35rem;
            font-weight: 800;
            color: #18181b;
            margin-bottom: 0.75rem;
            letter-spacing: -0.02em;
        }
        .dark .lp-report-title {
            color: #ffffff;
        }
        .lp-report-desc {
            font-size: 0.85rem;
            color: #71717a;
            margin-bottom: 2rem;
            line-height: 1.6;
            font-style: italic;
            font-weight: 500;
            flex-grow: 1;
        }
        .dark .lp-report-desc {
            color: #a1a1aa;
        }

        .lp-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
            padding: 1.15rem;
            color: #ffffff;
            border-radius: 1rem;
            font-weight: 800;
            font-size: 0.75rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            transition: all 0.3s ease;
            cursor: pointer;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .lp-btn:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }
        .lp-btn:active {
            transform: translateY(1px);
        }
    </style>

    {{-- Header Stats --}}
    <div class="lp-grid-stats">
        <!-- Revenue Stat -->
        <div class="lp-stat-card">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-orange-600/20 blur-[50px] rounded-full"></div>
            <div class="relative z-10">
                <p class="lp-stat-card-title text-orange-500">Total Revenue Today</p>
                @php
                    $todayIncome = \App\Models\Pesanan::where('status', 'dibayar')
                        ->whereDate('updated_at', today())
                        ->sum('total_harga');
                @endphp
                <h4 class="lp-stat-card-val">Rp {{ number_format($todayIncome, 0, ',', '.') }}</h4>
            </div>
        </div>
        
        <!-- Verification Stat -->
        <div class="lp-stat-card">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-blue-600/10 blur-[50px] rounded-full"></div>
            <div class="relative z-10">
                <p class="lp-stat-card-title text-blue-400">Orders to Verify</p>
                @php
                    $pendingCount = \App\Models\Pesanan::where('status', 'menunggu_verifikasi')->count();
                @endphp
                <h4 class="lp-stat-card-val">{{ $pendingCount }} <span class="text-xs not-italic text-gray-500 ml-1">Orders</span></h4>
            </div>
        </div>

        <!-- Completion Stat -->
        <div class="lp-stat-card">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-green-600/10 blur-[50px] rounded-full"></div>
            <div class="relative z-10">
                <p class="lp-stat-card-title text-green-400">Completed This Month</p>
                @php
                    $monthCount = \App\Models\Pesanan::where('status', 'selesai')
                        ->whereMonth('updated_at', now()->month)
                        ->count();
                @endphp
                <h4 class="lp-stat-card-val">{{ $monthCount }} <span class="text-xs not-italic text-gray-500 ml-1">Orders</span></h4>
            </div>
        </div>
    </div>

    {{-- Main Custom Dashboard Grid --}}
    <div class="lp-grid-reports">
        {{-- Daily Report Card --}}
        <div class="lp-report-card">
            <div class="lp-icon-box bg-orange-50 dark:bg-orange-600/10 text-orange-600">
                <x-heroicon-o-calendar-days style="width: 32px; height: 32px;" />
            </div>
            <h3 class="lp-report-title">Laporan Harian</h3>
            <p class="lp-report-desc">
                "Ringkasan transaksi lengkap yang terjadi pada hari ini untuk monitoring harian."
            </p>
            <a href="{{ route('admin.laporan', ['type' => 'hari']) }}" target="_blank" 
               class="lp-btn bg-stone-900 dark:bg-orange-600 hover:bg-orange-600 dark:hover:bg-orange-700">
                PRINT DAILY REPORT 
                <x-heroicon-o-printer style="width: 16px; height: 16px; margin-left: 4px;" />
            </a>
        </div>

        {{-- Weekly Report Card --}}
        <div class="lp-report-card">
            <div class="lp-icon-box bg-blue-50 dark:bg-blue-600/10 text-blue-600">
                <x-heroicon-o-chart-bar style="width: 32px; height: 32px;" />
            </div>
            <h3 class="lp-report-title">Laporan Mingguan</h3>
            <p class="lp-report-desc">
                "Analisis performa penjualan dalam periode 7 hari terakhir untuk evaluasi mingguan."
            </p>
            <a href="{{ route('admin.laporan', ['type' => 'minggu']) }}" target="_blank" 
               class="lp-btn bg-stone-900 dark:bg-blue-600 hover:bg-blue-600 dark:hover:bg-blue-700">
                PRINT WEEKLY REPORT 
                <x-heroicon-o-printer style="width: 16px; height: 16px; margin-left: 4px;" />
            </a>
        </div>

        {{-- Monthly Report Card --}}
        <div class="lp-report-card">
            <div class="lp-icon-box bg-green-50 dark:bg-green-600/10 text-green-600">
                <x-heroicon-o-presentation-chart-line style="width: 32px; height: 32px;" />
            </div>
            <h3 class="lp-report-title">Laporan Bulanan</h3>
            <p class="lp-report-desc">
                "Rekapitulasi total dalam 30 hari terakhir untuk laporan keuangan bulanan perusahaan."
            </p>
            <a href="{{ route('admin.laporan', ['type' => 'bulan']) }}" target="_blank" 
               class="lp-btn bg-stone-900 dark:bg-green-600 hover:bg-green-600 dark:hover:bg-green-700">
                PRINT MONTHLY REPORT 
                <x-heroicon-o-printer style="width: 16px; height: 16px; margin-left: 4px;" />
            </a>
        </div>
    </div>
</x-filament-panels::page>
