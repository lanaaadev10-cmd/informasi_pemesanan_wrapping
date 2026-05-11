<?php

namespace App\Filament\Resources\Layanans;

use App\Filament\Resources\Layanans\Pages\CreateLayanan;
use App\Filament\Resources\Layanans\Pages\EditLayanan;
use App\Filament\Resources\Layanans\Pages\ListLayanans;
use App\Filament\Resources\Layanans\Schemas\LayananForm;
use App\Filament\Resources\Layanans\Tables\LayanansTable;
use App\Models\Layanan;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

/**
 * LayananResource
 * Digunakan untuk mengelola Katalog Layanan/Jasa di dashboard admin.
 */
class LayananResource extends Resource
{
    // Model yang digunakan: Layanan
    protected static ?string $model = Layanan::class;

    // Icon sidebar: Tas Belanja
    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-s-shopping-bag';
    
    // Penamaan menu di Admin
    protected static ?string $navigationLabel = 'Katalog Layanan';
    protected static ?string $pluralModelLabel = 'Katalog Layanan';
    protected static ?string $recordTitleAttribute = 'nama_layanan';

    // Urutan ke-3 di Sidebar
    protected static ?int $navigationSort = 3;

    /**
     * Konfigurasi Form (Logika ada di folder Schemas)
     */
    public static function form(Schema $form): Schema
    {
        return $form->schema(LayananForm::schema());
    }

    /**
     * Konfigurasi Table (Logika ada di folder Tables)
     */
    public static function table(Table $table): Table
    {
        return LayanansTable::configure($table);
    }

    /**
     * Definisi Rute Halaman
     */
    public static function getPages(): array
    {
        return [
            'index'  => ListLayanans::route('/'),
            'create' => CreateLayanan::route('/create'),
            'edit'   => EditLayanan::route('/{record}/edit'),
        ];
    }
}