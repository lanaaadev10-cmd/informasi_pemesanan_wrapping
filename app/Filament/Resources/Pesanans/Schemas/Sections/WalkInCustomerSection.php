<?php

namespace App\Filament\Resources\Pesanans\Schemas\Sections;

use App\Models\User;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class WalkInCustomerSection
{
    public static function make(): Section
    {
        return Section::make('Informasi Pelanggan Walk-In')
            ->icon('heroicon-o-user')
            ->description('Pilih apakah pesanan walk-in ini ingin dibuatkan akun pelanggan atau dicatat langsung tanpa akun.')
            ->schema([
                Radio::make('opsi_pelanggan')
                    ->label('Pilihan Akun Pelanggan')
                    ->options([
                        'dengan_akun' => '👤 Gunakan / Buatkan Akun Pelanggan (Bisa Login ke Web)',
                        'tanpa_akun'  => '⚡ Pesan Cepat (Tanpa Akun / Guest)',
                    ])
                    ->default('dengan_akun')
                    ->inline()
                    ->live(),

                // Jika Dengan Akun:
                Grid::make(1)
                    ->visible(fn (Get $get) => $get('opsi_pelanggan') !== 'tanpa_akun')
                    ->schema([
                        Select::make('id_user')
                            ->label('Pilih / Buatkan Akun Pelanggan *')
                            ->placeholder('Cari nama pelanggan atau klik tombol [+] untuk buatkan akun baru')
                            ->options(User::whereDoesntHave('roles', fn ($q) => $q->where('name', 'admin'))->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->live()
                            ->required(fn (Get $get) => $get('opsi_pelanggan') !== 'tanpa_akun')
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
                                    ->helperText('Jika dikosongkan, sistem otomatis membuat email: walkin_[no_hp]@customer.local'),
                                TextInput::make('password')
                                    ->label('Password Akun Pelanggan')
                                    ->default('password123')
                                    ->required()
                                    ->helperText('Password untuk pelanggan login ke website.'),
                            ])
                            ->createOptionUsing(function (array $data): int {
                                $phone = trim($data['phone'] ?? '');
                                $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
                                $email = !empty($data['email'])
                                    ? trim($data['email'])
                                    : ('walkin_' . ($cleanPhone ?: time()) . '@customer.local');

                                $existing = User::where('email', $email)->first();
                                if ($existing) {
                                    return $existing->id;
                                }

                                $user = User::create([
                                    'name'               => trim($data['name']),
                                    'phone'              => $phone,
                                    'email'              => $email,
                                    'password'           => $data['password'] ?? 'password123',
                                    'email_verified_at'  => now(),
                                    'is_walk_in'         => true,
                                    'walk_in_created_by' => auth()->id(),
                                    'walk_in_note'       => 'Akun customer walk-in dibuat langsung oleh admin di kasir.',
                                ]);

                                if (method_exists($user, 'assignRole')) {
                                    $user->assignRole('user');
                                }

                                return $user->id;
                            })
                            ->createOptionAction(
                                fn (\Filament\Actions\Action $action) => $action
                                    ->label('Buatkan Akun Baru')
                                    ->tooltip('Klik untuk membuatkan akun pelanggan baru')
                                    ->modalHeading('➕ Buatkan Akun Pelanggan Baru')
                                    ->modalDescription('Isi nama & nomor WhatsApp pelanggan. Akun akan langsung dibuat dan otomatis terpilih di formulir pesanan ini.')
                                    ->modalSubmitActionLabel('Buatkan Akun & Pilih')
                            )
                            ->afterStateUpdated(function ($state, Set $set) {
                                if ($state) {
                                    $u = User::find($state);
                                    if ($u) {
                                        $set('customer_name', $u->name);
                                        if ($u->phone) {
                                            $set('whatsapp_number', $u->phone);
                                        }
                                    }
                                }
                            })
                            ->helperText('Pelanggan belum punya akun? Klik tombol [+] di samping kolom ini untuk membuatkan akun baru secara langsung.'),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('customer_name')
                                    ->label('Nama Pelanggan')
                                    ->placeholder('Otomatis terisi dari akun')
                                    ->readOnly()
                                    ->helperText('Otomatis terisi dari akun pelanggan terpilih.'),

                                TextInput::make('whatsapp_number')
                                    ->label('Nomor WhatsApp / HP Pelanggan')
                                    ->placeholder('Contoh: 081234567890')
                                    ->tel()
                                    ->helperText('Nomor HP/WA untuk komunikasi progres pengerjaan.'),
                            ]),
                    ]),

                // Jika Tanpa Akun:
                Grid::make(2)
                    ->visible(fn (Get $get) => $get('opsi_pelanggan') === 'tanpa_akun')
                    ->schema([
                        TextInput::make('guest_customer_name')
                            ->label('Nama Lengkap Pelanggan *')
                            ->placeholder('Contoh: Budi Santoso')
                            ->required(fn (Get $get) => $get('opsi_pelanggan') === 'tanpa_akun'),

                        TextInput::make('guest_whatsapp_number')
                            ->label('Nomor WhatsApp / HP *')
                            ->placeholder('Contoh: 081234567890')
                            ->tel()
                            ->required(fn (Get $get) => $get('opsi_pelanggan') === 'tanpa_akun'),

                        Placeholder::make('info_tanpa_akun')
                            ->label('')
                            ->columnSpan(2)
                            ->content(new \Illuminate\Support\HtmlString('<div class="p-3 text-sm bg-amber-50 dark:bg-amber-950/40 text-amber-800 dark:text-amber-300 rounded-xl border border-amber-200 dark:border-amber-800 flex items-center gap-2"><span>⚡</span> <span><strong>Mode Pesanan Cepat:</strong> Pesanan langsung tercatat sebagai transaksi walk-in atas nama pelanggan di atas tanpa membuat akun login web.</span></div>')),
                    ]),
            ]);
    }
}
