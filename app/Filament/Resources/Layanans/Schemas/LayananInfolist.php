<?php

namespace App\Filament\Resources\Layanans\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LayananInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(12)->schema([
                    // Kolom Kiri (8/12): Informasi Inti Layanan & Keunggulan
                    Group::make([
                        Section::make('Informasi Paket Layanan')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                TextEntry::make('nama_layanan')
                                    ->label('Nama Layanan')
                                    ->weight('bold')
                                    ->size('lg'),

                                Grid::make(3)->schema([
                                    TextEntry::make('kategori')
                                        ->label('Kategori Kendaraan')
                                        ->badge()
                                        ->color('info')
                                        ->formatStateUsing(fn ($state) => ucfirst($state ?? '-')),

                                    TextEntry::make('tipe_layanan')
                                        ->label('Tipe Penawaran')
                                        ->badge()
                                        ->color(fn ($state) => $state === 'fix' ? 'success' : 'warning')
                                        ->formatStateUsing(fn ($state) => $state === 'fix' ? 'Fixed Price' : 'Custom Quote'),

                                    TextEntry::make('estimasi_waktu')
                                        ->label('Estimasi Pengerjaan')
                                        ->icon('heroicon-o-clock')
                                        ->placeholder('-'),
                                ]),

                                TextEntry::make('harga')
                                    ->label('Harga Paket')
                                    ->money('IDR')
                                    ->color('primary')
                                    ->weight('bold')
                                    ->placeholder('Custom Quote / Negosiasi'),

                                TextEntry::make('deskripsi')
                                    ->label('Deskripsi Layanan')
                                    ->html()
                                    ->columnSpanFull()
                                    ->placeholder('-'),
                            ]),

                        Section::make('Keunggulan & Fitur Paket')
                            ->icon('heroicon-o-star')
                            ->schema([
                                TextEntry::make('fitur')
                                    ->label('')
                                    ->bulleted()
                                    ->formatStateUsing(function ($state) {
                                        if (is_array($state)) {
                                            return $state['nama_fitur'] ?? ($state[0] ?? '-');
                                        }
                                        return $state;
                                    })
                                    ->placeholder('Belum ada fitur / keunggulan ditambahkan'),
                            ]),
                    ])->columnSpan(8),

                    // Kolom Kanan (4/12): Foto & Waktu Pembuatan
                    Group::make([
                        Section::make('Foto Contoh Pekerjaan')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                ImageEntry::make('foto_contoh')
                                    ->label('')
                                    ->disk('public')
                                    ->square()
                                    ->placeholder('Belum ada foto yang diunggah'),
                            ]),

                        Section::make('Metadata Sistem')
                            ->icon('heroicon-o-clock')
                            ->schema([
                                TextEntry::make('created_at')
                                    ->label('Dibuat Pada')
                                    ->dateTime('d M Y H:i'),
                                TextEntry::make('updated_at')
                                    ->label('Terakhir Diperbarui')
                                    ->dateTime('d M Y H:i'),
                            ]),
                    ])->columnSpan(4),
                ]),
            ]);
    }
}
