<?php

namespace App\Filament\Resources\Galeris\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class GaleriForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('judul')
                ->label('Judul Pekerjaan')
                ->placeholder('Contoh: Sticker Custom Premium untuk Mobil Avanza')
                ->required()
                ->maxLength(255)
                ->columnSpan(2)
                ->helperText('Judul yang menarik untuk galeri/portofolio.'),

            TextInput::make('sub_judul')
                ->label('Sub Judul (Opsional)')
                ->placeholder('Contoh: Premium Design dengan Tinta Berkualitas')
                ->maxLength(255)
                ->columnSpan(1)
                ->helperText('Detail tambahan atau kategori jenis pekerjaan.'),

            Textarea::make('deskripsi')
                ->label('Deskripsi Pekerjaan')
                ->placeholder('Tuliskan detail cerita, proses, dan pencapaian...')
                ->rows(5)
                ->columnSpanFull(),

            FileUpload::make('foto')
                ->label('Foto Utama Galeri')
                ->image()
                ->imageEditor()
                ->directory('galeri')
                ->disk('public')
                ->maxSize(10240)
                ->required()
                ->columnSpanFull(),

            TextInput::make('badge_text')
                ->label('Teks Badge (Label)')
                ->placeholder('Contoh: Featured, Best Seller, Premium')
                ->columnSpan(1),

            DatePicker::make('tanggal_upload')
                ->label('Tanggal Upload')
                ->required()
                ->columnSpan(1),

            Toggle::make('is_featured')
                ->label('Tampilkan sebagai Featured?')
                ->columnSpan(1)
                ->default(false),
        ]);
    }
}
