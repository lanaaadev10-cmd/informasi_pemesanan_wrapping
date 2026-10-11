<?php

namespace App\Filament\Resources\Layanans\Schemas;

use Filament\Forms;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;

class LayananForm
{
    public static function schema(): array
    {
        return [
            Section::make('Informasi Dasar Layanan')
                ->description('Nama layanan, jenis kendaraan, tipe pengerjaan, dan tarif.')
                ->icon('heroicon-o-information-circle')
                ->schema([
                    Grid::make(3)
                        ->schema([
                            Forms\Components\TextInput::make('nama_layanan')
                                ->label('Nama Layanan / Paket *')
                                ->required()
                                ->maxLength(255)
                                ->placeholder('Contoh: Full Wrap Motor Maxi (Bahan Oracal)')
                                ->helperText('Nama layanan yang akan tampil di katalog dan form booking pelanggan.'),

                            Forms\Components\Select::make('kategori')
                                ->label('Kategori Kendaraan *')
                                ->options([
                                    'mobil' => '🚗 Mobil (Roda 4)',
                                    'motor' => '🛵 Sepeda Motor (Roda 2)',
                                ])
                                ->required()
                                ->helperText('Menentukan di tab kendaraan mana layanan ini akan muncul di web.'),

                            Forms\Components\Select::make('tipe_paket')
                                ->label('Tipe Pengerjaan *')
                                ->options([
                                    'wrapping'    => 'Full Body Wrapping',
                                    'partial'     => 'Wrapping Parsial / Atap Panoramic',
                                    'jasa-pasang' => 'Jasa Pasang (Bawa Bahan Sendiri)',
                                    'window-film' => 'Kaca Film Tolak Panas',
                                    'lighting'    => 'Lampu Bi-LED',
                                    'audio'       => 'Audio Mobil',
                                    'striping'    => 'Striping & Decal',
                                ])
                                ->default('wrapping')
                                ->required()
                                ->helperText('Pengelompokan pengerjaan fisik pada kendaraan.'),
                        ]),

                    Grid::make(3)
                        ->schema([
                            Forms\Components\Select::make('jenis_layanan')
                                ->label('Model Penyediaan Bahan *')
                                ->options([
                                    'paket'       => '📦 Paket Lengkap All-In (Bahan Toko + Garansi)',
                                    'jasa_pasang' => '✂️ Jasa Pasang Saja (Pelanggan Bawa Bahan Sendiri)',
                                    'custom'      => '🎨 Custom Project (Penawaran Khusus / Livery)',
                                ])
                                ->default('paket')
                                ->required()
                                ->helperText('Menentukan apakah material stiker disediakan bengkel atau dibawa sendiri oleh pelanggan.'),

                            Forms\Components\Select::make('tipe_layanan')
                                ->label('Model Penetapan Harga *')
                                ->options([
                                    'fix'    => 'Fixed Price (Harga Pasti / Paket Standar)',
                                    'custom' => 'Custom Quote (Estimasi / Konsultasi WA)',
                                ])
                                ->default('fix')
                                ->required()
                                ->live()
                                ->helperText('Fixed Price langsung bisa dipesan via Booking; Custom Quote diarahkan ke konsultasi WA.'),

                            Forms\Components\TextInput::make('estimasi_waktu')
                                ->label('Estimasi Lama Pengerjaan')
                                ->placeholder('Contoh: 1-2 Hari Kerja / 2-3 Jam')
                                ->helperText('Perkiraan lama waktu pengerjaan di workshop.'),
                        ]),

                    Grid::make(2)
                        ->schema([
                            Forms\Components\TextInput::make('harga')
                                ->label('Tarif / Harga Layanan (Rp)')
                                ->numeric()
                                ->prefix('Rp')
                                ->placeholder('Contoh: 450000')
                                ->required(fn (Get $get) => $get('tipe_layanan') === 'fix')
                                ->helperText('Wajib diisi jika Fixed Price. Jika Custom Quote, boleh dikosongkan.'),

                            Forms\Components\Toggle::make('is_active')
                                ->label('Status Aktif Layanan')
                                ->default(true)
                                ->helperText('Jika diaktifkan, layanan akan langsung tampil di katalog website dan formulir booking.'),
                        ]),
                ]),

            Section::make('Deskripsi & Foto Hasil Pekerjaan')
                ->description('Penjelasan detail layanan dan foto contoh pengerjaan di workshop.')
                ->icon('heroicon-o-document-text')
                ->collapsible()
                ->schema([
                    Forms\Components\RichEditor::make('deskripsi')
                        ->label('Deskripsi Lengkap Layanan *')
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
                        ->placeholder('Jelaskan detail spesifikasi bahan, ketahanan, keunggulan, dan kecocokan tipe kendaraan...')
                        ->helperText('Deskripsi ini akan dibaca oleh calon pelanggan di katalog website.'),

                    Forms\Components\FileUpload::make('foto_contoh')
                        ->label('Foto Hasil Pengerjaan')
                        ->image()
                        ->disk('public')
                        ->directory('layanan/admin')
                        ->imagePreviewHeight(140)
                        ->helperText('Upload foto contoh hasil kerja. Ukuran rekomendasi: 1200x800px.'),
                ]),

            Section::make('Keunggulan & Fitur Paket')
                ->description('Poin-poin keunggulan yang akan ditampilkan dalam bentuk checklist di kartu katalog.')
                ->icon('heroicon-o-star')
                ->collapsible()
                ->schema([
                    Forms\Components\Repeater::make('fitur')
                        ->label('Daftar Poin Keunggulan')
                        ->schema([
                            Forms\Components\TextInput::make('nama_fitur')
                                ->label('Keunggulan / Fitur *')
                                ->placeholder('Contoh: Garansi Resmi Daya Rekat 1 Tahun')
                                ->required()
                                ->columnSpanFull()
                                ->helperText('Poin keunggulan singkat (misal: Bahan Oracal Jerman, Pemasangan Knifeless).'),
                        ])
                        ->addActionLabel('+ Tambah Poin Keunggulan')
                        ->defaultItems(0)
                        ->minItems(1)
                        ->helperText('Minimal 1 poin keunggulan agar kartu katalog tampil menarik.'),
                ]),
        ];
    }
}
