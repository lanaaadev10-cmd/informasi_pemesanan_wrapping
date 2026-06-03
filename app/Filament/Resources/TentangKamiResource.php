<?php

namespace App\Filament\Resources;

use Filament\Resources\Resource;
use App\Filament\Resources\TentangKamis\Pages\EditTentangKami;

class TentangKamiResource extends Resource
{
    protected static ?string $label = 'Edit Tentang Kami';
    protected static ?string $pluralLabel = 'Edit Tentang Kami';
    protected static ?string $navigationLabel = 'Edit Tentang Kami';
    protected static string|null|\UnitEnum $navigationGroup = 'Halaman Website';
    protected static ?int $navigationSort = 5;

    public static function getPages(): array
    {
        return [
            'index' => EditTentangKami::route('/'),
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
