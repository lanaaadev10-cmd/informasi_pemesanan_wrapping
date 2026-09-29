<?php

namespace App\Filament\Resources\BlockedDates;

use App\Filament\Resources\BlockedDates\Pages\CreateBlockedDate;
use App\Filament\Resources\BlockedDates\Pages\EditBlockedDate;
use App\Filament\Resources\BlockedDates\Pages\ListBlockedDates;
use App\Models\BlockedDate;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;

class BlockedDateResource extends Resource
{
    protected static ?string $model = BlockedDate::class;

    protected static ?string $navigationLabel = 'Tanggal Libur / Blokir';
    protected static ?string $pluralLabel = 'Tanggal Libur / Blokir';
    protected static ?string $modelLabel = 'Tanggal Diblokir';
    protected static ?string $slug = 'tanggal-diblokir';
    protected static string|null|\UnitEnum $navigationGroup = 'Transaksi';
    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-no-symbol';
    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Informasi Tanggal Libur / Blokir')
                ->description('Tanggal yang diblokir tidak dapat dipesan oleh pelanggan di kalender.')
                ->schema([
                    DatePicker::make('date')
                        ->label('Tanggal')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->default(now()->toDateString()),

                    TextInput::make('reason')
                        ->label('Keterangan / Alasan')
                        ->placeholder('Contoh: Hari Libur Nasional / Maintenance Workshop')
                        ->required()
                        ->default('Tutup / Hari Libur')
                        ->maxLength(255),

                    Toggle::make('is_active')
                        ->label('Status Aktif')
                        ->default(true)
                        ->helperText('Jika aktif, tanggal ini terkunci untuk booking.'),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('date', 'desc')
            ->columns([
                TextColumn::make('date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable()
                    ->badge()
                    ->color('danger'),

                TextColumn::make('reason')
                    ->label('Alasan / Keterangan')
                    ->searchable()
                    ->weight('bold'),

                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBlockedDates::route('/'),
            'create' => CreateBlockedDate::route('/create'),
            'edit' => EditBlockedDate::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasRole('admin') ?? false;
    }
}
