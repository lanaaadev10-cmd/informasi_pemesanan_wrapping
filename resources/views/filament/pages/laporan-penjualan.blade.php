<x-filament-panels::page>
    {{-- Header Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="relative overflow-hidden p-8 bg-gray-900 rounded-[2rem] border border-gray-800 shadow-2xl">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-orange-600/20 blur-[50px] rounded-full"></div>
            <div class="relative z-10">
                <p class="text-[10px] font-black text-orange-500 uppercase tracking-[0.3em] mb-4">Total Revenue Today</p>
                @php
                    $todayIncome = \App\Models\Pesanan::where('status', 'dibayar')
                        ->whereDate('updated_at', today())
                        ->sum('total_harga');
                @endphp
                <h4 class="text-3xl font-black text-white italic tracking-tighter">Rp {{ number_format($todayIncome, 0, ',', '.') }}</h4>
            </div>
        </div>
        <div class="relative overflow-hidden p-8 bg-gray-900 rounded-[2rem] border border-gray-800 shadow-2xl">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-blue-600/10 blur-[50px] rounded-full"></div>
            <div class="relative z-10">
                <p class="text-[10px] font-black text-blue-400 uppercase tracking-[0.3em] mb-4">Orders to Verify</p>
                @php
                    $pendingCount = \App\Models\Pesanan::where('status', 'menunggu_verifikasi')->count();
                @endphp
                <h4 class="text-3xl font-black text-white italic tracking-tighter">{{ $pendingCount }} <span class="text-xs not-italic text-gray-500 ml-1">Orders</span></h4>
            </div>
        </div>
        <div class="relative overflow-hidden p-8 bg-gray-900 rounded-[2rem] border border-gray-800 shadow-2xl">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-green-600/10 blur-[50px] rounded-full"></div>
            <div class="relative z-10">
                <p class="text-[10px] font-black text-green-400 uppercase tracking-[0.3em] mb-4">Completed This Month</p>
                @php
                    $monthCount = \App\Models\Pesanan::where('status', 'selesai')
                        ->whereMonth('updated_at', now()->month)
                        ->count();
                @endphp
                <h4 class="text-3xl font-black text-white italic tracking-tighter">{{ $monthCount }} <span class="text-xs not-italic text-gray-500 ml-1">Orders</span></h4>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        {{-- Daily Report --}}
        <div class="group relative bg-white dark:bg-gray-900 p-10 rounded-[2.5rem] border border-gray-100 dark:border-gray-800 transition-all duration-500 hover:shadow-2xl hover:-translate-y-2">
            <div class="w-16 h-16 bg-orange-50 dark:bg-orange-600/10 rounded-2xl flex items-center justify-center text-orange-600 mb-8 group-hover:scale-110 transition-transform">
                <x-heroicon-o-calendar-days class="w-8 h-8" />
            </div>
            <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-4 tracking-tight">Laporan Harian</h3>
            <p class="text-sm text-gray-400 dark:text-gray-500 mb-10 leading-relaxed font-medium italic">
                "Ringkasan transaksi lengkap yang terjadi pada hari ini untuk monitoring harian."
            </p>
            <a href="{{ route('admin.laporan', ['type' => 'hari']) }}" target="_blank" 
               class="flex items-center justify-center gap-3 w-full py-5 bg-gray-900 dark:bg-orange-600 text-white rounded-2xl font-black text-[11px] tracking-widest uppercase hover:bg-orange-600 transition-all shadow-xl shadow-orange-900/10">
                PRINT DAILY REPORT <x-heroicon-o-printer class="w-4 h-4" />
            </a>
        </div>

        {{-- Weekly Report --}}
        <div class="group relative bg-white dark:bg-gray-900 p-10 rounded-[2.5rem] border border-gray-100 dark:border-gray-800 transition-all duration-500 hover:shadow-2xl hover:-translate-y-2">
            <div class="w-16 h-16 bg-blue-50 dark:bg-blue-600/10 rounded-2xl flex items-center justify-center text-blue-600 mb-8 group-hover:scale-110 transition-transform">
                <x-heroicon-o-chart-bar class="w-8 h-8" />
            </div>
            <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-4 tracking-tight">Laporan Mingguan</h3>
            <p class="text-sm text-gray-400 dark:text-gray-500 mb-10 leading-relaxed font-medium italic">
                "Analisis performa penjualan dalam periode 7 hari terakhir untuk evaluasi mingguan."
            </p>
            <a href="{{ route('admin.laporan', ['type' => 'minggu']) }}" target="_blank" 
               class="flex items-center justify-center gap-3 w-full py-5 bg-gray-900 dark:bg-blue-600 text-white rounded-2xl font-black text-[11px] tracking-widest uppercase hover:bg-blue-600 transition-all shadow-xl shadow-blue-900/10">
                PRINT WEEKLY REPORT <x-heroicon-o-printer class="w-4 h-4" />
            </a>
        </div>

        {{-- Monthly Report --}}
        <div class="group relative bg-white dark:bg-gray-900 p-10 rounded-[2.5rem] border border-gray-100 dark:border-gray-800 transition-all duration-500 hover:shadow-2xl hover:-translate-y-2">
            <div class="w-16 h-16 bg-green-50 dark:bg-green-600/10 rounded-2xl flex items-center justify-center text-green-600 mb-8 group-hover:scale-110 transition-transform">
                <x-heroicon-o-presentation-chart-line class="w-8 h-8" />
            </div>
            <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-4 tracking-tight">Laporan Bulanan</h3>
            <p class="text-sm text-gray-400 dark:text-gray-500 mb-10 leading-relaxed font-medium italic">
                "Rekapitulasi total dalam 30 hari terakhir untuk laporan keuangan bulanan perusahaan."
            </p>
            <a href="{{ route('admin.laporan', ['type' => 'bulan']) }}" target="_blank" 
               class="flex items-center justify-center gap-3 w-full py-5 bg-gray-900 dark:bg-green-600 text-white rounded-2xl font-black text-[11px] tracking-widest uppercase hover:bg-green-600 transition-all shadow-xl shadow-green-900/10">
                PRINT MONTHLY REPORT <x-heroicon-o-printer class="w-4 h-4" />
            </a>
        </div>
    </div>
</x-filament-panels::page>
