<x-filament-panels::page>
    {{-- Header Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Revenue Stat -->
        <div class="relative overflow-hidden p-8 bg-gray-900 rounded-3xl border border-gray-800 shadow-[0_10px_15px_-3px_rgba(0,0,0,0.3)]">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-orange-600/20 blur-[50px] rounded-full"></div>
            <div class="relative z-10">
                <p class="text-[0.65rem] font-extrabold uppercase tracking-widest mb-3 text-orange-500">Total Revenue Today</p>
                @php
                    $todayIncome = \App\Models\Pesanan::where('status', 'dibayar')
                        ->whereDate('updated_at', today())
                        ->sum('total_harga');
                @endphp
                <h4 class="text-3xl font-black text-white italic tracking-tighter">Rp {{ number_format($todayIncome, 0, ',', '.') }}</h4>
            </div>
        </div>
        
        <!-- Verification Stat -->
        <div class="relative overflow-hidden p-8 bg-gray-900 rounded-3xl border border-gray-800 shadow-[0_10px_15px_-3px_rgba(0,0,0,0.3)]">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-blue-600/10 blur-[50px] rounded-full"></div>
            <div class="relative z-10">
                <p class="text-[0.65rem] font-extrabold uppercase tracking-widest mb-3 text-blue-400">Orders to Verify</p>
                @php
                    $pendingCount = \App\Models\Pesanan::where('status', 'menunggu_verifikasi')->count();
                @endphp
                <h4 class="text-3xl font-black text-white italic tracking-tighter">{{ $pendingCount }} <span class="text-xs not-italic text-gray-500 ml-1">Orders</span></h4>
            </div>
        </div>

        <!-- Completion Stat -->
        <div class="relative overflow-hidden p-8 bg-gray-900 rounded-3xl border border-gray-800 shadow-[0_10px_15px_-3px_rgba(0,0,0,0.3)]">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-green-600/10 blur-[50px] rounded-full"></div>
            <div class="relative z-10">
                <p class="text-[0.65rem] font-extrabold uppercase tracking-widest mb-3 text-green-400">Completed This Month</p>
                @php
                    $monthCount = \App\Models\Pesanan::where('status', 'selesai')
                        ->whereMonth('updated_at', now()->month)
                        ->count();
                @endphp
                <h4 class="text-3xl font-black text-white italic tracking-tighter">{{ $monthCount }} <span class="text-xs not-italic text-gray-500 ml-1">Orders</span></h4>
            </div>
        </div>
    </div>

    {{-- Main Custom Dashboard Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        {{-- Daily Report Card --}}
        <div class="bg-white dark:bg-zinc-900 p-10 rounded-3xl border border-gray-100 dark:border-zinc-800 shadow-[0_4px_6px_-1px_rgba(0,0,0,0.05)] dark:shadow-[0_4px_6px_-1px_rgba(0,0,0,0.2)] transition-all duration-[400ms] ease-[cubic-bezier(0.4,0,0.2,1)] relative flex flex-col hover:-translate-y-1.5 hover:shadow-[0_20px_25px_-5px_rgba(0,0,0,0.08)] dark:hover:shadow-[0_20px_25px_-5px_rgba(0,0,0,0.4)] group">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-6 transition-transform duration-300 group-hover:scale-105 bg-orange-50 dark:bg-orange-600/10 text-orange-600">
                <x-heroicon-o-calendar-days style="width: 32px; height: 32px;" />
            </div>
            <h3 class="text-[1.35rem] font-extrabold text-zinc-900 dark:text-white mb-3 tracking-tight">Laporan Harian</h3>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-8 leading-relaxed italic font-medium flex-grow">
                "Ringkasan transaksi lengkap yang terjadi pada hari ini untuk monitoring harian."
            </p>
            <a href="{{ route('admin.laporan', ['type' => 'hari']) }}" target="_blank" 
               class="inline-flex items-center justify-center gap-2 w-full py-[1.15rem] text-white rounded-2xl font-extrabold text-xs tracking-widest uppercase transition-all duration-300 cursor-pointer shadow-[0_4px_6px_-1px_rgba(0,0,0,0.1)] hover:opacity-90 hover:-translate-y-0.5 active:translate-y-0.5 bg-stone-900 dark:bg-orange-600 hover:bg-orange-600 dark:hover:bg-orange-700">
                PRINT DAILY REPORT 
                <x-heroicon-o-printer style="width: 16px; height: 16px; margin-left: 4px;" />
            </a>
        </div>

        {{-- Weekly Report Card --}}
        <div class="bg-white dark:bg-zinc-900 p-10 rounded-3xl border border-gray-100 dark:border-zinc-800 shadow-[0_4px_6px_-1px_rgba(0,0,0,0.05)] dark:shadow-[0_4px_6px_-1px_rgba(0,0,0,0.2)] transition-all duration-[400ms] ease-[cubic-bezier(0.4,0,0.2,1)] relative flex flex-col hover:-translate-y-1.5 hover:shadow-[0_20px_25px_-5px_rgba(0,0,0,0.08)] dark:hover:shadow-[0_20px_25px_-5px_rgba(0,0,0,0.4)] group">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-6 transition-transform duration-300 group-hover:scale-105 bg-blue-50 dark:bg-blue-600/10 text-blue-600">
                <x-heroicon-o-chart-bar style="width: 32px; height: 32px;" />
            </div>
            <h3 class="text-[1.35rem] font-extrabold text-zinc-900 dark:text-white mb-3 tracking-tight">Laporan Mingguan</h3>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-8 leading-relaxed italic font-medium flex-grow">
                "Analisis performa penjualan dalam periode 7 hari terakhir untuk evaluasi mingguan."
            </p>
            <a href="{{ route('admin.laporan', ['type' => 'minggu']) }}" target="_blank" 
               class="inline-flex items-center justify-center gap-2 w-full py-[1.15rem] text-white rounded-2xl font-extrabold text-xs tracking-widest uppercase transition-all duration-300 cursor-pointer shadow-[0_4px_6px_-1px_rgba(0,0,0,0.1)] hover:opacity-90 hover:-translate-y-0.5 active:translate-y-0.5 bg-stone-900 dark:bg-blue-600 hover:bg-blue-600 dark:hover:bg-blue-700">
                PRINT WEEKLY REPORT 
                <x-heroicon-o-printer style="width: 16px; height: 16px; margin-left: 4px;" />
            </a>
        </div>

        {{-- Monthly Report Card --}}
        <div class="bg-white dark:bg-zinc-900 p-10 rounded-3xl border border-gray-100 dark:border-zinc-800 shadow-[0_4px_6px_-1px_rgba(0,0,0,0.05)] dark:shadow-[0_4px_6px_-1px_rgba(0,0,0,0.2)] transition-all duration-[400ms] ease-[cubic-bezier(0.4,0,0.2,1)] relative flex flex-col hover:-translate-y-1.5 hover:shadow-[0_20px_25px_-5px_rgba(0,0,0,0.08)] dark:hover:shadow-[0_20px_25px_-5px_rgba(0,0,0,0.4)] group">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-6 transition-transform duration-300 group-hover:scale-105 bg-green-50 dark:bg-green-600/10 text-green-600">
                <x-heroicon-o-presentation-chart-line style="width: 32px; height: 32px;" />
            </div>
            <h3 class="text-[1.35rem] font-extrabold text-zinc-900 dark:text-white mb-3 tracking-tight">Laporan Bulanan</h3>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-8 leading-relaxed italic font-medium flex-grow">
                "Rekapitulasi total dalam 30 hari terakhir untuk laporan keuangan bulanan perusahaan."
            </p>
            <a href="{{ route('admin.laporan', ['type' => 'bulan']) }}" target="_blank" 
               class="inline-flex items-center justify-center gap-2 w-full py-[1.15rem] text-white rounded-2xl font-extrabold text-xs tracking-widest uppercase transition-all duration-300 cursor-pointer shadow-[0_4px_6px_-1px_rgba(0,0,0,0.1)] hover:opacity-90 hover:-translate-y-0.5 active:translate-y-0.5 bg-stone-900 dark:bg-green-600 hover:bg-green-600 dark:hover:bg-green-700">
                PRINT MONTHLY REPORT 
                <x-heroicon-o-printer style="width: 16px; height: 16px; margin-left: 4px;" />
            </a>
        </div>
    </div>
</x-filament-panels::page>
