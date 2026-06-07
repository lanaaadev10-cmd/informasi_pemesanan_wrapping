<?php

namespace App\Filament\Resources\RiwayatPesanans;

use App\Models\Pesanan;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\Action;

class RiwayatPesananResource extends Resource
{
    protected static ?string $model = Pesanan::class;

    protected static ?string $label = 'Kelola Riwayat Pemesanan';
    protected static ?string $pluralLabel = 'Kelola Riwayat Pemesanan';
    protected static ?string $navigationLabel = 'Kelola Riwayat Pemesanan';
    protected static string|null|\UnitEnum $navigationGroup = 'Kelola User Dashboard';
    protected static ?int $navigationSort = 3;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_pesanan')
                    ->label('ID Pesanan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('user.name')
                    ->label('Pelanggan')
                    ->sortable(),
                TextColumn::make('total_harga')
                    ->label('Total Bayar')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'selesai' => 'success',
                        'dibayar' => 'success',
                        'dibatalkan' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'selesai' => 'Selesai',
                        'dibayar' => 'Sudah Dibayar',
                        'dibatalkan' => 'Dibatalkan',
                    ]),
            ])
            ->actions([
                Action::make('cetakInvoice')
                    ->label('Struk')
                    ->icon('heroicon-o-printer')
                    ->color('info')
                    ->url(fn (Pesanan $record): string => route('pesanan.invoice', $record->id_pesanan))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\RiwayatPesanans\Pages\ListRiwayatPesanans::route('/'),
        ];
    }

    public static function canCreate(): bool { return false; }
    public static function canEdit($record): bool { return false; }
    public static function canDelete($record): bool { return false; }
}
