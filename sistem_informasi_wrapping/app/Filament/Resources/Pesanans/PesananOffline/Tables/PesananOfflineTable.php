<?php

namespace App\Filament\Resources\Pesanans\PesananOffline\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\DeleteAction;

class PesananOfflineTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('kode_pesanan')
                    ->label('Order ID')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable(),

                TextColumn::make('customer_name')
                    ->label('Pelanggan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('whatsapp_number')
                    ->label('WhatsApp'),

                TextColumn::make('details_count')
                    ->label('Item')
                    ->counts('details'),

                TextColumn::make('total_harga')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'menunggu_konfirmasi_admin'      => 'warning',
                        'menunggu_pembayaran'            => 'info',
                        'menunggu_verifikasi_pembayaran' => 'warning',
                        'dikonfirmasi'                   => 'success',
                        'sedang_diproses'                => 'primary',
                        'selesai'                        => 'success',
                        'ditolak'                        => 'danger',
                        default                          => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'menunggu_konfirmasi_admin'      => 'Tunggu Konfirmasi',
                        'menunggu_pembayaran'            => 'Tunggu Pembayaran',
                        'menunggu_verifikasi_pembayaran' => 'Verifikasi Bayar',
                        'dikonfirmasi'                   => 'Pembayaran OK',
                        'sedang_diproses'                => 'Sedang Diproses',
                        'selesai'                        => 'Selesai',
                        'ditolak'                        => 'Ditolak',
                        default                          => ucfirst(str_replace('_', ' ', $state)),
                    }),

                TextColumn::make('created_at')
                    ->label('Tgl Pesan')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
                DeleteAction::make(),
            ])
            ->bulkActions([]);
    }
}
