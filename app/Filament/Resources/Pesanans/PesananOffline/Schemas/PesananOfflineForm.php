<?php

namespace App\Filament\Resources\Pesanans\PesananOffline\Schemas;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PesananOfflineForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)->schema([
                    Section::make('Informasi Pelanggan')
                        ->columnSpan(2)
                        ->icon('heroicon-o-user')
                        ->schema([
                            Grid::make(2)->schema([
                                TextInput::make('customer_name')
                                    ->label('Nama Pelanggan')
                                    ->disabled(),
                                TextInput::make('whatsapp_number')
                                    ->label('No. WhatsApp')
                                    ->disabled(),
                            ]),
                            Textarea::make('address')
                                ->label('Alamat')
                                ->disabled()
                                ->columnSpan(2),
                        ]),

                    Section::make('Status')
                        ->columnSpan(1)
                        ->icon('heroicon-o-check-circle')
                        ->schema([
                            Select::make('status')
                                ->options([
                                    'menunggu_konfirmasi_admin'      => 'Tunggu Konfirmasi',
                                    'menunggu_pembayaran'            => 'Tunggu Pembayaran',
                                    'menunggu_verifikasi_pembayaran' => 'Verifikasi Bayar',
                                    'dikonfirmasi'                   => 'Pembayaran OK',
                                    'sedang_diproses'                => 'Sedang Diproses',
                                    'selesai'                        => 'Selesai',
                                    'ditolak'                        => 'Ditolak',
                                ])
                                ->required()
                                ->native(false),
                            Textarea::make('catatan_admin')
                                ->label('Catatan Admin'),
                        ]),
                ]),

                Section::make('Detail Pesanan')
                    ->icon('heroicon-o-shopping-bag')
                    ->schema([
                        Placeholder::make('items_placeholder')
                            ->label('')
                            ->content(function ($record) {
                                if (!$record || !$record->details || $record->details->isEmpty()) {
                                    return 'Tidak ada item pesanan.';
                                }

                                $html = '<div class="space-y-3">';
                                foreach ($record->details as $detail) {
                                    $namaPaket = $detail->layanan?->nama_paket ?? 'Paket #' . $detail->id_layanan;
                                    $html .= "<div class='flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700'>";
                                    $html .= "<div><p class='font-bold text-sm text-gray-900 dark:text-white'>{$namaPaket}</p>";
                                    $html .= "<p class='text-xs text-gray-500'>Jumlah: {$detail->jumlah} x Rp " . number_format($detail->harga_satuan ?? 0, 0, ',', '.') . "</p></div>";
                                    $html .= "<p class='font-black text-sm text-gray-900 dark:text-white'>Rp " . number_format($detail->subtotal ?? 0, 0, ',', '.') . "</p>";
                                    $html .= "</div>";
                                }
                                $html .= '</div>';

                                return new \Illuminate\Support\HtmlString($html);
                            }),
                    ]),

                Section::make('Informasi Pesanan')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('kode_pesanan')
                                ->label('Kode Pesanan')
                                ->disabled(),
                            TextInput::make('total_harga')
                                ->label('Total Harga')
                                ->prefix('Rp')
                                ->disabled()
                                ->numeric(),
                            TextInput::make('created_at')
                                ->label('Tanggal Pesan')
                                ->disabled(),
                        ]),
                    ]),
            ]);
    }
}
