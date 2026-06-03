<?php

namespace App\Filament\Resources\Galeris\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class GaleriForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Portofolio')
                    ->description('Judul, kategori, dan deskripsi pekerjaan.')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('judul')
                                    ->label('Judul Pekerjaan *')
                                    ->placeholder('Contoh: Sticker Custom Premium untuk Mobil Avanza')
                                    ->required()
                                    ->maxLength(255)
                                    ->helperText('Judul yang menarik untuk galeri/portofolio.'),
                                TextInput::make('sub_judul')
                                    ->label('Sub Judul')
                                    ->placeholder('Contoh: Premium Design dengan Tinta Berkualitas')
                                    ->maxLength(255)
                                    ->helperText('Detail tambahan atau kategori jenis pekerjaan.'),
                            ]),
                        Textarea::make('deskripsi')
                            ->label('Deskripsi Pekerjaan')
                            ->placeholder('Tuliskan detail cerita, proses, dan pencapaian di balik pekerjaan ini...')
                            ->rows(5)
                            ->helperText('Cerita lengkap tentang pekerjaan ini. Jelaskan proses, material yang digunakan, dan hasil akhir.'),
                    ]),

                Section::make('Visual & Kategori')
                    ->description('Foto utama, kategori, badge, dan tanggal upload.')
                    ->icon('heroicon-o-photo')
                    ->collapsible()
                    ->schema([
                        FileUpload::make('foto')
                            ->label('Foto Utama Galeri *')
                            ->image()
                            ->imageEditor()
                            ->directory('galeri')
                            ->disk('public')
                            ->maxSize(10240)
                            ->required()
                            ->helperText('Format: JPG, PNG, WebP. Maksimal 10MB. Ukuran rekomendasi: 1200x800px.'),
                        Grid::make(2)
                            ->schema([
                                TextInput::make('kategori')
                                    ->label('Kategori')
                                    ->placeholder('Contoh: Sticker, Wrapping, Vinyl Wrapping')
                                    ->helperText('Kategori pekerjaan. Digunakan untuk filter dan organisir di galeri.'),
                                TextInput::make('badge_text')
                                    ->label('Teks Badge (Label)')
                                    ->placeholder('Contoh: Featured, Best Seller, Premium')
                                    ->helperText('Label khusus yang akan ditampilkan di sudut foto.'),
                            ]),
                        Grid::make(2)
                            ->schema([
                                DatePicker::make('tanggal_upload')
                                    ->label('Tanggal Upload *')
                                    ->required()
                                    ->helperText('Tanggal pekerjaan ini selesai/dipublikasikan.'),
                                Toggle::make('is_featured')
                                    ->label('Tampilkan sebagai Featured?')
                                    ->default(false)
                                    ->helperText('Jika aktif, akan ditampilkan di bagian "Featured Works".'),
                            ]),
                    ]),
            ]);
    }
}
