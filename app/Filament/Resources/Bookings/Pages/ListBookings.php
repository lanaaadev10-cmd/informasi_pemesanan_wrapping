<?php

namespace App\Filament\Resources\Bookings\Pages;

use App\Filament\Resources\Bookings\BookingResource;
use App\Enums\BookingStatus;
use App\Models\Booking;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBookings extends ListRecords
{
    protected static string $resource = BookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('viewQuota')
                ->label('Kuota Hari Ini')
                ->icon('heroicon-o-chart-pie')
                ->color('info')
                ->modalHeading('Status Kuota Hari Ini')
                ->modalDescription(function () {
                    $q = app(\App\Services\SlotKuotaService::class)->getTodayQuota();
                    return "Tanggal " . now()->translatedFormat('d F Y') . ": Terisi {$q['total_used']}/5 slot (Booking: {$q['booked_count']}, Pesanan: {$q['pesanan_count']}). Sisa kuota tersedia: {$q['available']} slot.";
                })
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Tutup'),

            \Filament\Actions\Action::make('exportCsv')
                ->label('Ekspor CSV / Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(function () {
                    return response()->streamDownload(function () {
                        $handle = fopen('php://output', 'w');
                        fputs($handle, "\xEF\xBB\xBF");
                        fputcsv($handle, ['Kode Booking', 'Nama Pelanggan', 'WhatsApp', 'Email', 'Layanan', 'Tanggal Booking', 'Jam', 'Tipe Bayar', 'Status', 'Catatan', 'Dibuat']);

                        Booking::with('layanan', 'user')->orderBy('created_at', 'desc')->chunk(100, function ($bookings) use ($handle) {
                            foreach ($bookings as $b) {
                                fputcsv($handle, [
                                    $b->booking_code,
                                    $b->pelanggan_nama,
                                    $b->pelanggan_phone,
                                    $b->pelanggan_email,
                                    $b->layanan?->nama_layanan ?? '-',
                                    $b->booking_date ? $b->booking_date->format('Y-m-d') : '-',
                                    $b->booking_time ?: '-',
                                    $b->payment_type,
                                    $b->label_status,
                                    $b->notes ?: '-',
                                    $b->created_at ? $b->created_at->format('Y-m-d H:i:s') : '-',
                                ]);
                            }
                        });

                        fclose($handle);
                    }, 'booking-report-' . date('Ymd-His') . '.csv', [
                        'Content-Type' => 'text/csv; charset=UTF-8',
                    ]);
                }),

            CreateAction::make()->label('Tambah Booking Manual'),
        ];
    }

    public function getTabs(): array
    {
        // Satu query agregat menggantikan 5 query terpisah per tab.
        $counts = Booking::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->all();

        $sum = fn (array $statuses): int => collect($statuses)->sum(
            fn (string $status): int => (int) ($counts[$status] ?? 0)
        );

        return [
            'all' => \Filament\Schemas\Components\Tabs\Tab::make('Semua Booking'),
            'pending' => \Filament\Schemas\Components\Tabs\Tab::make('Menunggu Konfirmasi')
                ->modifyQueryUsing(fn ($query) => $query->where('status', BookingStatus::PENDING->value))
                ->badge($counts[BookingStatus::PENDING->value] ?? 0)
                ->badgeColor('warning'),
            'payment' => \Filament\Schemas\Components\Tabs\Tab::make('Verifikasi Pembayaran')
                ->modifyQueryUsing(fn ($query) => $query->where('status', BookingStatus::PAYMENT_UPLOADED->value))
                ->badge($counts[BookingStatus::PAYMENT_UPLOADED->value] ?? 0)
                ->badgeColor('danger'),
            'proses' => \Filament\Schemas\Components\Tabs\Tab::make('Sedang Berjalan')
                ->modifyQueryUsing(fn ($query) => $query->whereIn('status', [
                    BookingStatus::AWAITING_PAYMENT->value,
                    BookingStatus::APPROVED->value,
                    BookingStatus::IN_PROGRESS->value,
                ]))
                ->badge($sum([
                    BookingStatus::AWAITING_PAYMENT->value,
                    BookingStatus::APPROVED->value,
                    BookingStatus::IN_PROGRESS->value,
                ]))
                ->badgeColor('info'),
            'selesai' => \Filament\Schemas\Components\Tabs\Tab::make('Selesai')
                ->modifyQueryUsing(fn ($query) => $query->where('status', BookingStatus::COMPLETED->value))
                ->badge($counts[BookingStatus::COMPLETED->value] ?? 0),
            'batal' => \Filament\Schemas\Components\Tabs\Tab::make('Ditolak/Dibatalkan')
                ->modifyQueryUsing(fn ($query) => $query->whereIn('status', [
                    BookingStatus::REJECTED->value,
                    BookingStatus::CANCELLED->value,
                ]))
                ->badge($sum([
                    BookingStatus::REJECTED->value,
                    BookingStatus::CANCELLED->value,
                ]))
                ->badgeColor('danger'),
        ];
    }
}