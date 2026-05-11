<?php

namespace App\Filament\Resources\Galeris;

use App\Filament\Resources\Galeris\Pages\CreateGaleri;
use App\Filament\Resources\Galeris\Pages\EditGaleri;
use App\Filament\Resources\Galeris\Pages\ListGaleris;
use App\Filament\Resources\Galeris\Schemas\GaleriForm;
use App\Filament\Resources\Galeris\Tables\GalerisTable;
use App\Models\Galeri;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DatePicker;

/**
 * GaleriResource
 * Digunakan untuk mengelola portofolio atau dokumentasi pekerjaan (Galeri).
 */
class GaleriResource extends Resource
{
    // Model yang digunakan: Galeri
    protected static ?string $model = Galeri::class;

    // Nama menu di Sidebar
    protected static ?string $navigationLabel = 'Galeri Pekerjaan';

    // Icon sidebar: Foto/Gambar
    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-photo';

    // Judul record saat dicari
    protected static ?string $recordTitleAttribute = 'judul';

    /**
     * Konfigurasi Formulir Galeri
     */
    public static function form(Schema $schema): Schema
    {
        return $schema->schema([

            TextInput::make('judul')
                ->label('Judul Foto/Pekerjaan')
                ->required(),

            FileUpload::make('foto')
                ->label('Unggah Foto')
                ->image()
                ->directory('galeri') // folder penyimpanan
                ->disk('public')
                ->maxSize(10240) // 10MB
                ->helperText('Maksimal 10MB')
                ->required(),

            Textarea::make('deskripsi')
                ->label('Deskripsi Singkat'),

            DatePicker::make('tanggal_upload')
                ->label('Tanggal Pekerjaan')
                ->required(),

        ]);
    }

    /**
     * Konfigurasi Tabel Galeri
     */
    public static function table(Table $table): Table
    {
        return GalerisTable::configure($table);
    }

    /**
     * Rute Halaman
     */
    public static function getPages(): array
    {
        return [
            'index' => ListGaleris::route('/'),
            'create' => CreateGaleri::route('/create'),
            'edit' => EditGaleri::route('/{record}/edit'),
        ];
    }
}
