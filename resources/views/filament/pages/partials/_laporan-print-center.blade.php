{{-- SISI KANAN (5/12): PUSAT CETAK & DOKUMEN LAPORAN --}}
<div class="f-card-panel">
    <div class="f-section-header">
        <div class="f-section-header-left">
            <div class="f-section-icon-box">
                <x-heroicon-o-printer />
            </div>
            <div>
                <h4 class="f-section-title">Pusat Cetak Dokumen</h4>
                <p class="f-section-desc">
                    Format PDF resmi siap cetak standar A4.
                </p>
            </div>
        </div>
    </div>

    <div class="f-print-list">
        {{-- Modul 1: Cetak Harian --}}
        <div class="f-print-item">
            <div class="f-print-item-left">
                <div class="f-print-icon-box bg-orange-100 text-orange-600 dark:bg-orange-950/50 dark:text-orange-400">
                    <x-heroicon-o-calendar />
                </div>
                <div>
                    <h5 class="f-print-item-title">Laporan Harian</h5>
                    <p class="f-print-item-sub">
                        {{ \Illuminate\Support\Carbon::today()->isoFormat('D MMMM Y') }}
                    </p>
                </div>
            </div>
            <a href="{{ route('admin.laporan', ['type' => 'hari']) }}" target="_blank" class="f-btn-print">
                <x-heroicon-o-printer />
                Cetak PDF
            </a>
        </div>

        {{-- Modul 2: Cetak Mingguan --}}
        <div class="f-print-item">
            <div class="f-print-item-left">
                <div class="f-print-icon-box bg-blue-100 text-blue-600 dark:bg-blue-950/50 dark:text-blue-400">
                    <x-heroicon-o-chart-bar />
                </div>
                <div>
                    <h5 class="f-print-item-title">Laporan Mingguan</h5>
                    <p class="f-print-item-sub">
                        {{ \Illuminate\Support\Carbon::now()->startOfWeek()->isoFormat('D MMM') }} - {{ \Illuminate\Support\Carbon::now()->endOfWeek()->isoFormat('D MMM Y') }}
                    </p>
                </div>
            </div>
            <a href="{{ route('admin.laporan', ['type' => 'minggu']) }}" target="_blank" class="f-btn-print">
                <x-heroicon-o-printer />
                Cetak PDF
            </a>
        </div>

        {{-- Modul 3: Cetak Bulanan --}}
        <div class="f-print-item">
            <div class="f-print-item-left">
                <div class="f-print-icon-box bg-emerald-100 text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-400">
                    <x-heroicon-o-presentation-chart-line />
                </div>
                <div>
                    <h5 class="f-print-item-title">Laporan Bulanan</h5>
                    <p class="f-print-item-sub">
                        {{ \Illuminate\Support\Carbon::now()->isoFormat('MMMM Y') }}
                    </p>
                </div>
            </div>
            <a href="{{ route('admin.laporan', ['type' => 'bulan']) }}" target="_blank" class="f-btn-print">
                <x-heroicon-o-printer />
                Cetak PDF
            </a>
        </div>
    </div>
</div>
