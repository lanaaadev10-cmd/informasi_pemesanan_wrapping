<?php

namespace App\Filament\Resources\ProfilPerusahaans\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Schemas\Schema;

/**
 * Class ProfilPerusahaanForm
 * Digunakan untuk mengatur struktur form pada CMS Filament untuk Profil Perusahaan.
 */
class ProfilPerusahaanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Section 1: Informasi Utama
                Section::make('Informasi Utama')
                    ->description('Atur nama dan deskripsi utama perusahaan Anda.')
                    ->aside()
                    ->schema([
                        TextInput::make('nama_perusahaan')
                            ->label('Nama Perusahaan')
                            ->placeholder('Contoh: Dantie Sticker')
                            ->required()
                            ->maxLength(255)
                            ->prefixIcon('heroicon-m-building-office'),

                        Textarea::make('deskripsi')
                            ->label('Deskripsi Perusahaan')
                            ->placeholder('Tuliskan visi atau keunggulan perusahaan...')
                            ->required()
                            ->rows(5)
                            ->columnSpanFull(),
                    ]),

                // Section 2: Kontak & Lokasi
                Section::make('Kontak & Lokasi')
                    ->description('Pastikan pelanggan bisa menghubungi dan menemukan lokasi Anda.')
                    ->aside()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('email')
                                    ->label('Alamat Email')
                                    ->email()
                                    ->required()
                                    ->prefixIcon('heroicon-m-envelope'),

                                TextInput::make('nomor_telepon')
                                    ->label('Nomor WhatsApp/Telepon')
                                    ->tel()
                                    ->required()
                                    ->prefixIcon('heroicon-m-phone'),
                            ]),

                        TextInput::make('alamat')
                            ->label('Alamat Lengkap')
                            ->placeholder('Jl. Contoh No. 123, Kota...')
                            ->required()
                            ->prefixIcon('heroicon-m-map-pin'),

                        TextInput::make('maps_url')
                            ->label('Google Maps Embed URL')
                            ->placeholder('https://www.google.com/maps/embed?pb=...')
                            ->helperText('Dapatkan URL ini dari menu Share > Embed Map di Google Maps.')
                            ->prefixIcon('heroicon-m-globe-alt'),
                    ]),

                // Section 3: Branding
                Section::make('Branding')
                    ->description('Upload logo perusahaan untuk ditampilkan di website.')
                    ->aside()
                    ->schema([
                        FileUpload::make('logo')
                            ->label('Logo Perusahaan')
                            ->image()
                            ->imageEditor()
                            ->circleShape() // Membuat preview logo berbentuk lingkaran agar modern
                            ->disk('public')
                            ->directory('logos')
                            ->visibility('public')
                            ->maxSize(2048) // Maksimal 2MB untuk optimasi loading
                            ->helperText('Gunakan gambar PNG transparan untuk hasil terbaik (Maks. 2MB).')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
