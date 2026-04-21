<?php

namespace App\Filament\Resources\ProfilPerusahaans;

use App\Filament\Resources\ProfilPerusahaans\Pages\CreateProfilPerusahaan;
use App\Filament\Resources\ProfilPerusahaans\Pages\EditProfilPerusahaan;
use App\Filament\Resources\ProfilPerusahaans\Pages\ListProfilPerusahaans;
use App\Filament\Resources\ProfilPerusahaans\Schemas\ProfilPerusahaanForm;
use App\Filament\Resources\ProfilPerusahaans\Tables\ProfilPerusahaansTable;
use App\Models\ProfilPerusahaan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProfilPerusahaanResource extends Resource
{
    protected static ?string $model = ProfilPerusahaan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'ProfilPerusahaan';

    public static function form(Schema $schema): Schema
    {
        return ProfilPerusahaanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProfilPerusahaansTable::configure($table);
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
            'index' => ListProfilPerusahaans::route('/'),
            'create' => CreateProfilPerusahaan::route('/create'),
            'edit' => EditProfilPerusahaan::route('/{record}/edit'),
        ];
    }
}
