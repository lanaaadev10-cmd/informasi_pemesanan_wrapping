<?php

namespace App\Filament\Resources\ProfilPerusahaans;

use App\Filament\Resources\ProfilPerusahaans\Pages\CreateProfilPerusahaan;
use App\Filament\Resources\ProfilPerusahaans\Pages\EditProfilPerusahaan;
use App\Filament\Resources\ProfilPerusahaans\Pages\ListProfilPerusahaans;
use App\Filament\Resources\ProfilPerusahaans\Schemas\ProfilPerusahaanForm;
use App\Filament\Resources\ProfilPerusahaans\Tables\ProfilPerusahaansTable;
use App\Models\ProfilPerusahaan;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class ProfilPerusahaanResource extends Resource
{
    protected static ?string $model = ProfilPerusahaan::class;

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $label = 'Konfigurasi Website';
    protected static ?string $pluralLabel = 'Konfigurasi Website';
    protected static ?string $navigationLabel = 'Konfigurasi Website';
    protected static string|null|\UnitEnum $navigationGroup = 'SISTEM';
    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'nama_perusahaan';

    public static function form(Schema $schema): Schema
    { 
        return ProfilPerusahaanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProfilPerusahaansTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\KonfigurasiWebsite::route('/'),
            'create' => Pages\CreateProfilPerusahaan::route('/create'),
            'edit' => Pages\EditProfilPerusahaan::route('/{record}/edit'),
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
