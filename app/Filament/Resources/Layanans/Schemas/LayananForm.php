<?php

namespace App\Filament\Resources\Layanans\Schemas;

use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;

class LayananForm
{
    public static function schema(): array
    {
        return [
            Forms\Components\FileUpload::make('foto_contoh')
                ->label('Foto Layanan')
                ->image()
                ->disk('public')
                ->directory('layanan/admin')
                ->imagePreviewHeight(140)
                ->columnSpanFull()
                ->helperText('Upload foto contoh hasil pekerjaan. Ukuran rekomendasi: 1200x800px. Berfungsi sebagai visual referensi di halaman katalog.'),

            Forms\Components\TextInput::make('nama_layanan')
                ->label('Nama Layanan')
                ->required()
                ->maxLength(255)
                ->columnSpan(2)
                ->placeholder('Contoh: Precision Coating - Protection Ultimate')
                ->helperText('Nama layanan yang akan ditampilkan di website. Gunakan nama yang deskriptif agar mudah dipahami customer.'),

            Forms\Components\Select::make('kategori')
                ->label('Kategori Kendaraan')
                ->options([
                    'mobil' => 'Mobil',
                    'motor' => 'Sepeda Motor',
                    'stiker' => 'Custom Stiker',
                ])
                ->required()
                ->columnSpan(1)
                ->helperText('Pilih jenis kendaraan/jasa. Digunakan untuk filter di halaman katalog.'),

            Forms\Components\Select::make('tipe_layanan')
                ->label('Tipe Penawaran')
                ->options([
                    'fix'    => 'Fixed Price (Harga Tetap)',
                    'custom' => 'Custom Quote (Negosiasi)',
                ])
                ->required()
                ->columnSpan(1)
                ->live()
                ->helperText('Fixed Price: harga standar untuk semua customer. Custom Quote: harga negosiasi per customer.'),

            Forms\Components\TextInput::make('harga')
                ->label('Harga Paket (Rp)')
                ->numeric()
                ->prefix('Rp ')
                ->required()
                ->columnSpan(1)
                ->visible(fn (Get $get) => $get('tipe_layanan') === 'fix')
                ->helperText('Masukkan harga dalam rupiah. Hanya muncul jika tipe "Fixed Price" dipilih.'),

            Forms\Components\TextInput::make('estimasi_waktu')
                ->label('Estimasi Waktu Pengerjaan')
                ->placeholder('Contoh: 3 hari kerja')
                ->columnSpan(1)
                ->helperText('Berapa lama waktu pengerjaan layanan ini. Contoh: 3 hari, 1 minggu, 5 jam kerja.'),

            Forms\Components\RichEditor::make('deskripsi')
                ->label('Deskripsi Lengkap Paket')
                ->toolbarButtons([
                    'bold',
                    'italic',
                    'strike',
                    'link',
                    'bulletList',
                    'orderedList',
                    'redo',
                    'undo',
                ])
                ->columnSpanFull()
                ->placeholder('Jelaskan detail layanan, manfaat, dan spesifikasi...')
                ->helperText('Deskripsi detail yang akan ditampilkan di halaman detail layanan. Gunakan formatting untuk memudahkan pembacaan: bold, italic, list, dll.'),

            Forms\Components\Repeater::make('fitur')
                ->label('Daftar Keunggulan & Fitur Paket')
                ->schema([
                    Forms\Components\TextInput::make('nama_fitur')
                        ->label('Keunggulan')
                        ->placeholder('Contoh: Garansi Resmi hingga 5 Tahun')
                        ->required()
                        ->columnSpanFull(),
                ])
                ->columnSpanFull()
                ->columns(1)
                ->addActionLabel('+ Tambah Keunggulan Baru')
                ->defaultItems(0)
                ->minItems(1)
                ->helperText('Daftar keunggulan paket (contoh: Garansi, Material Premium, Installer Bersertifikat, etc). Minimal 1 keunggulan harus ditambahkan. Akan ditampilkan di halaman detail layanan.'),
        ];
    }
}