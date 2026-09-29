<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Pesanans\PesananResource;
use App\Models\Pesanan;
use Filament\Actions\Action;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentOrdersWidget extends BaseWidget
{
    protected static ?string $heading = 'Pesanan Walk-In / Offline Terbaru';
    protected static ?string $description = '5 pesanan offline & toko paling baru';
    protected static ?int $sort = 6;

    protected int | string | array $columnSpan = [
        'default' => 'full',
        'lg' => 1,
    ];

    protected static ?int $defaultPaginationPageOption = 5;

    public function table(Table $table): Table
    {
        return $table
            ->query(Pesanan::latest()->limit(5))
            ->columns([
                Tables\Columns\TextColumn::make('kode_pesanan')
                    ->label('Kode Pesanan')
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama Pelanggan')
                    ->searchable(),

                Tables\Columns\TextColumn::make('total_harga')
                    ->label('Total Harga')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'menunggu_konfirmasi_admin' => 'warning',
                        'menunggu_pembayaran' => 'info',
                        'menunggu_verifikasi_pembayaran' => 'warning',
                        'dikonfirmasi' => 'success',
                        'sedang_diproses' => 'info',
                        'selesai' => 'success',
                        'ditolak' => 'danger',
                        default => 'gray',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'menunggu_konfirmasi_admin' => 'heroicon-m-clock',
                        'menunggu_pembayaran' => 'heroicon-m-credit-card',
                        'menunggu_verifikasi_pembayaran' => 'heroicon-m-check',
                        'dikonfirmasi' => 'heroicon-m-check-badge',
                        'sedang_diproses' => 'heroicon-m-cog-6-tooth',
                        'selesai' => 'heroicon-m-check-circle',
                        'ditolak' => 'heroicon-m-x-circle',
                        default => 'heroicon-m-question-mark-circle',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'menunggu_konfirmasi_admin' => 'Tunggu Konfirmasi',
                        'menunggu_pembayaran' => 'Tunggu Pembayaran',
                        'menunggu_verifikasi_pembayaran' => 'Verifikasi Bayar',
                        'dikonfirmasi' => 'Pembayaran OK',
                        'sedang_diproses' => 'Sedang Diproses',
                        'selesai' => 'Selesai',
                        'ditolak' => 'Ditolak',
                        default => ucfirst(str_replace('_', ' ', $state)),
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->actions([
                Action::make('view')
                    ->label('Buka')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Pesanan $record): string => PesananResource::getUrl('view', ['record' => $record])),
            ])
            ->paginated(false);
    }
}
