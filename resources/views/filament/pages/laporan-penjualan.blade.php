<x-filament-panels::page>
    {{-- Scoped CSS Stylesheets extracted for clean maintainability (<300 lines rule) --}}
    @include('filament.pages.partials._laporan-penjualan-styles')
    @include('filament.pages.partials._laporan-preview-styles')

    <div class="f-hub-container">

        {{-- ==================== 1. FILTER TOOLBAR ==================== --}}
        <div class="f-filter-toolbar">
            {{-- Quick Period Pills --}}
            <div class="f-pill-group">
                <span class="f-toolbar-label">Periode:</span>
                <button type="button" 
                        wire:click="setPeriode('hari_ini')"
                        class="f-pill-btn {{ $periode === 'hari_ini' ? 'active' : '' }}">
                    Hari Ini
                </button>
                <button type="button" 
                        wire:click="setPeriode('7_hari')"
                        class="f-pill-btn {{ $periode === '7_hari' ? 'active' : '' }}">
                    7 Hari Terakhir
                </button>
                <button type="button" 
                        wire:click="setPeriode('bulan_ini')"
                        class="f-pill-btn {{ $periode === 'bulan_ini' ? 'active' : '' }}">
                    Bulan Ini
                </button>
                <button type="button" 
                        wire:click="setPeriode('tahun_ini')"
                        class="f-pill-btn {{ $periode === 'tahun_ini' ? 'active' : '' }}">
                    Tahun Ini
                </button>
            </div>

            {{-- Date Range Picker & Status Filter (Clean Inline Flex) --}}
            <div class="f-filter-controls">
                <div class="f-date-picker-wrap">
                    <span class="f-toolbar-label">Rentang:</span>
                    <input type="date" 
                           wire:model.live="startDate" 
                           class="f-input-control" 
                           title="Dari Tanggal">
                    <span style="color: #9ca3af; font-size: 0.75rem;">s/d</span>
                    <input type="date" 
                           wire:model.live="endDate" 
                           class="f-input-control" 
                           title="Sampai Tanggal">
                </div>

                {{-- Status Filter Dropdown --}}
                <select wire:model.live="statusFilter" class="f-input-control" style="font-weight: 600;">
                    <option value="semua">Semua Status</option>
                    <option value="selesai">Hanya Selesai</option>
                    <option value="proses">Sedang Diproses</option>
                    <option value="verifikasi">Butuh Verifikasi</option>
                </select>
            </div>
        </div>

        {{-- ==================== 2. KPI STAT CARDS (3 COLUMNS - CLEAN & INTERACTIVE) ==================== --}}
        <div class="f-kpi-grid">
            {{-- Total Pendapatan Card --}}
            <div class="f-kpi-card" 
                 wire:click="$set('statusFilter', 'selesai')" 
                 title="Klik untuk memfilter transaksi yang sudah selesai"
                 style="cursor: pointer;">
                <div class="f-kpi-card-header">
                    <span class="f-kpi-card-title text-gray-600 dark:text-gray-400">Total Pendapatan</span>
                    <div class="f-kpi-icon-badge bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-300">
                        <x-heroicon-o-banknotes />
                    </div>
                </div>
                <div>
                    <h3 class="f-kpi-val text-gray-900 dark:text-white">
                        Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                    </h3>
                    <p class="f-kpi-subtext">
                        Dari <strong class="text-gray-900 dark:text-gray-100">{{ $totalSelesai }}</strong> pesanan tuntas & terverifikasi
                    </p>
                </div>
            </div>

            {{-- Butuh Verifikasi Card --}}
            <div class="f-kpi-card" 
                 wire:click="$set('statusFilter', 'verifikasi')" 
                 title="Klik untuk memfilter transaksi yang butuh verifikasi admin"
                 style="cursor: pointer;">
                <div class="f-kpi-card-header">
                    <span class="f-kpi-card-title text-gray-600 dark:text-gray-400">Butuh Verifikasi</span>
                    <div class="f-kpi-icon-badge bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-300">
                        <x-heroicon-o-clock />
                    </div>
                </div>
                <div>
                    <h3 class="f-kpi-val text-gray-900 dark:text-white">
                        {{ $butuhVerifikasi }} <span style="font-size: 0.85rem; font-weight: 600; color: #6b7280;">Orders</span>
                    </h3>
                    <p class="f-kpi-subtext">
                        Menunggu persetujuan admin & validasi bukti
                    </p>
                </div>
            </div>

            {{-- Total Pesanan Selesai & Rata-rata --}}
            <div class="f-kpi-card" 
                 wire:click="$set('statusFilter', 'semua')" 
                 title="Klik untuk menampilkan semua transaksi"
                 style="cursor: pointer;">
                <div class="f-kpi-card-header">
                    <span class="f-kpi-card-title text-gray-600 dark:text-gray-400">Total Pesanan Periode Ini</span>
                    <div class="f-kpi-icon-badge bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-300">
                        <x-heroicon-o-check-circle />
                    </div>
                </div>
                <div>
                    <h3 class="f-kpi-val text-gray-900 dark:text-white">
                        {{ $totalOrdersPeriode }} <span style="font-size: 0.85rem; font-weight: 600; color: #6b7280;">Total</span>
                    </h3>
                    <p class="f-kpi-subtext">
                        Rata-rata: <strong class="text-gray-900 dark:text-gray-100">Rp {{ number_format($rataRataTransaksi, 0, ',', '.') }}</strong> / order
                    </p>
                </div>
            </div>
        </div>

        {{-- ==================== 3. MAIN SPLIT SECTION (7/12 & 5/12) ==================== --}}
        <div class="f-main-split">
            {{-- SISI KIRI (7/12): TABEL PREVIEW TRANSAKSI --}}
            @include('filament.pages.partials._laporan-table')

            {{-- SISI KANAN (5/12): PUSAT CETAK & DOKUMEN LAPORAN --}}
            @include('filament.pages.partials._laporan-print-center')
        </div>

    </div>
</x-filament-panels::page>