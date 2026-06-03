<?php

namespace App\Filament\Resources;

use Filament\Resources\Resource;
use App\Filament\Resources\Layout\Pages\EditLayout;

class LayoutResource extends Resource
{
    protected static ?string $label = 'Tampilan & Layout';
    protected static ?string $pluralLabel = 'Tampilan & Layout';
    protected static ?string $navigationLabel = 'Tampilan & Layout';
    protected static string|null|\UnitEnum $navigationGroup = 'Pengaturan';
    protected static ?int $navigationSort = 3;

    public static function getPages(): array
    {
        return [
            'index' => EditLayout::route('/'),
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
