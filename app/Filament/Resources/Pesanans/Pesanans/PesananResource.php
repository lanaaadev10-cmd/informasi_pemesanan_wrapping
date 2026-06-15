<?php

namespace App\Filament\Resources\Pesanans\Pesanans;

use App\Filament\Resources\Pesanans\Pesanans\Pages\CreatePesanan;
use App\Filament\Resources\Pesanans\Pesanans\Pages\EditPesanan;
use App\Filament\Resources\Pesanans\Pesanans\Pages\ListPesanans;
use App\Filament\Resources\Pesanans\Pesanans\Pages\ViewPesanan;
use App\Filament\Resources\Pesanans\Pesanans\Schemas\PesananForm;

use App\Filament\Resources\Pesanans\Pesanans\Tables\PesanansTable;
use App\Models\Pesanan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class PesananResource extends Resource
{
    protected static ?string $model = Pesanan::class;

    protected static ?string $label = 'Kelola Pemesanan';
    protected static ?string $pluralLabel = 'Kelola Pemesanan';
    protected static ?string $navigationLabel = 'Kelola Pemesanan';
    protected static string|null|\UnitEnum $navigationGroup = 'Kelola User Dashboard';
    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return PesananForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PesanansTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPesanans::route('/'),
            'create' => CreatePesanan::route('/create'),
            'view' => ViewPesanan::route('/{record}'),
            'edit' => EditPesanan::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasRole('admin') ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->hasRole('admin') ?? false;
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
