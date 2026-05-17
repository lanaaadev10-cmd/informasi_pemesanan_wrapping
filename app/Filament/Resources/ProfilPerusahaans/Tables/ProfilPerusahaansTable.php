<?php

namespace App\Filament\Resources\ProfilPerusahaans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;

/**
 * Table Schema untuk ProfilPerusahaan Resource.
 *
 * Menampilkan daftar profil perusahaan dengan kolom:
 * Logo, Nama (+ email), Kontak, Alamat, Sosmed, dan Waktu Update.
 */
class ProfilPerusahaansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // Logo berbentuk lingkaran
                ImageColumn::make('logo')
                    ->label('Logo')
                    ->circular()
                    ->defaultImageUrl(fn () => 'https://ui-avatars.com/api/?name=P&background=f97316&color=fff&size=64'),

                // Nama perusahaan (tebal) + email di bawahnya
                TextColumn::make('nama_perusahaan')
                    ->label('Perusahaan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record) => $record->email),

                // Nomor kontak dengan fitur copy
                TextColumn::make('nomor_telepon')
                    ->label('Kontak')
                    ->icon('heroicon-m-phone')
                    ->copyable()
                    ->searchable(),

                // Alamat (dipotong, lengkap di tooltip)
                TextColumn::make('alamat')
                    ->label('Alamat')
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->alamat)
                    ->searchable(),

                // Status sosmed (badge jumlah yang terisi)
                TextColumn::make('id')
                    ->label('Sosmed')
                    ->formatStateUsing(function ($record) {
                        $count = collect([
                            $record->instagram_url,
                            $record->facebook_url,
                            $record->tiktok_url,
                            $record->whatsapp_url,
                        ])->filter()->count();

                        return "{$count}/4 terisi";
                    })
                    ->badge()
                    ->color(fn ($record) => collect([
                        $record->instagram_url,
                        $record->facebook_url,
                        $record->tiktok_url,
                        $record->whatsapp_url,
                    ])->filter()->count() >= 3 ? 'success' : 'warning'),

                // Waktu terakhir diperbarui
                TextColumn::make('updated_at')
                    ->label('Terakhir Diubah')
                    ->dateTime()
                    ->since()
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
            ])
            ->emptyStateHeading('Belum ada Profil Perusahaan')
            ->emptyStateDescription('Klik tombol "Baru" di atas untuk membuat profil perusahaan pertama.')
            ->emptyStateIcon('heroicon-o-building-office');
    }
}
