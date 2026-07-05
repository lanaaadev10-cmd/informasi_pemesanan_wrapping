<?php

namespace App\Filament\Resources\Layanans\Schemas;

use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;

class LayananForm
{
    public static function schema(): array
    {
        return [
            Section::make('Informasi Dasar Paket')
                ->description('Nama, kategori, harga, dan estimasi waktu pengerjaan.')
                ->icon('heroicon-o-information-circle')
                ->schema([
                    Grid::make(2)
                        ->schema([
                            Forms\Components\TextInput::make('nama_layanan')
                                ->label('Nama Paket *')
                                ->required()
                                ->maxLength(255)
                                ->placeholder('Contoh: Precision Coating - Protection Ultimate')
                                ->helperText('Nama layanan yang akan ditampilkan di website. Gunakan nama yang deskriptif agar mudah dipahami customer.'),
                            Forms\Components\Select::make('kategori')
                                ->label('Kategori Kendaraan *')
                                ->options([
                                    'mobil' => 'Mobil',
                                    'motor' => 'Sepeda Motor',
                                    'stiker' => 'Custom Stiker',
                                ])
                                ->required()
                                ->helperText('Pilih jenis kendaraan/jasa. Digunakan untuk filter di halaman katalog.'),
                        ]),
                    Grid::make(2)
                        ->schema([
                            Forms\Components\Select::make('tipe_layanan')
                                ->label('Tipe Penawaran *')
                                ->options([
                                    'fix'    => 'Fixed Price (Harga Tetap)',
                                    'custom' => 'Custom Quote (Negosiasi)',
                                ])
                                ->required()
                                ->live()
                                ->helperText('Fixed Price: harga standar untuk semua customer. Custom Quote: harga negosiasi per customer.'),
                            Forms\Components\TextInput::make('estimasi_waktu')
                                ->label('Estimasi Waktu Pengerjaan')
                                ->placeholder('Contoh: 3 hari kerja')
                                ->helperText('Berapa lama waktu pengerjaan layanan ini.'),
                        ]),
                    Forms\Components\TextInput::make('harga')
                        ->label('Harga Paket (Rp) *')
                        ->numeric()
                        ->prefix('Rp ')
                        ->required()
                        ->visible(fn (Get $get) => $get('tipe_layanan') === 'fix')
                        ->helperText('Masukkan harga dalam rupiah. Hanya muncul jika tipe "Fixed Price" dipilih.'),
                ]),

            Section::make('Deskripsi & Visual')
                ->description('Detail layanan dan foto contoh hasil pekerjaan.')
                ->icon('heroicon-o-document-text')
                ->collapsible()
                ->schema([
                    Forms\Components\RichEditor::make('deskripsi')
                        ->label('Deskripsi Lengkap Paket *')
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
                        ->placeholder('Jelaskan detail layanan, manfaat, dan spesifikasi...')
                        ->helperText('Deskripsi detail yang akan ditampilkan di halaman detail layanan.'),
                    Forms\Components\FileUpload::make('foto_contoh')
                        ->label('Foto Layanan')
                        ->image()
                        ->disk('public')
                        ->directory('layanan/admin')
                        ->imagePreviewHeight(140)
                        ->helperText('Upload foto contoh hasil pekerjaan. Ukuran rekomendasi: 1200x800px.'),
                ]),

            Section::make('Keunggulan & Fitur Paket')
                ->description('Daftar keunggulan yang akan ditampilkan di halaman detail layanan.')
                ->icon('heroicon-o-star')
                ->collapsible()
                ->schema([
                    Forms\Components\Repeater::make('fitur')
                        ->label('Daftar Keunggulan')
                        ->schema([
                            Forms\Components\TextInput::make('nama_fitur')
                                ->label('Keunggulan *')
                                ->placeholder('Contoh: Garansi Resmi hingga 5 Tahun')
                                ->required()
                                ->columnSpanFull()
                                ->helperText('Nama keunggulan atau fitur dari paket layanan ini.'),
                        ])
                        ->addActionLabel('+ Tambah Keunggulan Baru')
                        ->defaultItems(0)
                        ->minItems(1)
                        ->helperText('Daftar keunggulan paket. Minimal 1 keunggulan harus ditambahkan.'),
                ]),
        ];
    }
}
