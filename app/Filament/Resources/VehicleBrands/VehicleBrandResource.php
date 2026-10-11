<?php

namespace App\Filament\Resources\VehicleBrands;

use App\Filament\Resources\VehicleBrands\Pages\CreateVehicleBrand;
use App\Filament\Resources\VehicleBrands\Pages\EditVehicleBrand;
use App\Filament\Resources\VehicleBrands\Pages\ListVehicleBrands;
use App\Models\VehicleBrand;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;

class VehicleBrandResource extends Resource
{
    protected static ?string $model = VehicleBrand::class;

    protected static ?string $navigationLabel = 'Merk Kendaraan';
    protected static ?string $pluralLabel = 'Merk Kendaraan';
    protected static ?string $modelLabel = 'Merk Kendaraan';
    protected static ?string $slug = 'merk-kendaraan';
    protected static string|null|\UnitEnum $navigationGroup = 'Kendaraan';
    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-truck';
    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Informasi Merk Kendaraan')
                ->schema([
                    TextInput::make('name')
                        ->label('Nama Merk')
                        ->placeholder('Contoh: Honda, Toyota, Yamaha')
                        ->required()
                        ->maxLength(100),

                    Select::make('vehicle_type')
                        ->label('Jenis Kendaraan')
                        ->options([
                            'mobil' => 'Mobil',
                            'motor' => 'Sepeda Motor',
                        ])
                        ->default('mobil')
                        ->required(),

                    Toggle::make('is_active')
                        ->label('Status Aktif')
                        ->default(true),
                ])->columns(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('name', 'asc')
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Merk')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('vehicle_type')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn ($state) => $state === 'motor' ? 'info' : 'success')
                    ->formatStateUsing(fn ($state) => $state === 'motor' ? 'Sepeda Motor' : 'Mobil')
                    ->sortable(),

                TextColumn::make('models_count')
                    ->label('Jumlah Model')
                    ->counts('models')
                    ->badge(),

                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListVehicleBrands::route('/'),
            'create' => CreateVehicleBrand::route('/create'),
            'edit'   => EditVehicleBrand::route('/{record}/edit'),
        ];
    }
}
