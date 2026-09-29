<?php

namespace App\Filament\Resources\Pesanans\Schemas\Sections;

use App\Models\Pesanan;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;

class OrderDetailSection
{
    /**
     * Komponen schema formulir untuk mode View & Edit Pesanan
     */
    public static function make(): array
    {
        return [
            Grid::make(3)
                ->schema([
                    // ── 1. INFORMASI PESANAN ──
                    Section::make('Informasi Pesanan')
                        ->columnSpan(2)
                        ->icon('heroicon-o-document-text')
                        ->description('Kode pesanan, pelanggan, tanggal, dan total harga.')
                        ->schema([
                            Grid::make(2)
                                ->schema([
                                    TextInput::make('kode_pesanan')
                                        ->disabled()
                                        ->label('Kode Pesanan'),
                                    Select::make('id_user')
                                        ->relationship('user', 'name')
                                        ->searchable()
                                        ->preload()
                                        ->label('Pelanggan'),
                                    DatePicker::make('tanggal_pesan')
                                        ->label('Tanggal Dipesan'),
                                    TextInput::make('total_harga')
                                        ->numeric()
                                        ->prefix('Rp')
                                        ->label('Total Harga'),
                                    TextInput::make('whatsapp_number')
                                        ->label('Nomor Telepon Pelanggan')
                                        ->tel()
                                        ->columnSpan(2),
                                ]),
                        ]),

                    // ── 2. STATUS & KENDALI ──
                    Section::make('Status & Kendali')
                        ->columnSpan(1)
                        ->icon('heroicon-o-check-circle')
                        ->description('Atur status pesanan dan catatan.')
                        ->schema([
                            Select::make('status')
                                ->options([
                                    Pesanan::STATUS_MENUNGGU_KONFIRMASI_ADMIN => 'Menunggu Konfirmasi Admin',
                                    Pesanan::STATUS_MENUNGGU_PEMBAYARAN => 'Menunggu Pembayaran',
                                    Pesanan::STATUS_MENUNGGU_VERIFIKASI_PEMBAYARAN => 'Menunggu Verifikasi Pembayaran',
                                    Pesanan::STATUS_DIKONFIRMASI => 'Dikonfirmasi (Bayar OK)',
                                    Pesanan::STATUS_SEDANG_DIPROSES => 'Sedang Diproses',
                                    Pesanan::STATUS_SELESAI => 'Selesai',
                                    Pesanan::STATUS_DITOLAK => 'Ditolak',
                                ])
                                ->required()
                                ->native(false),
                            Textarea::make('catatan_admin')
                                ->label('Catatan Admin')
                                ->placeholder('Masukkan catatan jika ada...'),
                        ]),

                    // ── 3. DATA KENDARAAN & JADWAL SESI ──
                    Section::make('Data Kendaraan & Jadwal Sesi')
                        ->description('Informasi kendaraan pelanggan dan jadwal pengerjaan.')
                        ->icon('heroicon-o-truck')
                        ->relationship('form')
                        ->columnSpan(3)
                        ->collapsible()
                        ->schema([
                            Grid::make(3)
                                ->schema([
                                    TextInput::make('model_kendaraan')
                                        ->label('Merk & Model Kendaraan'),
                                    TextInput::make('warna_kendaraan')
                                        ->label('Warna Dasar'),
                                    TextInput::make('nomor_polisi')
                                        ->label('Nomor Polisi'),
                                    TextInput::make('tahun_produksi')
                                        ->label('Tahun Produksi')
                                        ->numeric(),
                                    Select::make('lokasi_pengerjaan')
                                        ->label('Lokasi Pengerjaan')
                                        ->options([
                                            'toko' => 'Di Bengkel (Wrapping Studio)',
                                        ]),
                                    DateTimePicker::make('jadwal_pengerjaan')
                                        ->label('Tanggal Mulai Sesi')
                                        ->displayFormat('d M Y, H:i'),
                                    TextInput::make('estimasi_durasi')
                                        ->label('Estimasi Durasi'),
                                    Textarea::make('alamat_pengiriman')
                                        ->label('Alamat Pengerjaan')
                                        ->columnSpan(2),
                                ]),
                        ]),

                    // ── 4. DETAIL ITEM PESANAN ──
                    Section::make('Detail Item Pesanan')
                        ->columnSpan(3)
                        ->icon('heroicon-o-shopping-bag')
                        ->description('Daftar layanan yang dipesan oleh pelanggan.')
                        ->collapsible()
                        ->schema([
                            Placeholder::make('items_placeholder')
                                ->label('')
                                ->content(function ($record) {
                                    if (!$record || $record->details->isEmpty()) return 'Tidak ada data item.';
                                    
                                    $html = '<div class="space-y-4">';
                                    foreach ($record->details as $detail) {
                                        $namaPaket = $detail->layanan?->nama_layanan ?? 'Paket Layanan';
                                        $html .= "<div class='p-4 bg-gray-50 dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-white/10 flex justify-between items-center'>";
                                        $html .= "<div><p class='font-bold text-gray-900 dark:text-white'>{$namaPaket}</p>";
                                        $html .= "<p class='text-xs text-gray-500'>Jumlah: {$detail->jumlah} x Rp " . number_format($detail->harga_satuan, 0, ',', '.') . "</p></div>";
                                        $html .= "<p class='font-black text-gray-900 dark:text-white'>Rp " . number_format($detail->subtotal, 0, ',', '.') . "</p>";
                                        $html .= "</div>";
                                    }
                                    $html .= '</div>';
                                    
                                    return new \Illuminate\Support\HtmlString($html);
                                }),
                        ]),

                    // ── 5. INFORMASI PEMBAYARAN ──
                    Section::make('Informasi Pembayaran')
                        ->columnSpan(3)
                        ->icon('heroicon-o-credit-card')
                        ->description('Detail pembayaran dan bukti transfer dari pelanggan.')
                        ->collapsible()
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
                                        'transfer_bank'     => 'Transfer Bank',
                                        'transfer_e_wallet' => 'E-Wallet / QRIS',
                                        'cash'              => 'Cash / Tunai di Kasir',
                                        default             => $metodeStr,
                                    };

                                    $html = "<div class='grid grid-cols-1 md:grid-cols-2 gap-8 items-start'>";
                                    $html .= "<div>";
                                    $html .= "<p class='text-sm font-bold text-gray-400 uppercase tracking-widest mb-2'>Status Pembayaran</p>";
                                    $html .= "<p class='text-xl font-black {$statusClass}'>{$verifikasiLabel}</p>";
                                    $html .= "<p class='mt-4 text-sm font-bold text-gray-400 uppercase tracking-widest mb-2'>Metode</p>";
                                    $html .= "<p class='font-bold text-gray-900 dark:text-white'>{$metodeLabel}</p>";
                                    $html .= "<p class='mt-4 text-sm font-bold text-gray-400 uppercase tracking-widest mb-2'>Jumlah Bayar</p>";
                                    $html .= "<p class='font-black text-lg text-gray-900 dark:text-white'>Rp " . number_format($pembayaran->jumlah_bayar, 0, ',', '.') . "</p>";
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
        ];
    }
}
