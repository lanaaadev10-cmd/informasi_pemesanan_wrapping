<?php

namespace App\Filament\Resources\Pesanans\Schemas\Sections;

use App\Models\Pesanan;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;

class WalkInBillingSection
{
    public static function make(): Group
    {
        return Group::make([
            // ── 1. JADWAL PENGERJAAN ──
            Section::make('Jadwal Pengerjaan')
                ->icon('heroicon-o-calendar')
                ->description('Atur tanggal mulai pengerjaan kendaraan di bengkel.')
                ->schema([
                    DatePicker::make('booking_date')
                        ->label('Tanggal Pengerjaan *')
                        ->default(now()->toDateString())
                        ->required()
                        ->helperText('Maksimal kuota 5 kendaraan per hari (terintegrasi kuota kalender).'),

                    TextInput::make('estimasi_durasi')
                        ->label('Estimasi Durasi')
                        ->default('3 - 4 Hari Kerja')
                        ->placeholder('Contoh: 3 - 4 Hari Kerja'),
                ]),

            // ── 2. STATUS & PEMBAYARAN KASIR ──
            Section::make('Kasir & Status Pembayaran')
                ->icon('heroicon-o-credit-card')
                ->description('Pencatatan pembayaran di kasir workshop.')
                ->schema([
                    Select::make('payment_method')
                        ->label('Metode Bayar')
                        ->options([
                            'cash'              => '💵 Tunai / Cash di Kasir',
                            'transfer_bank'     => '🏦 Transfer Bank',
                            'transfer_e_wallet' => '📱 QRIS / E-Wallet',
                        ])
                        ->default('cash')
                        ->required(),

                    Select::make('payment_status')
                        ->label('Status Pembayaran')
                        ->options([
                            'lunas'   => '✅ Lunas di Tempat',
                            'dp'      => '🟡 DP (Uang Muka)',
                            'pending' => '⏳ Belum Bayar',
                        ])
                        ->default('lunas')
                        ->required(),

                    Select::make('status')
                        ->label('Status Pesanan Awal')
                        ->options([
                            Pesanan::STATUS_SEDANG_DIPROSES => 'Sedang Diproses (Mobil di Workshop)',
                            Pesanan::STATUS_DIKONFIRMASI   => 'Dikonfirmasi (Antrean Pengerjaan)',
                            Pesanan::STATUS_SELESAI         => 'Selesai (Siap Diambil)',
                        ])
                        ->default(Pesanan::STATUS_SEDANG_DIPROSES)
                        ->required(),
                ]),

            // ── 3. HIGHLIGHT RINGKASAN TAGIHAN ──
            Section::make('Ringkasan Tagihan Kasir')
                ->icon('heroicon-o-banknotes')
                ->schema([
                    Placeholder::make('billing_summary')
                        ->label('')
                        ->content(function (Get $get) {
                            $total = (float) ($get('total_harga') ?? 0);
                            $formattedTotal = 'Rp ' . number_format($total, 0, ',', '.');
                            $status = match($get('payment_status') ?? 'lunas') {
                                'lunas' => '✅ Lunas di Kasir',
                                'dp'    => '🟡 Uang Muka (DP)',
                                default => '⏳ Menunggu Pembayaran',
                            };
                            $metode = match($get('payment_method') ?? 'cash') {
                                'transfer_bank'     => 'Transfer Bank',
                                'transfer_e_wallet' => 'QRIS / E-Wallet',
                                default             => 'Tunai / Cash',
                            };

                            return new \Illuminate\Support\HtmlString("
                                <div class='p-4 bg-gradient-to-br from-amber-500/10 via-orange-500/5 to-transparent dark:from-amber-950/30 dark:to-transparent rounded-2xl border border-amber-500/20 space-y-3'>
                                    <div>
                                        <span class='text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider'>Total Tagihan Kasir:</span>
                                        <p class='text-2xl font-black text-amber-600 dark:text-amber-400 mt-0.5 tracking-tight'>{$formattedTotal}</p>
                                    </div>
                                    <div class='flex justify-between items-center text-xs pt-2 border-t border-amber-500/10'>
                                        <span class='text-gray-500'>Metode: <strong>{$metode}</strong></span>
                                        <span class='font-bold text-gray-700 dark:text-gray-300'>{$status}</span>
                                    </div>
                                </div>
                            ");
                        }),
                ]),
        ]);
    }
}
