<?php

namespace App\Filament\Resources;

use Filament\Resources\Resource;
use App\Filament\Resources\KeranjangCheckout\Pages\EditKeranjangCheckout;

class KeranjangCheckoutResource extends Resource
{
    protected static ?string $label = 'Kelola Keranjang';
    protected static ?string $pluralLabel = 'Kelola Keranjang';
    protected static ?string $navigationLabel = 'Kelola Keranjang';
    protected static string|null|\UnitEnum $navigationGroup = 'Kelola User Dashboard';
    protected static ?int $navigationSort = 1;

    public static function getPages(): array
    {
        return [
            'index' => EditKeranjangCheckout::route('/'),
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
