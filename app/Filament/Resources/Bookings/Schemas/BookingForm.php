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

                        TextInput::make('customer_name')
                            ->label('Nama Lengkap Pelanggan')
                            ->placeholder('Nama pemesan...')
                            ->required(),

                        TextInput::make('customer_phone')
                            ->label('Nomor WhatsApp')
                            ->placeholder('081234567890')
                            ->required(),

                        TextInput::make('customer_email')
                            ->label('Email (Opsional)')
                            ->email(),

                        Select::make('user_id')
                            ->label('Akun Pengguna (Opsional)')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable(),

                        Select::make('layanan_id')
                            ->label('Paket Layanan')
                            ->relationship('layanan', 'nama_layanan')
                            ->searchable()
                            ->preload()
                            ->required(),

                        DatePicker::make('booking_date')
                            ->label('Tanggal Pengerjaan')
                            ->default(now()->toDateString())
                            ->required(),

                        TextInput::make('booking_time')
                            ->label('Jam Booking')
                            ->default('09:00')
                            ->placeholder('09:00'),

                        Select::make('payment_type')
                            ->label('Tipe Pembayaran')
                            ->options(collect(PaymentType::cases())
                                ->mapWithKeys(fn ($case) => [$case->value => $case->label()])
                                ->toArray())
                            ->default(PaymentType::DP->value)
                            ->required(),

                        \Filament\Forms\Components\Toggle::make('override_quota')
                            ->label('Admin Override Kuota (Maks 5 Booking)')
                            ->helperText('Aktifkan jika ingin mengabaikan batas kuota 5 booking per hari.')
                            ->default(false),
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