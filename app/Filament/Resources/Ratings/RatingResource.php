<?php

namespace App\Filament\Resources\Ratings;

use App\Filament\Resources\Ratings\Pages\ListRatings;
use App\Filament\Resources\Ratings\Pages\ViewRating;
use App\Filament\Resources\Ratings\Tables\RatingsTable;
use App\Models\Rating;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

/**
 * RatingResource
 * Digunakan untuk melihat, menyembunyikan, dan menghapus rating/testimoni user.
 * Tanpa create/edit manual.
 */
class RatingResource extends Resource
{
    protected static ?string $model = Rating::class;

    protected static ?string $navigationLabel = 'Rating & Testimoni';

    protected static ?string $pluralLabel = 'Rating & Testimoni';

    protected static ?string $recordTitleAttribute = 'id';

    protected static string|null|\UnitEnum $navigationGroup = 'Konten';

    protected static ?int $navigationSort = 5;

    public static function table(Table $table): Table
    {
        return RatingsTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user.name')->label('User'),
                TextEntry::make('tipe_rating')->label('Tipe'),
                TextEntry::make('layanan.nama_layanan')->label('Layanan')->placeholder('-'),
                TextEntry::make('pesanan.kode_pesanan')->label('Kode Pesanan')->placeholder('-'),
                TextEntry::make('rating')->label('Rating')->badge(),
                TextEntry::make('ulasan')->label('Ulasan')->columnSpanFull(),
                TextEntry::make('balasan_admin')
                    ->label('Balasan Admin')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('dibalas_at')->label('Dibalas Pada')->placeholder('-')->dateTime('d M Y H:i'),
                TextEntry::make('created_at')->label('Tanggal')->dateTime('d M Y H:i'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRatings::route('/'),
            'view' => ViewRating::route('/{record}'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasRole('admin') ?? false;
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()?->hasRole('admin') ?? false;
    }
}
