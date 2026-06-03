<?php

namespace App\Filament\Resources;

use Filament\Resources\Resource;
use App\Filament\Resources\ProfilPage\Pages\EditProfilPage;

class ProfilPageResource extends Resource
{
    protected static ?string $label = 'Halaman Profil';
    protected static ?string $pluralLabel = 'Halaman Profil';
    protected static ?string $navigationLabel = 'Halaman Profil';
    protected static string|null|\UnitEnum $navigationGroup = 'Website';
    protected static ?int $navigationSort = 8;

    public static function getPages(): array
    {
        return [
            'index' => EditProfilPage::route('/'),
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
