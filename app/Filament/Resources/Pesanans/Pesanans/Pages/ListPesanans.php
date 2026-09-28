<?php

namespace App\Filament\Resources\Pesanans\Pesanans\Pages;

use App\Filament\Resources\Pesanans\Pesanans\PesananResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPesanans extends ListRecords
{
    protected static string $resource = PesananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('createOfflinePesanan')
                ->label('➕ Buat Pesanan Offline (Walk-in)')
                ->icon('heroicon-o-user-plus')
                ->color('warning')
                ->modalHeading('Buat Pesanan Offline / Walk-in')
                ->modalDescription('Admin membuatkan pesanan langsung untuk pelanggan walk-in. Akun pelanggan akan dibuatkan otomatis jika belum ada.')
                ->modalSubmitActionLabel('Simpan Pesanan Offline')
                ->form([
                    \Filament\Forms\Components\Section::make('Informasi Pelanggan Walk-in')
                        ->schema([
                            \Filament\Forms\Components\Select::make('user_id')
                                ->label('Pilih Pelanggan Terdaftar (Opsional)')
                                ->options(\App\Models\User::pluck('name', 'id'))
                                ->searchable()
                                ->helperText('Kosongkan jika pelanggan baru / walk-in tanpa akun. Sistem akan membuatkan akun otomatis.'),
                            \Filament\Forms\Components\TextInput::make('customer_name')
                                ->label('Nama Lengkap Pelanggan *')
                                ->required(),
                            \Filament\Forms\Components\TextInput::make('phone')
                                ->label('Nomor HP/WA *')
                                ->tel()
                                ->required(),
                            \Filament\Forms\Components\TextInput::make('email')
                                ->label('Email (Opsional)')
                                ->email()
                                ->placeholder('opsional@domain.com'),
                        ])->columns(2),

                    \Filament\Forms\Components\Section::make('Layanan & Tanggal Pengerjaan')
                        ->schema([
                            \Filament\Forms\Components\Select::make('layanan_id')
                                ->label('Pilih Paket Layanan *')
                                ->options(\App\Models\Layanan::pluck('nama_layanan', 'id_layanan'))
                                ->searchable()
                                ->required(),
                            \Filament\Forms\Components\DatePicker::make('booking_date')
                                ->label('Tanggal Pengerjaan *')
                                ->default(now()->toDateString())
                                ->required()
                                ->helperText('Maksimal 5 slot kendaraan per hari (gabungan booking & pesanan).'),
                            \Filament\Forms\Components\Select::make('payment_method')
                                ->label('Metode Pembayaran')
                                ->options([
                                    'cash' => 'Cash / Tunai di Kasir',
                                    'transfer_bank' => 'Transfer Bank',
                                    'transfer_e_wallet' => 'E-Wallet',
                                ])
                                ->default('cash')
                                ->required(),
                            \Filament\Forms\Components\Select::make('status')
                                ->label('Status Awal Pesanan')
                                ->options([
                                    \App\Models\Pesanan::STATUS_SEDANG_DIPROSES => 'Sedang Diproses (Mobil di Bengkel)',
                                    \App\Models\Pesanan::STATUS_DIKONFIRMASI => 'Dikonfirmasi (Lunas)',
                                    \App\Models\Pesanan::STATUS_SELESAI => 'Selesai',
                                ])
                                ->default(\App\Models\Pesanan::STATUS_SEDANG_DIPROSES)
                                ->required(),
                        ])->columns(2),

                    \Filament\Forms\Components\Section::make('Data Kendaraan')
                        ->schema([
                            \Filament\Forms\Components\TextInput::make('model_kendaraan')
                                ->label('Merk & Model Kendaraan *')
                                ->placeholder('Contoh: Honda HR-V 2023')
                                ->required(),
                            \Filament\Forms\Components\TextInput::make('warna_kendaraan')
                                ->label('Warna Kendaraan')
                                ->placeholder('Contoh: Hitam Glossy'),
                            \Filament\Forms\Components\TextInput::make('nomor_polisi')
                                ->label('Nomor Polisi')
                                ->placeholder('Contoh: B 1234 ABC'),
                            \Filament\Forms\Components\TextInput::make('tahun_produksi')
                                ->label('Tahun Produksi')
                                ->numeric()
                                ->placeholder('2023'),
                            \Filament\Forms\Components\Textarea::make('catatan')
                                ->label('Catatan Pengerjaan')
                                ->columnSpan(2),
                        ])->columns(2),
                ])
                ->action(function (array $data) {
                    try {
                        $service = app(\App\Services\OfflinePesananService::class);
                        $data['admin_id'] = auth()->id();
                        $pesanan = $service->createOfflineOrder($data);

                        \Filament\Notifications\Notification::make()
                            ->title('Pesanan Offline Berhasil Dibuat!')
                            ->body("Kode Pesanan: {$pesanan->kode_pesanan}. Akun walk-in telah diproses.")
                            ->success()
                            ->send();
                    } catch (\App\Exceptions\SlotPenuhException $e) {
                        \Filament\Notifications\Notification::make()
                            ->title('Gagal: Slot Kuota Penuh!')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    } catch (\Throwable $e) {
                        \Filament\Notifications\Notification::make()
                            ->title('Terjadi Kesalahan')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => \Filament\Schemas\Components\Tabs\Tab::make('Semua Pesanan'),
            'offline' => \Filament\Schemas\Components\Tabs\Tab::make('🟠 Offline (Walk-in)')
                ->modifyQueryUsing(fn ($query) => $query->whereIn('order_source', ['offline', 'walk_in']))
                ->badge(\App\Models\Pesanan::whereIn('order_source', ['offline', 'walk_in'])->count())
                ->badgeColor('warning'),
            'verifikasi' => \Filament\Schemas\Components\Tabs\Tab::make('Perlu Verifikasi')
                ->modifyQueryUsing(fn ($query) => $query->where('status', \App\Models\Pesanan::STATUS_MENUNGGU_VERIFIKASI_PEMBAYARAN))
                ->badge(\App\Models\Pesanan::where('status', \App\Models\Pesanan::STATUS_MENUNGGU_VERIFIKASI_PEMBAYARAN)->count())
                ->badgeColor('warning'),
            'pembayaran' => \Filament\Schemas\Components\Tabs\Tab::make('Menunggu Bayar')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'menunggu_pembayaran'))
                ->badge(\App\Models\Pesanan::where('status', 'menunggu_pembayaran')->count()),
            'validasi' => \Filament\Schemas\Components\Tabs\Tab::make('Pembayaran OK')
                ->modifyQueryUsing(fn ($query) => $query->where('status', \App\Models\Pesanan::STATUS_DIKONFIRMASI))
                ->badge(\App\Models\Pesanan::where('status', \App\Models\Pesanan::STATUS_DIKONFIRMASI)->count())
                ->badgeColor('success'),
            'proses' => \Filament\Schemas\Components\Tabs\Tab::make('Dalam Proses')
                ->modifyQueryUsing(fn ($query) => $query->where('status', \App\Models\Pesanan::STATUS_SEDANG_DIPROSES))
                ->badge(\App\Models\Pesanan::where('status', \App\Models\Pesanan::STATUS_SEDANG_DIPROSES)->count())
                ->badgeColor('info'),
            'selesai' => \Filament\Schemas\Components\Tabs\Tab::make('Selesai')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'selesai')),
        ];
    }
}
