<?php

namespace App\Filament\Resources;

use Filament\Resources\Resource;
use App\Filament\Resources\DashboardCustomer\Pages\EditDashboardCustomer;

class DashboardCustomerResource extends Resource
{
    protected static ?string $label = 'Dashboard Customer';
    protected static ?string $pluralLabel = 'Dashboard Customer';
    protected static ?string $navigationLabel = 'Dashboard Customer';
    protected static string|null|\UnitEnum $navigationGroup = 'Kelola User Dashboard';
    protected static ?int $navigationSort = 4;

    public static function getPages(): array
    {
        return [
            'index' => EditDashboardCustomer::route('/'),
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
