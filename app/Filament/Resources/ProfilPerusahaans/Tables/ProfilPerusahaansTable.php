<?php

namespace App\Filament\Resources\ProfilPerusahaans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;

class ProfilPerusahaansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // Baru saya tambah: Kolom Logo dengan bentuk lingkaran (circular)
                ImageColumn::make('logo')
                    ->label('Logo')
                    ->circular(), // Membuat preview logo berbentuk bulat

                // Baru saya tambah: Kolom nama dengan teks tebal dan deskripsi email di bawahnya
                TextColumn::make('nama_perusahaan')
                    ->label('Perusahaan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold') // Teks jadi tebal
                    ->description(fn ($record) => $record->email), // Menampilkan email sebagai teks kecil di bawah nama

                // Baru saya tambah: Kolom kontak dengan fitur Copy-to-Clipboard
                TextColumn::make('nomor_telepon')
                    ->label('Kontak')
                    ->icon('heroicon-m-phone')
                    ->copyable() // Admin bisa klik untuk copy nomor telepon
                    ->searchable(),

                TextColumn::make('alamat')
                    ->label('Alamat')
                    ->limit(30) // Membatasi panjang teks agar tabel tidak berantakan
                    ->tooltip(fn ($record) => $record->alamat) // Teks lengkap muncul saat mouse diarahkan (hover)
                    ->searchable(),

                // Baru saya tambah: Waktu update dengan format relatif (contoh: 2 jam yang lalu)
                TextColumn::make('updated_at')
                    ->label('Pembaruan')
                    ->dateTime()
                    ->since() // Format "time ago"
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                // Baru saya tambah: Tombol edit dengan warna kuning agar lebih menonjol
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
