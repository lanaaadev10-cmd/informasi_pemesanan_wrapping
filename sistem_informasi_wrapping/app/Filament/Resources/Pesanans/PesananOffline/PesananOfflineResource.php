<?php

namespace App\Filament\Resources\Pesanans\PesananOffline;

use App\Filament\Resources\Pesanans\PesananOffline\Pages\EditPesananOffline;
use App\Filament\Resources\Pesanans\PesananOffline\Pages\ListPesananOfflines;
use App\Filament\Resources\Pesanans\PesananOffline\Schemas\PesananOfflineForm;
use App\Filament\Resources\Pesanans\PesananOffline\Tables\PesananOfflineTable;
use App\Models\Pesanan;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class PesananOfflineResource extends Resource
{
    protected static ?string $model = Pesanan::class;

    protected static ?string $label = 'Pemesanan Offline';
    protected static ?string $pluralLabel = 'Pemesanan Offline';
    protected static ?string $navigationLabel = 'Pemesanan Offline';
    protected static string|null|\UnitEnum $navigationGroup = 'Laporan & Pembayaran';
    protected static ?int $navigationSort = 2;
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->where('order_source', Pesanan::ORDER_SOURCE_OFFLINE);
    }

    public static function form(Schema $schema): Schema
    {
        return PesananOfflineForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PesananOfflineTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPesananOfflines::route('/'),
            'edit'  => EditPesananOffline::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasRole('admin') ?? false;
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return auth()->user()?->hasRole('admin') ?? false;
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return auth()->user()?->hasRole('admin') ?? false;
    }
}
