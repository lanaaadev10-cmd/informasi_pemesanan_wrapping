<?php

namespace App\Filament\Resources\Addons;

use App\Filament\Resources\Addons\Pages\CreateAddon;
use App\Filament\Resources\Addons\Pages\EditAddon;
use App\Filament\Resources\Addons\Pages\ListAddons;
use App\Models\Tambahan;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
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

class AddonResource extends Resource
{
    protected static ?string $model = Tambahan::class;

    protected static ?string $navigationLabel = 'Layanan Extra';
    protected static ?string $pluralLabel = 'Layanan Extra';
    protected static ?string $modelLabel = 'Layanan Extra';
    protected static ?string $slug = 'layanan-extra';
    protected static string|null|\UnitEnum $navigationGroup = 'Konten';
    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-plus-circle';
    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Informasi Layanan Tambahan (Add-on)')
                ->schema([
                    TextInput::make('name')
                        ->label('Nama Add-on')
                        ->placeholder('Contoh: Bongkar Lem Stiker Lama, Coating Kaca')
                        ->required()
                        ->maxLength(150),

                    Select::make('category')
                        ->label('Kategori Kendaraan')
                        ->options([
                            'all'   => 'Semua Kendaraan',
                            'mobil' => 'Khusus Mobil',
                            'motor' => 'Khusus Motor',
                        ])
                        ->default('all')
                        ->required(),

                    TextInput::make('price')
                        ->label('Tarif Add-on (Rp)')
                        ->numeric()
                        ->prefix('Rp')
                        ->required(),

                    Toggle::make('is_active')
                        ->label('Status Aktif')
                        ->default(true),

                    Textarea::make('description')
                        ->label('Deskripsi Pengerjaan')
                        ->columnSpanFull()
                        ->placeholder('Jelaskan detail pengerjaan atau benefit addon...'),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('name', 'asc')
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Add-on')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'motor' => 'info',
                        'mobil' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'motor' => 'Motor',
                        'mobil' => 'Mobil',
                        default => 'Semua',
                    })
                    ->sortable(),

                TextColumn::make('price')
                    ->label('Tarif (Rp)')
                    ->money('IDR')
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
                SelectFilter::make('category')
                    ->label('Filter Kategori')
                    ->options([
                        'all'   => 'Semua Kendaraan',
                        'mobil' => 'Mobil',
                        'motor' => 'Motor',
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
            'index'  => ListAddons::route('/'),
            'create' => CreateAddon::route('/create'),
            'edit'   => EditAddon::route('/{record}/edit'),
        ];
    }
}
