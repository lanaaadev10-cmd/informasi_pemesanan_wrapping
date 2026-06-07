<?php

namespace App\Filament\Resources;

use Filament\Resources\Resource;
use App\Filament\Resources\Company\Pages\EditCompany;

class CompanyResource extends Resource
{
    protected static ?string $label = 'Setting Maps & Logo';
    protected static ?string $pluralLabel = 'Setting Maps & Logo';
    protected static ?string $navigationLabel = 'Setting Maps & Logo';
    protected static string|null|\UnitEnum $navigationGroup = 'Pengaturan Sistem';
    protected static ?int $navigationSort = 2;

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
