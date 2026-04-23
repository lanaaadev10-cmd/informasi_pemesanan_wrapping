<?php

namespace App\Filament\Resources\ProfilPerusahaans;

use App\Filament\Resources\ProfilPerusahaans\Pages\CreateProfilPerusahaan;
use App\Filament\Resources\ProfilPerusahaans\Pages\EditProfilPerusahaan;
use App\Filament\Resources\ProfilPerusahaans\Pages\ListProfilPerusahaans;
use App\Filament\Resources\ProfilPerusahaans\Schemas\ProfilPerusahaanForm;
use App\Filament\Resources\ProfilPerusahaans\Tables\ProfilPerusahaansTable;
use App\Models\ProfilPerusahaan;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProfilPerusahaanResource extends Resource
{
    protected static ?string $model = ProfilPerusahaan::class;

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $label = 'Profil Perusahaan';
    protected static ?string $pluralLabel = 'Profil Perusahaan';
    protected static ?string $navigationLabel = 'Profil Perusahaan';

    protected static ?string $recordTitleAttribute = 'ProfilPerusahaan';

    // TAMBAHKAN BARIS INI (Angka 2 biar di bawah Dashboard)
    protected static ?int $navigationSort = 2;

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
