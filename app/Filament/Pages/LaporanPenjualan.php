<?php

namespace App\Filament\Pages;

use App\Models\Booking;
use App\Models\BookingPayment;
use App\Models\Pesanan;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Illuminate\Support\Carbon;

class LaporanPenjualan extends Page
{
    protected static ?string $navigationLabel = 'Laporan Penjualan';
    protected static ?string $title = 'Laporan Penjualan';
    protected static \UnitEnum|string|null $navigationGroup = 'Transaksi';
    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?int $navigationSort = 5;

    protected static ?string $slug = 'laporan-penjualan';

    protected string $view = 'filament.pages.laporan-penjualan';

    // Interactive Livewire State
    public string $periode = 'bulan_ini'; // 'hari_ini', '7_hari', 'bulan_ini', 'tahun_ini', 'custom'
    public ?string $startDate = null;
    public ?string $endDate = null;
    public string $statusFilter = 'semua'; // 'semua', 'selesai', 'proses', 'verifikasi'

    public function mount(): void
    {
        $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->endDate = Carbon::now()->format('Y-m-d');
    }

    public function setPeriode(string $periode): void
    {
        $this->periode = $periode;

        if ($periode === 'hari_ini') {
            $this->startDate = Carbon::today()->format('Y-m-d');
            $this->endDate = Carbon::today()->format('Y-m-d');
        } elseif ($periode === '7_hari') {
            $this->startDate = Carbon::now()->subDays(6)->format('Y-m-d');
            $this->endDate = Carbon::now()->format('Y-m-d');
        } elseif ($periode === 'bulan_ini') {
            $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
            $this->endDate = Carbon::now()->format('Y-m-d');
        } elseif ($periode === 'tahun_ini') {
            $this->startDate = Carbon::now()->startOfYear()->format('Y-m-d');
            $this->endDate = Carbon::now()->format('Y-m-d');
        }
    }

    public function getHeaderActions(): array
    {
        return [
            Action::make('cetakAktif')
                ->label('Cetak PDF Sesuai Filter')
                ->icon('heroicon-o-printer')
                ->color('primary')
                ->url(fn () => route('admin.laporan', [
                    'type' => 'custom',
                    'start_date' => $this->startDate ?: Carbon::now()->startOfMonth()->format('Y-m-d'),
                    'end_date' => $this->endDate ?: Carbon::now()->format('Y-m-d'),
                ]))
                ->openUrlInNewTab(),
        ];
    }

    protected function getViewData(): array
    {
        $start = $this->startDate ? Carbon::parse($this->startDate)->startOfDay() : Carbon::now()->startOfMonth();
        $end = $this->endDate ? Carbon::parse($this->endDate)->endOfDay() : Carbon::now()->endOfDay();

        // 1. Query Pesanan Selesai / Terkonfirmasi pada rentang waktu
        $completedPesananQuery = Pesanan::whereIn('status', [
            Pesanan::STATUS_DIKONFIRMASI,
            Pesanan::STATUS_SEDANG_DIPROSES,
            Pesanan::STATUS_SELESAI,
            'selesai',
        ])->whereBetween('created_at', [$start, $end]);

        $pesananRevenue = (float) (clone $completedPesananQuery)->sum('total_harga');

        // Revenue dari Booking Payment yang terverifikasi pada rentang waktu
        $bookingRevenue = (float) BookingPayment::whereIn('status', ['verified', 'approved'])
            ->whereBetween('created_at', [$start, $end])
            ->sum('amount');

        $totalPendapatan = $pesananRevenue + $bookingRevenue;

        // 2. Total Pesanan Selesai
        $totalSelesai = (clone $completedPesananQuery)->count();

        // 3. Pesanan yang butuh verifikasi admin (global realtime)
        $butuhVerifikasi = Pesanan::whereIn('status', [
            Pesanan::STATUS_MENUNGGU_KONFIRMASI_ADMIN,
            Pesanan::STATUS_MENUNGGU_VERIFIKASI_PEMBAYARAN,
        ])->count() + Booking::whereIn('status', [
            \App\Enums\BookingStatus::PENDING->value,
            \App\Enums\BookingStatus::PAYMENT_UPLOADED->value,
        ])->count();

        // 4. Total semua order dalam periode
        $totalOrdersPeriode = Pesanan::whereBetween('created_at', [$start, $end])->count();

        // 5. Rata-rata nilai transaksi
        $rataRataTransaksi = $totalSelesai > 0 ? ($totalPendapatan / $totalSelesai) : 0;

        // 6. Preview Transaksi Terkini
        $previewQuery = Pesanan::with(['user', 'details.layanan', 'form'])
            ->whereBetween('created_at', [$start, $end]);

        if ($this->statusFilter !== 'semua') {
            if ($this->statusFilter === 'selesai') {
                $previewQuery->whereIn('status', [Pesanan::STATUS_SELESAI, 'selesai']);
            } elseif ($this->statusFilter === 'proses') {
                $previewQuery->whereIn('status', [Pesanan::STATUS_DIKONFIRMASI, Pesanan::STATUS_SEDANG_DIPROSES]);
            } elseif ($this->statusFilter === 'verifikasi') {
                $previewQuery->whereIn('status', [
                    Pesanan::STATUS_MENUNGGU_KONFIRMASI_ADMIN,
                    Pesanan::STATUS_MENUNGGU_VERIFIKASI_PEMBAYARAN,
                ]);
            }
        }

        $previewPesanan = $previewQuery->latest('created_at')->limit(8)->get();

        return [
            'totalPendapatan' => $totalPendapatan,
            'totalSelesai' => $totalSelesai,
            'butuhVerifikasi' => $butuhVerifikasi,
            'totalOrdersPeriode' => $totalOrdersPeriode,
            'rataRataTransaksi' => $rataRataTransaksi,
            'previewPesanan' => $previewPesanan,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'periode' => $this->periode,
            'statusFilter' => $this->statusFilter,
        ];
    }
}