<?php

namespace App\Filament\Resources\Pesanans\Pesanans\Schemas;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Schema;

class PesananForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
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

                        // Fieldset untuk mengedit Data Kendaraan & Jadwal Sesi (relasi ke form_pesanans)
                        Fieldset::make('Data Kendaraan & Jadwal Sesi')
                            ->relationship('form')
                            ->columnSpan(3)
                            ->schema([
                                Grid::make(3)
                                    ->schema([
                                        TextInput::make('model_kendaraan')
                                            ->label('Merk & Model Kendaraan')
                                            ->placeholder('Contoh: Porsche 911 GT3')
                                            ->required(),
                                        TextInput::make('warna_kendaraan')
                                            ->label('Warna Dasar')
                                            ->placeholder('Contoh: Chalk White')
                                            ->required(),
                                        TextInput::make('nomor_polisi')
                                            ->label('Nomor Polisi')
                                            ->placeholder('Contoh: B 911 RSR')
                                            ->required(),
                                        TextInput::make('tahun_produksi')
                                            ->label('Tahun Produksi')
                                            ->numeric()
                                            ->placeholder('Contoh: 2023')
                                            ->required(),
                                        Select::make('lokasi_pengerjaan')
                                            ->label('Workshop / Lokasi Pengerjaan')
                                            ->options([
                                                'toko' => 'Studio HQ (Wapping Premium)',
                                                'rumah' => 'Home Service',
                                            ])
                                            ->required()
                                            ->native(false),
                                        DateTimePicker::make('jadwal_pengerjaan')
                                            ->label('Tanggal Mulai Sesi (Jadwal)')
                                            ->displayFormat('d M Y, H:i')
                                            ->required(),
                                        TextInput::make('estimasi_durasi')
                                            ->label('Estimasi Durasi')
                                            ->placeholder('Contoh: 4 - 5 Hari Kerja')
                                            ->default('4 - 5 Hari Kerja')
                                            ->required()
                                            ->columnSpan(1),
                                        Textarea::make('alamat_pengiriman')
                                            ->label('Alamat Lengkap Pengerjaan / Penjemputan')
                                            ->placeholder('Masukkan alamat lengkap...')
                                            ->required()
                                            ->columnSpan(2),
                                    ]),
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
                                        $verifikasiLabel = match($pembayaran->verifikasi_admin) {
                                            'diverifikasi' => '✅ Terverifikasi',
                                            'menunggu'     => '⏳ Menunggu Verifikasi Admin',
                                            'ditolak'      => '❌ Ditolak',
                                            default        => $pembayaran->verifikasi_admin,
                                        };
                                        $metodeStr = $pembayaran->metode_pembayaran instanceof \App\Enums\PaymentMethod
                                            ? $pembayaran->metode_pembayaran->value
                                            : (string) $pembayaran->metode_pembayaran;
                                        $metodeLabel = match($metodeStr) {
                                            'transfer_bank'   => 'Transfer Bank (Manual)',
                                            'transfer_e_wallet' => 'E-Wallet Transfer (Manual)',
                                            'cash'            => 'Cash',
                                            default           => $metodeStr,
                                        };

                                        $html = "<div class='grid grid-cols-1 md:grid-cols-2 gap-8 items-start'>";
                                        $html .= "<div>";
                                        $html .= "<p class='text-sm font-bold text-gray-400 uppercase tracking-widest mb-2'>Status Pembayaran</p>";
                                        $html .= "<p class='text-xl font-black {$statusClass}'>{$verifikasiLabel}</p>";
                                        $html .= "<p class='mt-4 text-sm font-bold text-gray-400 uppercase tracking-widest mb-2'>Metode</p>";
                                        $html .= "<p class='font-bold text-gray-900'>{$metodeLabel}</p>";
                                        $html .= "<p class='mt-4 text-sm font-bold text-gray-400 uppercase tracking-widest mb-2'>Jumlah Bayar</p>";
                                        $html .= "<p class='font-black text-lg text-gray-900'>Rp " . number_format($pembayaran->jumlah_bayar, 0, ',', '.') . "</p>";
                                        $html .= "<p class='mt-4 text-sm font-bold text-gray-400 uppercase tracking-widest mb-2'>Tanggal Pembayaran</p>";
                                        $html .= "<p class='font-bold text-gray-900'>" . ($pembayaran->tgl_bayar ? \Carbon\Carbon::parse($pembayaran->tgl_bayar)->translatedFormat('d M Y') : '-') . "</p>";
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
