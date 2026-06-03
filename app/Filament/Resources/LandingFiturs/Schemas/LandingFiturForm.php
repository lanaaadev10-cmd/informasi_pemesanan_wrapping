<?php

namespace App\Filament\Resources\LandingFiturs\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LandingFiturForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Konten Fitur')
                    ->description('Judul, deskripsi, dan ikon fitur yang ditampilkan di halaman beranda.')
                    ->icon('heroicon-o-star')
                    ->schema([
                        TextInput::make('judul')
                            ->label('Judul *')
                            ->required()
                            ->maxLength(255)
                            ->helperText('Judul fitur yang akan ditampilkan di halaman beranda.'),
                        Textarea::make('deskripsi')
                            ->label('Deskripsi *')
                            ->required()
                            ->rows(4)
                            ->helperText('Deskripsi singkat fitur ini.'),
                        Grid::make(2)
                            ->schema([
                                TextInput::make('ikon')
                                    ->label('Ikon')
                                    ->maxLength(255)
                                    ->placeholder('Contoh: ph ph-sketch-logo')
                                    ->helperText('Nama kelas ikon dari Phosphor Icons.'),
                                FileUpload::make('gambar')
                                    ->label('Gambar')
                                    ->image()
                                    ->disk('public')
                                    ->directory('landing-fitur')
                                    ->imagePreviewHeight(140)
                                    ->helperText('Upload gambar ilustrasi fitur. Ukuran rekomendasi: 800x600px.'),
                            ]),
                    ]),

                Section::make('Pengaturan Tampilan')
                    ->description('Urutan tampilan dan status aktif fitur.')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->collapsible()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('urutan')
                                    ->label('Urutan')
                                    ->numeric()
                                    ->default(0)
                                    ->helperText('Urutan tampilan fitur (semakin kecil semakin atas).'),
                                Toggle::make('is_aktif')
                                    ->label('Aktif?')
                                    ->default(true)
                                    ->helperText('Jika aktif, fitur akan ditampilkan di halaman beranda.'),
                            ]),
                    ]),
            ]);
    }
}
