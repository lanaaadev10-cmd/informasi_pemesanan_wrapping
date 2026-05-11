<?php

namespace App\Filament\Resources\ProfilPerusahaans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;

/**
 * Class ProfilPerusahaansTable
 * Mengatur tampilan tabel daftar profil perusahaan di dashboard admin.
 */
class ProfilPerusahaansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // Kolom Logo
                ImageColumn::make('logo')
                    ->label('Branding')
                    ->circular(),

                // Kolom Nama Perusahaan
                TextColumn::make('nama_perusahaan')
                    ->label('Nama Perusahaan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record) => $record->email),

                // Kolom Kontak
                TextColumn::make('nomor_telepon')
                    ->label('Kontak')
                    ->icon('heroicon-m-phone')
                    ->copyable() // Memudahkan admin copy nomor
                    ->searchable(),

                // Kolom Alamat
                TextColumn::make('alamat')
                    ->label('Lokasi')
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->alamat)
                    ->searchable(),

                // Timestamp
                TextColumn::make('updated_at')
                    ->label('Terakhir Diperbarui')
                    ->dateTime()
                    ->since() // Menampilkan waktu seperti "2 days ago" agar lebih modern
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()
                    ->button()
                    ->color('warning')
                    ->icon('heroicon-m-pencil-square'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
