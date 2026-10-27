<?php

namespace App\Filament\Resources\Bookings\Tables;

use App\Enums\BookingStatus;
use App\Enums\PaymentType;
use App\Filament\Resources\Bookings\BookingResource;
use App\Models\Reservasi;
use App\Services\BookingService;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BookingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('booking_code')
                    ->label('Kode Booking')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable(),

                TextColumn::make('pelanggan_nama')
                    ->label('Pelanggan')
                    ->searchable(query: function ($query, string $search) {
                        $query->where('customer_name', 'like', "%{$search}%")
                              ->orWhereHas('user', fn ($q) => $q->where('name', 'like', "%{$search}%"));
                    })
                    ->sortable(),

                TextColumn::make('pelanggan_phone')
                    ->label('WhatsApp')
                    ->searchable(query: function ($query, string $search) {
                        $query->where('customer_phone', 'like', "%{$search}%")
                              ->orWhereHas('user', fn ($q) => $q->where('phone', 'like', "%{$search}%")->orWhere('no_hp', 'like', "%{$search}%"));
                    })
                    ->copyable(),

                TextColumn::make('service_name_snapshot')
                    ->label('Layanan / Proyek')
                    ->state(fn (Reservasi $record) => $record->service_name_snapshot ?: ($record->layanan?->nama_layanan ?? 'Proyek Custom'))
                    ->searchable(query: function ($query, string $search) {
                        $query->where('service_name_snapshot', 'like', "%{$search}%")
                              ->orWhereHas('layanan', fn ($q) => $q->where('nama_layanan', 'like', "%{$search}%"));
                    })
                    ->sortable()
                    ->limit(25),

                TextColumn::make('booking_date')
                    ->label('Jadwal')
                    ->formatStateUsing(fn ($state, Reservasi $record) => ($record->booking_date ? $record->booking_date->format('d M Y') : '-') . ($record->booking_time ? ' • ' . $record->booking_time : ''))
                    ->sortable()
                    ->badge()
                    ->color(fn (Reservasi $record): string => $record->booking_date && $record->booking_date->isPast() ? 'gray' : 'info'),

                TextColumn::make('payment_type')
                    ->label('Tipe Bayar')
                    ->badge()
                    ->formatStateUsing(function ($state): string {
                        if ($state instanceof PaymentType) {
                            return $state->label();
                        }
                        if (is_string($state) && $state !== '') {
                            return PaymentType::tryFrom($state)?->label() ?? $state;
                        }
                        return '-';
                    })
                    ->color(function ($state): string {
                        $val = $state instanceof PaymentType ? $state->value : $state;
                        return $val === PaymentType::DP->value ? 'warning' : 'success';
                    }),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(function ($state): string {
                        $status = $state instanceof BookingStatus ? $state : ($state ? BookingStatus::tryFrom((string) $state) : null);
                        return $status?->label() ?? (is_string($state) && $state !== '' ? $state : '-');
                    })
                    ->color(function ($state): string {
                        $status = $state instanceof BookingStatus ? $state : ($state ? BookingStatus::tryFrom((string) $state) : null);
                        return $status?->badgeColor() ?? 'gray';
                    })
                    ->icon(function ($state): string {
                        $status = $state instanceof BookingStatus ? $state : ($state ? BookingStatus::tryFrom((string) $state) : null);
                        return $status?->icon() ?? 'heroicon-m-question-mark-circle';
                    }),

                ImageColumn::make('payment.proof_file')
                    ->label('Bukti Bayar')
                    ->square()
                    ->disk('public')
                    ->visibility('public')
                    ->defaultImageUrl(null),

                TextColumn::make('source')
                    ->label('Sumber')
                    ->badge()
                    ->color(fn ($state) => $state === 'offline' ? 'warning' : 'info')
                    ->formatStateUsing(fn ($state) => $state === 'offline' ? 'Walk-In' : 'Online'),

                TextColumn::make('vehicle_name')
                    ->label('Kendaraan')
                    ->description(fn (Reservasi $record) => $record->vehicle_size ? strtoupper($record->vehicle_size) : null)
                    ->searchable(),

                TextColumn::make('material_status')
                    ->label('Bahan Customer')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default    => 'warning',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'approved' => 'Layak',
                        'rejected' => 'Ditolak',
                        default    => 'Perlu Cek',
                    }),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(collect(BookingStatus::cases())
                        ->mapWithKeys(fn ($case) => [$case->value => $case->label()])
                        ->toArray()),

                Filter::make('booking_date')
                    ->label('Tanggal Pengerjaan')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('booking_date'),
                    ])
                    ->query(function (\Illuminate\Database\Eloquent\Builder $query, array $data): \Illuminate\Database\Eloquent\Builder {
                        return $query
                            ->when(
                                $data['booking_date'] ?? null,
                                fn (\Illuminate\Database\Eloquent\Builder $q, string $date) => $q->whereDate('booking_date', $date)
                            );
                    }),
            ])
            ->actions(BookingTableActions::all())
            ->bulkActions([]);
    }
}