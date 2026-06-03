<?php

namespace App\Filament\Resources;

use Filament\Resources\Resource;
use App\Filament\Resources\Berandas\Pages\EditBeranda;

class BerandaResource extends Resource
{
    protected static ?string $label = 'Edit Beranda';
    protected static ?string $pluralLabel = 'Edit Beranda';
    protected static ?string $navigationLabel = 'Edit Beranda';
    protected static string|null|\UnitEnum $navigationGroup = 'Halaman Website';
    protected static ?int $navigationSort = 1;

    public static function getPages(): array
    {
        return [
            'index' => EditBeranda::route('/'),
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
