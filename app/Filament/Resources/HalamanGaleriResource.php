<?php

namespace App\Filament\Resources;

use Filament\Resources\Resource;
use App\Filament\Resources\HalamanGaleri\Pages\EditHalamanGaleri;
use Illuminate\Database\Eloquent\Model;

class HalamanGaleriResource extends Resource
{
    protected static ?string $label = 'Edit Galeri';
    protected static ?string $pluralLabel = 'Edit Galeri';
    protected static ?string $navigationLabel = 'Edit Galeri';
    protected static string|null|\UnitEnum $navigationGroup = 'Website';
    protected static ?int $navigationSort = 5;

    public static function getPages(): array
    {
        return [
            'index' => EditHalamanGaleri::route('/'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasRole('admin') ?? false;
    }

    public static function canCreate(): bool { return false; }
    public static function canDelete(Model $record): bool { return false; }
    public static function canEdit(Model $record): bool
    {
        return static::canViewAny();
    }
}
