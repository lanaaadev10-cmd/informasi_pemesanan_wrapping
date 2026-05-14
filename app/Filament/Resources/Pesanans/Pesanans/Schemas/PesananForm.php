<?php

namespace App\Filament\Resources\Pesanans\Pesanans\Schemas;

use Filament\Forms\Components\Card;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PesananForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Grid::make(3)
                    ->schema([
                        Section::make('Informasi Pesanan')
                            ->columnSpan(2)
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('kode_pesanan')
                                            ->disabled()
                                            ->label('ID Pesanan'),
                                        Select::make('id_user')
                                            ->relationship('user', 'name')
                                            ->disabled()
                                            ->label('Pelanggan'),
                                        DatePicker::make('tanggal_pesan')
                                            ->disabled(),
                                        TextInput::make('total_harga')
                                            ->numeric()
                                            ->prefix('Rp')
                                            ->disabled(),
                                    ]),
                            ]),

                        Section::make('Status & Kendali')
                            ->columnSpan(1)
                            ->schema([
                                Select::make('status')
                                    ->options([
                                        'menunggu_verifikasi' => 'Menunggu Verifikasi Pesanan',
                                        'perlu_diperbaiki'    => 'Perlu Diperbaiki',
                                        'diverifikasi'       => 'Pesanan Diverifikasi (Siap Bayar)',
                                        'menunggu_pembayaran' => 'Menunggu Pembayaran',
                                        'dibayar'            => 'Sudah Dibayar',
                                        'selesai'            => 'Selesai',
                                        'dibatalkan'         => 'Dibatalkan',
                                    ])
                                    ->required()
                                    ->native(false),
                                Textarea::make('catatan_admin')
                                    ->label('Catatan Admin (Akan dilihat user)')
                                    ->placeholder('Masukkan catatan jika ada...'),
                            ]),

                        Section::make('Detail Item Pesanan')
                            ->columnSpan(3)
                            ->schema([
                                Placeholder::make('items_placeholder')
                                    ->label('')
                                    ->content(function ($record) {
                                        if (!$record) return 'Tidak ada data.';
                                        
                                        $html = '<div class="space-y-4">';
                                        foreach ($record->details as $detail) {
                                            $html .= "<div class='p-4 bg-gray-50 rounded-xl border border-gray-100 flex justify-between items-center'>";
                                            $html .= "<div><p class='font-bold text-gray-900'>{$detail->layanan->nama_paket}</p>";
                                            $html .= "<p class='text-xs text-gray-500'>Jumlah: {$detail->jumlah} x Rp " . number_format($detail->harga_satuan, 0, ',', '.') . "</p></div>";
                                            $html .= "<p class='font-black text-gray-900'>Rp " . number_format($detail->subtotal, 0, ',', '.') . "</p>";
                                            $html .= "</div>";
                                        }
                                        $html .= '</div>';
                                        
                                        return new \Illuminate\Support\HtmlString($html);
                                    }),
                            ]),

                        Section::make('Bukti Pembayaran')
                            ->columnSpan(3)
                            ->schema([
                                Placeholder::make('payment_info')
                                    ->label('')
                                    ->content(function ($record) {
                                        $pembayaran = $record?->pembayaran;
                                        if (!$pembayaran) return 'Belum ada data pembayaran.';
                                        
                                        $statusClass = $pembayaran->verifikasi_admin == 'diverifikasi' ? 'text-green-600' : 'text-yellow-600';
                                        
                                        $html = "<div class='grid grid-cols-1 md:grid-cols-2 gap-8 items-start'>";
                                        $html .= "<div>";
                                        $html .= "<p class='text-sm font-bold text-gray-400 uppercase tracking-widest mb-2'>Status Pembayaran</p>";
                                        $html .= "<p class='text-xl font-black {$statusClass} uppercase'>{$pembayaran->verifikasi_admin}</p>";
                                        $html .= "<p class='mt-4 text-sm font-bold text-gray-400 uppercase tracking-widest mb-2'>Metode</p>";
                                        $html .= "<p class='font-bold text-gray-900'>{$pembayaran->metode_pembayaran}</p>";
                                        $html .= "</div>";
                                        
                                        if ($pembayaran->bukti_transfer) {
                                            $url = asset('storage/' . $pembayaran->bukti_transfer);
                                            $html .= "<div>";
                                            $html .= "<p class='text-sm font-bold text-gray-400 uppercase tracking-widest mb-4'>Bukti Transfer</p>";
                                            $html .= "<a href='{$url}' target='_blank'><img src='{$url}' class='w-64 rounded-2xl shadow-lg hover:scale-105 transition-transform' /></a>";
                                            $html .= "</div>";
                                        }
                                        
                                        $html .= "</div>";
                                        
                                        return new \Illuminate\Support\HtmlString($html);
                                    }),
                            ]),
                    ]),
            ]);
    }
}
