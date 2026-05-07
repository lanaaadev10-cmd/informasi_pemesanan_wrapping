<?php

namespace App\Filament\Resources\Galeris;

use App\Filament\Resources\Galeris\Pages\CreateGaleri;
use App\Filament\Resources\Galeris\Pages\EditGaleri;
use App\Filament\Resources\Galeris\Pages\ListGaleris;
use App\Filament\Resources\Galeris\Schemas\GaleriForm;
use App\Filament\Resources\Galeris\Tables\GalerisTable;
use App\Models\Galeri;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DatePicker;

class GaleriResource extends Resource
{
    protected static ?string $model = Galeri::class;

    protected static ?string $navigationLabel = 'Galeri';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'galeri';

    public static function form(Schema $schema): Schema
    {
    return $schema->schema([

        TextInput::make('judul')
            ->required(),

        FileUpload::make('foto')
            ->image()
            ->directory('galeri') // folder storage
            ->disk('public') // gunakan disk 'public'
            ->maxSize(10240) // Maksimal 10MB
            ->helperText('Maksimal ukuran file adalah 10MB ya!')
            ->required(),

        Textarea::make('deskripsi'),

        DatePicker::make('tanggal_upload')
            ->required(),

    ]);
}

    public static function table(Table $table): Table
    {
        return GalerisTable::configure($table);
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
            'index' => ListGaleris::route('/'),
            'create' => CreateGaleri::route('/create'),
            'edit' => EditGaleri::route('/{record}/edit'),
        ];
    }
}
