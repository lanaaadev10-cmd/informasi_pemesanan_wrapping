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
                Section::make('Akun Pembeli')
                    ->description('Pilih akun pembeli yang sesuai atau daftarkan akun baru agar pesanan langsung muncul di akun & riwayat mereka.')
                    ->schema([
                        Select::make('user_id')
                            ->label('Pilih Akun Pembeli *')
                            ->placeholder('Cari nama pembeli, nomor WhatsApp, atau email...')
                            ->options(function () {
                                return User::whereDoesntHave('roles', fn ($q) => $q->where('name', 'admin'))
                                    ->orderBy('name')
                                    ->get()
                                    ->mapWithKeys(function ($u) {
                                        $phone = $u->phone ?: ($u->no_hp ?? '');
                                        $phoneLabel = $phone ? " • {$phone}" : '';
                                        return [$u->id => "{$u->name}{$phoneLabel} ({$u->email})"];
                                    })
                                    ->toArray();
                            })
                            ->searchable()
                            ->preload()
                            ->live()
                            ->required()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->label('Nama Lengkap Pembeli *')
                                    ->placeholder('Contoh: Budi Santoso')
                                    ->required(),
                                TextInput::make('phone')
                                    ->label('Nomor WhatsApp / HP *')
                                    ->placeholder('Contoh: 081234567890')
                                    ->tel()
                                    ->required(),
                                TextInput::make('email')
                                    ->label('Email Pembeli (Opsional)')
                                    ->placeholder('contoh: budi@gmail.com')
                                    ->email()
                                    ->helperText('Jika dikosongkan, sistem otomatis membuat email: customer_[no_wa]@dantiestiker.id'),
                                TextInput::make('password')
                                    ->label('Password Akun')
                                    ->default('password123')
                                    ->required()
                                    ->helperText('Password default untuk pembeli login ke sistem.'),
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
                                    ->label('Buatkan Akun Pembeli Baru')
                                    ->tooltip('Klik untuk mendaftarkan akun pembeli baru secara instan')
                                    ->modalHeading('➕ Buatkan Akun Pembeli Baru')
                                    ->modalDescription('Akun ini akan langsung aktif dan pesanan otomatis tersimpan di dashboard pembeli.')
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
                            ->helperText('Pilih akun pembeli yang terdaftar. Jika pembeli baru, klik tanda [+] untuk membuat akun seketika.')
                            ->columnSpanFull(),

                        TextInput::make('customer_name')
                            ->label('Nama Lengkap Pembeli *')
                            ->placeholder('Nama pemesan...')
                            ->required(),

                        TextInput::make('customer_phone')
                            ->label('Nomor WhatsApp *')
                            ->placeholder('081234567890')
                            ->required(),

                        TextInput::make('customer_email')
                            ->label('Email Pembeli (Opsional)')
                            ->email()
                            ->columnSpanFull(),
                    ])
                    ->columns(['default' => 2])
                    ->columnSpan(2),

                Section::make('Status & Kendali')
                    ->schema([
                        TextInput::make('booking_code')
                            ->label('Kode Pesanan / Booking')
                            ->disabled()
                            ->dehydrated()
                            ->default(fn () => 'BKG-' . date('YmdHis') . '-' . strtoupper(\Illuminate\Support\Str::random(6))),

                        Select::make('status')
                            ->label('Status Pesanan')
                            ->options(collect(BookingStatus::cases())
                                ->mapWithKeys(fn ($case) => [$case->value => $case->label()])
                                ->toArray())
                            ->default(BookingStatus::CONFIRMED->value)
                            ->helperText('Pilih status: Dikonfirmasi (deal), Menunggu Bayar (tagihan aktif), dll.'),

                        Textarea::make('admin_notes')
                            ->label('Catatan Internal Admin')
                            ->rows(3)
                            ->placeholder('Catatan internal staff bengkel...')
                            ->columnSpanFull(),
                    ])
                    ->columnSpan(1),

                Section::make('Jenis Pesanan & Detail Kesepakatan')
                    ->description('Masukkan jenis pesanan (Jasa atau Custom), detail kebutuhan, harga yang disepakati, dan jadwal pengerjaan.')
                    ->schema([
                        Radio::make('tipe_pemesanan')
                            ->label('Jenis Pesanan *')
                            ->options([
                                'jasa'   => '✂️ Jasa (Pemasangan Stiker / Bawa Bahan Sendiri - Deal WhatsApp)',
                                'custom' => '🎨 Custom (Wrapping Custom / Desain Livery Khusus - Deal WhatsApp)',
                                'paket'  => '📦 Paket Katalog (Pilihan Layanan Standar Bengkel)',
                            ])
                            ->default('jasa')
                            ->inline()
                            ->live()
                            ->required()
                            ->columnSpanFull(),

                        // Tampilan saat JASA atau CUSTOM (Deal WhatsApp) — Tanpa mengharuskan memilih paket katalog
                        TextInput::make('custom_project_name')
                            ->label(fn (Get $get) => $get('tipe_pemesanan') === 'jasa'
                                ? 'Detail Kebutuhan Jasa Pengerjaan *'
                                : 'Detail Kebutuhan Proyek Custom *')
                            ->placeholder(fn (Get $get) => $get('tipe_pemesanan') === 'jasa'
                                ? 'Contoh: Pasang decal stiker motor Nmax full body (bahan Oracal bawa sendiri)'
                                : 'Contoh: Wrapping custom livery anime two-tone kap & atap Brio')
                            ->required(fn (Get $get) => in_array($get('tipe_pemesanan'), ['jasa', 'custom', null]))
                            ->visible(fn (Get $get) => in_array($get('tipe_pemesanan'), ['jasa', 'custom', null]))
                            ->helperText('Rincian kebutuhan pengerjaan hasil kesepakatan dengan pembeli via WhatsApp.')
                            ->columnSpanFull(),

                        TextInput::make('quotation_amount')
                            ->label('Harga yang Disepakati (Rp) *')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(fn (Get $get) => in_array($get('tipe_pemesanan'), ['jasa', 'custom', null]))
                            ->visible(fn (Get $get) => in_array($get('tipe_pemesanan'), ['jasa', 'custom', null]))
                            ->helperText('Total harga pengerjaan hasil kesepakatan via WhatsApp.')
                            ->live(),

                        Select::make('quotation_status')
                            ->label('Status Kesepakatan WA')
                            ->options([
                                'accepted' => '🤝 Disepakati Pembeli (Deal)',
                                'sent'     => '✅ Penawaran Terkirim',
                                'pending'  => '⏳ Menunggu Negosiasi Lanjutan',
                            ])
                            ->default('accepted')
                            ->visible(fn (Get $get) => in_array($get('tipe_pemesanan'), ['jasa', 'custom', null])),

                        Textarea::make('quotation_notes')
                            ->label('Catatan Kesepakatan WhatsApp')
                            ->rows(2)
                            ->placeholder('Contoh: Deal harga Rp 1.500.000, estimasi 2 hari pengerjaan, DP 50%, pelunasan di bengkel.')
                            ->visible(fn (Get $get) => in_array($get('tipe_pemesanan'), ['jasa', 'custom', null]))
                            ->columnSpanFull(),

                        // Tampilan HANYA saat PAKET STANDAR (Katalog):
                        Select::make('layanan_id')
                            ->label('Pilih Paket Layanan Katalog *')
                            ->relationship('layanan', 'nama_layanan')
                            ->searchable()
                            ->preload()
                            ->required(fn (Get $get) => $get('tipe_pemesanan') === 'paket')
                            ->visible(fn (Get $get) => $get('tipe_pemesanan') === 'paket')
                            ->helperText('Pilih paket standar katalog jika bukan proyek Jasa atau Custom.')
                            ->columnSpanFull(),

                        // Jadwal & Pembayaran
                        DatePicker::make('booking_date')
                            ->label('Jadwal Tanggal Pengerjaan *')
                            ->default(now()->toDateString())
                            ->required(),

                        TextInput::make('booking_time')
                            ->label('Jam Kedatangan / Booking')
                            ->default('09:00')
                            ->placeholder('09:00'),

                        Select::make('payment_type')
                            ->label('Skema Pembayaran')
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
                            ->label('Nomor Polisi (Plat)')
                            ->placeholder('Contoh: B 1234 ABC')
                            ->maxLength(50),

                        Textarea::make('notes')
                            ->label('Catatan Khusus untuk Teknisi')
                            ->rows(2)
                            ->placeholder('Catatan spesifikasi kendaraan atau teknis saat pengerjaan...')
                            ->columnSpanFull(),
                    ])
                    ->columns(['default' => 3])
                    ->columnSpan(3)
                    ->collapsible(),
            ]);
    }
}