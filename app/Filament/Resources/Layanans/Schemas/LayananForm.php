<?php

namespace App\Filament\Resources\Layanans\Schemas;

use Filament\Forms;
use Filament\Schemas\Schema;

class LayananForm
{
    public static function schema(): array
    {
        return [
            // [TAMBAHAN FUNGSI] Mengizinkan Admin mengunggah Foto Utama Layanan
            Forms\Components\FileUpload::make('foto_contoh')
                ->label('Foto')
                ->image()
                ->disk('public') // MEMASTIKAN file masuk ke folder yang bisa diakses publik
                ->directory('layanan/admin')
                ->columnSpanFull(),

            Forms\Components\TextInput::make('nama_layanan')
                ->label('Nama Layanan')
                ->required()
                ->maxLength(255),

            Forms\Components\Select::make('tipe_layanan')
                ->label('Kategori Harga')
                ->options([
                    'fix'    => 'Fix (Harga Tetap)',
                    'custom' => 'Custom (Nego)',
                ])
                ->required(),

            Forms\Components\TextInput::make('harga')
                ->label('Harga (Rp)')
                ->numeric()
                ->prefix('Rp')
                ->required(),

            // [TAMBAHAN FUNGSI] Menentukan paket ini untuk Mobil, Motor, atau Stiker agar bisa difilter di depan
            Forms\Components\Select::make('kategori')
                ->label('Kategori Kendaraan/Jasa')
                ->options([
                    'mobil' => 'Mobil',
                    'motor' => 'Sepeda Motor',
                    'stiker' => 'Custom Stiker',
                ])
                ->required(),

            Forms\Components\TextInput::make('estimasi_waktu')
                ->label('Estimasi Waktu')
                ->placeholder('Contoh: 3 hari'),

            Forms\Components\Textarea::make('deskripsi')
                ->label('Deskripsi Singkat')
                ->columnSpanFull(),

            // [TAMBAHAN FUNGSI] Sistem "Daftar Keunggulan" yang bisa ditambah-tambah sendiri di Admin
            Forms\Components\Repeater::make('fitur')
                ->label('Daftar Keunggulan Paket')
                ->schema([
                    Forms\Components\TextInput::make('nama_fitur')
                        ->label('Nama Keunggulan')
                        ->placeholder('Contoh: Garansi 6 Bulan')
                        ->required(),
                ])
                ->columnSpanFull()
                ->columns(1)
                ->addActionLabel('Tambah Keunggulan Baru'),
        ];
    }
}