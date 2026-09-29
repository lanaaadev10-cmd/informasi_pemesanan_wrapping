<?php

namespace App\Filament\Resources\Companies;

use Filament\Resources\Resource;
use App\Filament\Resources\Companies\Pages\EditCompany;

class CompanyResource extends Resource
{
    protected static ?string $label = 'Profil Perusahaan';
    protected static ?string $pluralLabel = 'Profil Perusahaan';
    protected static ?string $navigationLabel = 'Profil Perusahaan';
    protected static string|null|\UnitEnum $navigationGroup = 'Pengaturan';
    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?int $navigationSort = 2;
    protected static bool $shouldRegisterNavigation = true;

    public static function getPages(): array
    {
        return [
            'index' => EditCompany::route('/'),
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
