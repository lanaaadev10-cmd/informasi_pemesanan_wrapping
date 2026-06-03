<?php

namespace App\Filament\Resources;

use Filament\Resources\Resource;
use App\Filament\Resources\TentangKamiSetting\Pages\EditTentangKamiSetting;

class TentangKamiSettingResource extends Resource
{
    protected static ?string $label = 'Halaman Tentang Kami';
    protected static ?string $pluralLabel = 'Halaman Tentang Kami';
    protected static ?string $navigationLabel = 'Halaman Tentang Kami';
    protected static string|null|\UnitEnum $navigationGroup = 'Website';
    protected static ?int $navigationSort = 3;

    public static function getPages(): array
    {
        return [
            'index' => EditTentangKamiSetting::route('/'),
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
