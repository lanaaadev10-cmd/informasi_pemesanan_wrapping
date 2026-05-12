<?php

namespace App\Filament\Resources\ProfilPerusahaans\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

use Filament\Forms\Components\FileUpload; 

class ProfilPerusahaanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_perusahaan')
                    ->required(),
                Textarea::make('deskripsi')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('alamat')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('nomor_telepon')
                    ->tel()
                    ->required(),
                FileUpload::make('logo')
                    ->image()
                    ->disk('public') // Simpan ke disk public
                    ->directory('logos') // Masukin ke folder logo agar rapi
                    ->visibility('public') // Pastikan file bisa diakses publik
                    ->maxSize(2048) // 2048 KB = 2 MB. Browser bakal nolak kalau jika filenya terlalu besar
                    ->helperText('Maksimal ukuran file adalah 2MB ya!'),
                Textarea::make('maps_url')
                    ->label('Google Maps Embed URL')
                    ->helperText('Buka Google Maps → Share → Embed a map → copy bagian src="..." nya aja')
                    ->nullable()
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }
}
