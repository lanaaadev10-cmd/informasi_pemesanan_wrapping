<?php

namespace App\Filament\Resources\ProfilPerusahaans\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class ProfilPerusahaanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Baru saya tambah: Penggunaan Section agar form terbagi menjadi kategori yang rapi
                Section::make('Informasi Utama')
                    ->description('Atur nama dan deskripsi utama perusahaan Anda.')
                    ->aside() // Baru saya tambah: Membuat label section berada di samping agar hemat ruang
                    ->schema([
                        TextInput::make('nama_perusahaan')
                            ->label('Nama Perusahaan')
                            ->placeholder('Contoh: Dantie Sticker')
                            ->required()
                            ->maxLength(255)
                            ->prefixIcon('heroicon-m-building-office'), // Baru saya tambah: Icon visual untuk estetika

                        Textarea::make('deskripsi')
                            ->label('Deskripsi Perusahaan')
                            ->placeholder('Tuliskan visi atau keunggulan perusahaan...')
                            ->required()
                            ->rows(5)
                            ->columnSpanFull(),
                    ]),

                // Baru saya tambah: Section khusus Kontak agar tidak bercampur dengan info utama
                Section::make('Kontak & Lokasi')
                    ->description('Pastikan pelanggan bisa menghubungi dan menemukan lokasi Anda.')
                    ->aside()
                    ->schema([
                        // Baru saya tambah: Grid agar Email dan Telepon bisa berdampingan (2 kolom)
                        Grid::make(2)
                            ->schema([
                                TextInput::make('email')
                                    ->label('Alamat Email')
                                    ->email()
                                    ->required()
                                    ->prefixIcon('heroicon-m-envelope'), // Baru saya tambah: Icon amplop

                                TextInput::make('nomor_telepon')
                                    ->label('Nomor WhatsApp/Telepon')
                                    ->tel()
                                    ->required()
                                    ->prefixIcon('heroicon-m-phone'), // Baru saya tambah: Icon telepon
                            ]),

                        TextInput::make('alamat')
                            ->label('Alamat Lengkap')
                            ->placeholder('Jl. Contoh No. 123, Kota...')
                            ->required()
                            ->prefixIcon('heroicon-m-map-pin'), // Baru saya tambah: Icon pin lokasi

                        // Baru saya tambah: Field Maps URL agar Admin bisa mengatur lokasi Google Maps di Frontend
                        TextInput::make('maps_url')
                            ->label('Google Maps Embed URL')
                            ->placeholder('https://www.google.com/maps/embed?pb=...')
                            ->helperText('Buka Google Maps → Share → Embed a map → copy bagian src="..." nya aja')
                            ->prefixIcon('heroicon-m-globe-alt')
                            ->columnSpanFull(),
                    ]),

                // Baru saya tambah: Section khusus Branding untuk upload Logo
                Section::make('Branding')
                    ->description('Upload logo perusahaan untuk ditampilkan di website.')
                    ->aside()
                    ->schema([
                        FileUpload::make('logo')
                            ->label('Logo Perusahaan')
                            ->image()
                            ->imageEditor() // Baru saya tambah: Fitur crop/edit gambar sebelum diupload
                            ->disk('public')
                            ->directory('logos')
                            ->visibility('public')
                            ->maxSize(10240) // Baru saya ubah: Kapasitas lebih besar (10MB)
                            ->helperText('Gunakan gambar PNG transparan untuk hasil terbaik (Maks. 10MB).')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
