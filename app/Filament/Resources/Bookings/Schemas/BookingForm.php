<?php

namespace App\Filament\Resources\Bookings\Schemas;

use App\Enums\BookingStatus;
use App\Enums\PaymentType;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class BookingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->columns(['default' => 3])
            ->schema([
                Section::make('Akun & Pelanggan')
                    ->description('Tautkan pesanan ini ke akun pelanggan agar langsung muncul di dashboard & riwayat pesanan mereka.')
                    ->schema([
                        Select::make('user_id')
                            ->label('Pilih / Buatkan Akun Pelanggan')
                            ->placeholder('Cari nama atau email pelanggan, atau klik [+] untuk buatkan akun baru...')
                            ->options(User::whereDoesntHave('roles', fn ($q) => $q->where('name', 'admin'))->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->live()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->label('Nama Lengkap Pelanggan *')
                                    ->placeholder('Contoh: Budi Santoso')
                                    ->required(),
                                TextInput::make('phone')
                                    ->label('Nomor WhatsApp / HP *')
                                    ->placeholder('Contoh: 081234567890')
                                    ->tel()
                                    ->required(),
                                TextInput::make('email')
                                    ->label('Email Pelanggan (Opsional)')
                                    ->placeholder('contoh: budi@gmail.com')
                                    ->email()
                                    ->helperText('Jika dikosongkan, sistem otomatis membuat email: customer_[no_wa]@dantiestiker.id'),
                                TextInput::make('password')
                                    ->label('Password Akun Pelanggan')
                                    ->default('password123')
                                    ->required()
                                    ->helperText('Password default untuk pelanggan login ke website.'),
                            ])
                            ->createOptionUsing(function (array $data): int {
                                $phone = trim($data['phone'] ?? '');
                                $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
                                $email = !empty($data['email'])
                                    ? trim($data['email'])
                                    : ('customer_' . ($cleanPhone ?: time()) . '@dantiestiker.id');

                                $existing = User::where('email', $email)->orWhere('phone', $phone)->first();
                                if ($existing) {
                                    return $existing->id;
                                }

                                $user = User::create([
                                    'name'               => trim($data['name']),
                                    'phone'              => $phone,
                                    'email'              => $email,
                                    'password'           => Hash::make($data['password'] ?? 'password123'),
                                    'email_verified_at'  => now(),
                                ]);

                                if (method_exists($user, 'assignRole')) {
                                    $user->assignRole('user');
                                }

                                return $user->id;
                            })
                            ->createOptionAction(
                                fn (\Filament\Actions\Action $action) => $action
                                    ->label('Buatkan Akun Pelanggan Baru')
                                    ->tooltip('Klik untuk mendaftarkan akun pelanggan baru secara instan')
                                    ->modalHeading('➕ Buatkan Akun Pelanggan Baru')
                                    ->modalDescription('Akun ini akan langsung aktif dan pesanan akan otomatis tersimpan di riwayat akunnya.')
                                    ->modalSubmitActionLabel('Simpan Akun & Pilih')
                            )
                            ->afterStateUpdated(function ($state, Set $set) {
                                if ($state) {
                                    $u = User::find($state);
                                    if ($u) {
                                        $set('customer_name', $u->name);
                                        $set('customer_phone', $u->phone ?: ($u->no_hp ?? ''));
                                        if ($u->email) {
                                            $set('customer_email', $u->email);
                                        }
                                    }
                                }
                            })
                            ->helperText('Pelanggan belum terdaftar? Klik tombol [+] untuk membuatkan akunnya seketika.')
                            ->columnSpanFull(),

                        TextInput::make('customer_name')
                            ->label('Nama Lengkap Pelanggan *')
                            ->placeholder('Nama pemesan...')
                            ->required(),

                        TextInput::make('customer_phone')
                            ->label('Nomor WhatsApp *')
                            ->placeholder('081234567890')
                            ->required(),

                        TextInput::make('customer_email')
                            ->label('Email Pelanggan (Opsional)')
                            ->email()
                            ->columnSpanFull(),
                    ])
                    ->columns(['default' => 2])
                    ->columnSpan(2),

                Section::make('Status & Kendali')
                    ->schema([
                        TextInput::make('booking_code')
                            ->label('Kode Booking')
                            ->disabled()
                            ->dehydrated()
                            ->default(fn () => 'BKG-' . date('YmdHis') . '-' . strtoupper(\Illuminate\Support\Str::random(6))),

                        Select::make('status')
                            ->label('Status Pesanan')
                            ->options(collect(BookingStatus::cases())
                                ->mapWithKeys(fn ($case) => [$case->value => $case->label()])
                                ->toArray())
                            ->default(BookingStatus::CONFIRMED->value)
                            ->helperText('Default: Terkonfirmasi jika sudah deal di WhatsApp.'),

                        Textarea::make('admin_notes')
                            ->label('Catatan Internal Admin')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columnSpan(1),

                Section::make('Layanan & Kesepakatan Biaya')
                    ->description('Pilih apakah pesanan merupakan paket standar atau proyek Custom hasil negosiasi/deal WhatsApp.')
                    ->schema([
                        Radio::make('tipe_pemesanan')
                            ->label('Jenis Pengerjaan')
                            ->options([
                                'custom' => '🎨 Proyek Custom (Hasil Kesepakatan / Deal WhatsApp)',
                                'paket'  => '📦 Paket Standar (Pilih dari Katalog Workshop)',
                            ])
                            ->default('custom')
                            ->inline()
                            ->live()
                            ->columnSpanFull(),

                        // Tampilan saat CUSTOM (Deal WA):
                        TextInput::make('custom_project_name')
                            ->label('Nama / Konsep Proyek Custom *')
                            ->placeholder('Contoh: Full Decal Racing Livery Beat / Wrap Two-Tone Atap + Spion Brio')
                            ->required(fn (Get $get) => $get('tipe_pemesanan') === 'custom')
                            ->visible(fn (Get $get) => $get('tipe_pemesanan') === 'custom')
                            ->helperText('Nama pengerjaan custom yang disepakati dengan pelanggan via WhatsApp.')
                            ->columnSpanFull(),

                        TextInput::make('quotation_amount')
                            ->label('Nominal Kesepakatan / Deal di WhatsApp (Rp) *')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(fn (Get $get) => $get('tipe_pemesanan') === 'custom')
                            ->visible(fn (Get $get) => $get('tipe_pemesanan') === 'custom')
                            ->helperText('Nominal total pengerjaan hasil deal di WhatsApp. Otomatis menentukan batas DP dan tagihan.')
                            ->live(),

                        Select::make('quotation_status')
                            ->label('Status Kesepakatan WA')
                            ->options([
                                'sent'     => '✅ Deal di WhatsApp (Penawaran Disepakati)',
                                'accepted' => '🤝 Disetujui Pelanggan',
                                'pending'  => '⏳ Menunggu Negosiasi Lanjutan',
                            ])
                            ->default('sent')
                            ->visible(fn (Get $get) => $get('tipe_pemesanan') === 'custom'),

                        Textarea::make('quotation_notes')
                            ->label('Rincian Kesepakatan di Chat WhatsApp')
                            ->rows(2)
                            ->placeholder('Contoh: Deal warna stiker abu doff, atap hitam + spion. DP 50%. Estimasi pengerjaan 2 hari.')
                            ->visible(fn (Get $get) => $get('tipe_pemesanan') === 'custom')
                            ->columnSpanFull(),

                        // Tampilan saat PAKET STANDAR:
                        Select::make('layanan_id')
                            ->label('Pilih Paket Layanan Katalog *')
                            ->relationship('layanan', 'nama_layanan')
                            ->searchable()
                            ->preload()
                            ->required(fn (Get $get) => $get('tipe_pemesanan') !== 'custom')
                            ->visible(fn (Get $get) => $get('tipe_pemesanan') !== 'custom')
                            ->columnSpanFull(),

                        // Jadwal & Pembayaran
                        DatePicker::make('booking_date')
                            ->label('Tanggal Pengerjaan')
                            ->default(now()->toDateString())
                            ->required(),

                        TextInput::make('booking_time')
                            ->label('Jam Kedatangan / Booking')
                            ->default('09:00')
                            ->placeholder('09:00'),

                        Select::make('payment_type')
                            ->label('Tipe Pembayaran Tagihan')
                            ->options(collect(PaymentType::cases())
                                ->mapWithKeys(fn ($case) => [$case->value => $case->label()])
                                ->toArray())
                            ->default(PaymentType::DP->value)
                            ->required(),

                        Toggle::make('override_quota')
                            ->label('Admin Override Kuota')
                            ->helperText('Aktifkan jika ingin memasukkan jadwal walau slot hari ini sudah penuh.')
                            ->default(true),
                    ])
                    ->columns(['default' => 2])
                    ->columnSpan(3),

                Section::make('Data Kendaraan')
                    ->schema([
                        TextInput::make('vehicle_name')
                            ->label('Nama & Model Kendaraan *')
                            ->placeholder('Contoh: Honda Vario 160 / Toyota Fortuner VRZ')
                            ->required()
                            ->maxLength(150),

                        TextInput::make('vehicle_color')
                            ->label('Warna Kendaraan')
                            ->placeholder('Contoh: Hitam Metalik')
                            ->maxLength(100),

                        TextInput::make('vehicle_license')
                            ->label('Nomor Polisi')
                            ->placeholder('Contoh: B 1234 ABC')
                            ->maxLength(50),

                        Textarea::make('notes')
                            ->label('Catatan Tambahan untuk Teknisi')
                            ->rows(2)
                            ->placeholder('Catatan khusus saat pengerjaan...')
                            ->columnSpanFull(),
                    ])
                    ->columns(['default' => 3])
                    ->columnSpan(3)
                    ->collapsible(),
            ]);
    }
}