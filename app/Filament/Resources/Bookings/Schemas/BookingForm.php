<?php

namespace App\Filament\Resources\Bookings\Schemas;

use App\Enums\BookingStatus;
use App\Enums\PaymentType;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BookingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->columns(['default' => 3])
            ->schema([
                Section::make('Informasi Booking')
                    ->schema([
                        TextInput::make('booking_code')
                            ->label('Kode Booking')
                            ->disabled()
                            ->dehydrated()
                            ->default(fn () => 'BKG-' . date('YmdHis') . '-' . strtoupper(\Illuminate\Support\Str::random(6))),

                        Select::make('user_id')
                            ->label('Pelanggan')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('layanan_id')
                            ->label('Paket Layanan')
                            ->relationship('layanan', 'nama_layanan')
                            ->searchable()
                            ->preload()
                            ->required(),

                        DatePicker::make('booking_date')
                            ->label('Tanggal Pengerjaan')
                            ->required(),

                        Select::make('payment_type')
                            ->label('Tipe Pembayaran')
                            ->options(collect(PaymentType::cases())
                                ->mapWithKeys(fn ($case) => [$case->value => $case->label()])
                                ->toArray())
                            ->default(PaymentType::DP->value)
                            ->required(),
                    ])
                    ->columns(['default' => 2])
                    ->columnSpan(2),

                Section::make('Status & Kendali')
                    ->schema([
                        Select::make('status')
                            ->label('Status')
                            ->options(collect(BookingStatus::cases())
                                ->mapWithKeys(fn ($case) => [$case->value => $case->label()])
                                ->toArray())
                            ->default(BookingStatus::PENDING->value)
                            ->disabled()
                            ->helperText('Status dikelola otomatis / via aksi status, tidak bisa diubah langsung.'),

                        Textarea::make('admin_notes')
                            ->label('Catatan Admin')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columnSpan(1),

                Section::make('Data Kendaraan')
                    ->schema([
                        TextInput::make('vehicle_name')
                            ->label('Nama Kendaraan')
                            ->required()
                            ->maxLength(150),

                        TextInput::make('vehicle_color')
                            ->label('Warna Kendaraan')
                            ->maxLength(100),

                        TextInput::make('vehicle_license')
                            ->label('Nomor Polisi')
                            ->maxLength(50),

                        Textarea::make('notes')
                            ->label('Keterangan Tambahan')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(['default' => 3])
                    ->columnSpan(3)
                    ->collapsible(),
            ]);
    }
}