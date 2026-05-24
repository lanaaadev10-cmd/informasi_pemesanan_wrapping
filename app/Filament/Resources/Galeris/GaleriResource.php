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

/**
 * GaleriResource
 * Digunakan untuk mengelola portofolio atau dokumentasi pekerjaan (Galeri).
 */
class GaleriResource extends Resource
{
    // Model yang digunakan: Galeri
    protected static ?string $model = Galeri::class;

    protected static ?string $navigationLabel = 'Edit Galeri Pekerjaan';
    protected static ?string $pluralLabel = 'Edit Galeri Pekerjaan';
    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-photo';
    protected static string|null|\UnitEnum $navigationGroup = 'Kelola Konten Website';
    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'judul';

    /**
     * Konfigurasi Formulir Galeri
     */
    public static function form(Schema $schema): Schema
    {
        return GaleriForm::configure($schema);
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

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasRole('admin') ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->hasRole('admin') ?? false;
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return auth()->user()?->hasRole('admin') ?? false;
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return auth()->user()?->hasRole('admin') ?? false;
    }
}
