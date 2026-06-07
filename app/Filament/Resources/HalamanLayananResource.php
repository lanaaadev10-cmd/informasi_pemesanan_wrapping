<?php

namespace App\Filament\Resources;

use Filament\Resources\Resource;
use App\Filament\Resources\HalamanLayanan\Pages\EditHalamanLayanan;

class HalamanLayananResource extends Resource
{
    protected static ?string $label = 'Kelola Halaman Layanan';
    protected static ?string $pluralLabel = 'Kelola Halaman Layanan';
    protected static ?string $navigationLabel = 'Kelola Halaman Layanan';
    protected static string|null|\UnitEnum $navigationGroup = 'Kelola Landing Page';
    protected static ?int $navigationSort = 2;

    public static function getPages(): array
    {
        return [
            'index' => EditHalamanLayanan::route('/'),
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
