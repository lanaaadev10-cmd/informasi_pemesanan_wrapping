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
use Filament\Support\Icons\Heroicon;

class LayananResource extends Resource
{
    protected static ?string $model = Layanan::class;

   // Tambahkan string|BackedEnum|null secara eksplisit
    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-s-shopping-bag';
    
    protected static ?string $navigationLabel = 'Katalog Layanan';
    protected static ?string $pluralModelLabel = 'Katalog Layanan';
    protected static ?string $recordTitleAttribute = 'nama_layanan';

    // TAMBAHKAN BARIS INI (Angka 3 biar di bawah Dashboard)    
    protected static ?int $navigationSort = 3;

    public static function form(Schema $form): Schema
    {
        return $form->schema(LayananForm::schema());
    }

    public static function table(Table $table): Table
    {
        return LayanansTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListLayanans::route('/'),
            'create' => CreateLayanan::route('/create'),
            'edit'   => EditLayanan::route('/{record}/edit'),
        ];
    }
}