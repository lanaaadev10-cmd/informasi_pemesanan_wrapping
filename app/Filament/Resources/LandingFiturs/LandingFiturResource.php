<?php

namespace App\Filament\Resources\LandingFiturs;

use App\Filament\Resources\LandingFiturs\Pages\CreateLandingFitur;
use App\Filament\Resources\LandingFiturs\Pages\EditLandingFitur;
use App\Filament\Resources\LandingFiturs\Pages\ListLandingFiturs;
use App\Filament\Resources\LandingFiturs\Schemas\LandingFiturForm;
use App\Filament\Resources\LandingFiturs\Tables\LandingFitursTable;
use App\Models\LandingFitur;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class LandingFiturResource extends Resource
{
    protected static ?string $model = LandingFitur::class;

    protected static ?string $navigationLabel = 'Kelola Fitur Beranda';
    protected static ?string $pluralLabel = 'Kelola Fitur Beranda';
    protected static string|null|\UnitEnum $navigationGroup = 'Konten';
    protected static ?int $navigationSort = 4;

    protected static ?string $recordTitleAttribute = 'judul';

    public static function form(Schema $schema): Schema
    {
        return LandingFiturForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LandingFitursTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLandingFiturs::route('/'),
            'create' => CreateLandingFitur::route('/create'),
            'edit' => EditLandingFitur::route('/{record}/edit'),
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
