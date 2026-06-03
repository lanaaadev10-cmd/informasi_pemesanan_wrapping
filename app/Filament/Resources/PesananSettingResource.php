<?php

namespace App\Filament\Resources;

use Filament\Resources\Resource;
use App\Filament\Resources\PesananSetting\Pages\EditPesananSetting;

class PesananSettingResource extends Resource
{
    protected static ?string $label = 'Halaman Pesanan';
    protected static ?string $pluralLabel = 'Halaman Pesanan';
    protected static ?string $navigationLabel = 'Halaman Pesanan';
    protected static string|null|\UnitEnum $navigationGroup = 'Website';
    protected static ?int $navigationSort = 7;

    public static function getPages(): array
    {
        return [
            'index' => EditPesananSetting::route('/'),
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
