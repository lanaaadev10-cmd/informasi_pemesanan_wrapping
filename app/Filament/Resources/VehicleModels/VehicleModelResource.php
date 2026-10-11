<?php

namespace App\Filament\Resources\VehicleModels;

use App\Filament\Resources\VehicleModels\Pages\CreateVehicleModel;
use App\Filament\Resources\VehicleModels\Pages\EditVehicleModel;
use App\Filament\Resources\VehicleModels\Pages\ListVehicleModels;
use App\Models\VehicleBrand;
use App\Models\VehicleModel;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;

class VehicleModelResource extends Resource
{
    protected static ?string $model = VehicleModel::class;

    protected static ?string $navigationLabel = 'Tipe & Model Kendaraan';
    protected static ?string $pluralLabel = 'Tipe & Model Kendaraan';
    protected static ?string $modelLabel = 'Model Kendaraan';
    protected static ?string $slug = 'model-kendaraan';
    protected static string|null|\UnitEnum $navigationGroup = 'Kendaraan';
    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-tag';
    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Informasi Model & Ukuran Kendaraan')
                ->schema([
                    Select::make('brand_id')
                        ->label('Merk Kendaraan')
                        ->relationship('brand', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),

                    TextInput::make('name')
                        ->label('Nama Model')
                        ->placeholder('Contoh: Avanza, Fortuner, NMAX, Beat')
                        ->required()
                        ->maxLength(100),

                    Select::make('vehicle_size')
                        ->label('Tier Ukuran Kendaraan')
                        ->options([
                            'small'  => 'Kecil (Small - Brio, Agya, Beat, Mio)',
                            'medium' => 'Sedang (Medium - Avanza, HR-V, Vario, NMAX)',
                            'large'  => 'Besar (Large - Fortuner, Pajero, Innova, Ninja)',
                            'xlarge' => 'Ekstra Besar (XL - Alphard, HiAce, Staria)',
                        ])
                        ->default('medium')
                        ->required()
                        ->helperText('Ukuran menentukan standar estimasi harga pada matrix pricing.'),

                    Toggle::make('is_active')
                        ->label('Status Aktif')
                        ->default(true),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('name', 'asc')
            ->columns([
                TextColumn::make('brand.name')
                    ->label('Merk')
                    ->badge()
                    ->color('info')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('name')
                    ->label('Nama Model')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('vehicle_size')
                    ->label('Ukuran')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'small'  => 'success',
                        'medium' => 'info',
                        'large'  => 'warning',
                        'xlarge' => 'danger',
                        default  => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => strtoupper($state))
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('brand_id')
                    ->label('Filter Merk')
                    ->relationship('brand', 'name'),
                SelectFilter::make('vehicle_size')
                    ->label('Filter Ukuran')
                    ->options([
                        'small'  => 'Small',
                        'medium' => 'Medium',
                        'large'  => 'Large',
                        'xlarge' => 'X-Large',
                    ]),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListVehicleModels::route('/'),
            'create' => CreateVehicleModel::route('/create'),
            'edit'   => EditVehicleModel::route('/{record}/edit'),
        ];
    }
}
