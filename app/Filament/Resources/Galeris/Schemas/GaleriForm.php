<?php

namespace App\Filament\Resources\Galeris\Schemas;

use Filament\Forms\Components\Select;
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

            Select::make('kategori')
                ->label('Kategori')
                ->options([
                    'matte'  => 'Variasi mobil',
                    'glossy' => 'Kaca film',
                    'satin'  => 'Audio mobil',
                ])
                ->placeholder('Pilih kategori galeri')
                ->searchable()
                ->columnSpan(1)
                ->helperText('Kategori filter tampilan galeri di landing page.'),

            TextInput::make('jenis')
                ->label('Jenis Pekerjaan (Opsional)')
                ->placeholder('Contoh: Full Wrapping, Kaca Depan, Audio Set')
                ->maxLength(100)
                ->columnSpan(1),

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
