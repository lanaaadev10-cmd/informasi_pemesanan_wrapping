<?php

namespace App\Filament\Resources;

use Filament\Resources\Resource;
use App\Filament\Resources\Contents\Pages\EditContent;

class ContentResource extends Resource
{
    protected static ?string $label = 'Edit Konten UI';
    protected static ?string $pluralLabel = 'Edit Konten UI';
    protected static ?string $navigationLabel = 'Edit Konten UI';
    protected static string|null|\UnitEnum $navigationGroup = 'Website';
    protected static ?int $navigationSort = 11;

    public static function getPages(): array
    {
        return [
            'index' => EditContent::route('/'),
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
