<?php

namespace App\Filament\Resources\Galeris\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class GaleriForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Dasar')
                ->description('Informasi utama untuk galeri/portofolio')
                ->aside()
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('judul')
                            ->label('Judul Pekerjaan')
                            ->placeholder('Contoh: Sticker Custom Premium')
                            ->required()
                            ->maxLength(255)
                            ->prefixIcon('heroicon-m-pencil'),

                        TextInput::make('sub_judul')
                            ->label('Sub Judul (Opsional)')
                            ->placeholder('Detail tambahan atau kategori')
                            ->maxLength(255)
                            ->prefixIcon('heroicon-m-sparkles'),
                    ]),

                    Textarea::make('deskripsi')
                        ->label('Deskripsi Pekerjaan')
                        ->placeholder('Tuliskan detail dan cerita di balik pekerjaan ini...')
                        ->rows(5)
                        ->columnSpanFull(),
                ]),

            Section::make('Media')
                ->description('Upload foto dan konten visual')
                ->aside()
                ->schema([
                    FileUpload::make('foto')
                        ->label('Foto Utama')
                        ->image()
                        ->imageEditor()
                        ->directory('galeri')
                        ->disk('public')
                        ->maxSize(10240)
                        ->helperText('Format: JPG, PNG. Maks. 10MB')
                        ->required()
                        ->columnSpanFull(),
                ]),

            Section::make('Kategori & Penanda')
                ->description('Organisir galeri dengan kategori dan penanda khusus')
                ->aside()
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('kategori')
                            ->label('Kategori')
                            ->placeholder('Contoh: Sticker, Wrapping, Desain')
                            ->prefixIcon('heroicon-m-tag'),

                        TextInput::make('badge_text')
                            ->label('Teks Badge')
                            ->placeholder('Contoh: Featured, Best Seller')
                            ->helperText('Akan ditampilkan di sudut foto'),
                    ]),

                    Grid::make(2)->schema([
                        DatePicker::make('tanggal_upload')
                            ->label('Tanggal Upload')
                            ->required()
                            ->prefixIcon('heroicon-m-calendar'),

                        Toggle::make('is_featured')
                            ->label('Tampilkan sebagai Featured?')
                            ->helperText('Tampilkan di halaman utama galeri')
                            ->default(false),
                    ]),
                ]),
        ]);
    }
}
