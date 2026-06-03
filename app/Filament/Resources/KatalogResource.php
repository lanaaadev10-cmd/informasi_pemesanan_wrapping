<?php

namespace App\Filament\Resources;

use Filament\Resources\Resource;
use App\Filament\Resources\Katalogs\Pages\EditKatalog;

class KatalogResource extends Resource
{
    protected static ?string $label = 'Halaman Katalog';
    protected static ?string $pluralLabel = 'Halaman Katalog';
    protected static ?string $navigationLabel = 'Halaman Katalog';
    protected static string|null|\UnitEnum $navigationGroup = 'Website';
    protected static ?int $navigationSort = 6;

    public static function getPages(): array
    {
        return [
            'index' => EditKatalog::route('/'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasRole('admin') ?? false;
    }

    public static function canCreate(): bool { return false; }
    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool { return false; }
    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return static::canViewAny();
    }
}
