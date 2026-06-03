<?php

namespace App\Filament\Resources\Testimonis\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TestimoniForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Pemberi Testimoni')
                    ->description('Nama, jabatan, dan foto pemberi testimoni.')
                    ->icon('heroicon-o-user')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('nama')
                                    ->label('Nama Lengkap *')
                                    ->required()
                                    ->maxLength(255)
                                    ->helperText('Nama lengkap pemberi testimoni.'),
                                TextInput::make('jabatan')
                                    ->label('Jabatan')
                                    ->maxLength(255)
                                    ->helperText('Contoh: CEO Toko Maju Jaya.'),
                            ]),
                        FileUpload::make('foto')
                            ->label('Foto')
                            ->image()
                            ->disk('public')
                            ->directory('testimoni')
                            ->imagePreviewHeight(140)
                            ->helperText('Upload foto pemberi testimoni. Ukuran rekomendasi: 400x400px.'),
                    ]),

                Section::make('Isi Testimoni & Penilaian')
                    ->description('Teks testimoni dan rating bintang.')
                    ->icon('heroicon-o-chat-bubble-bottom-center')
                    ->collapsible()
                    ->schema([
                        Textarea::make('isi_testimoni')
                            ->label('Isi Testimoni *')
                            ->required()
                            ->rows(4)
                            ->helperText('Teks testimoni yang akan ditampilkan.'),
                        Grid::make(2)
                            ->schema([
                                Select::make('rating')
                                    ->label('Rating *')
                                    ->options([
                                        1 => '1 - Sangat Kurang',
                                        2 => '2 - Kurang',
                                        3 => '3 - Cukup',
                                        4 => '4 - Baik',
                                        5 => '5 - Sangat Baik',
                                    ])
                                    ->default(5)
                                    ->helperText('Penilaian bintang dari pemberi testimoni.'),
                                Toggle::make('is_tampilkan')
                                    ->label('Tampilkan di Website?')
                                    ->default(true)
                                    ->helperText('Jika aktif, testimoni akan ditampilkan di halaman website.'),
                            ]),
                    ]),
            ]);
    }
}
