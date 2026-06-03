<?php

namespace App\Filament\Resources\Testimonis;

use App\Filament\Resources\Testimonis\Pages\CreateTestimoni;
use App\Filament\Resources\Testimonis\Pages\EditTestimoni;
use App\Filament\Resources\Testimonis\Pages\ListTestimonis;
use App\Filament\Resources\Testimonis\Schemas\TestimoniForm;
use App\Filament\Resources\Testimonis\Tables\TestimonisTable;
use App\Models\Testimoni;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class TestimoniResource extends Resource
{
    protected static ?string $model = Testimoni::class;

    protected static ?string $navigationLabel = 'Kelola Testimoni';
    protected static ?string $pluralLabel = 'Kelola Testimoni';
    protected static string|null|\UnitEnum $navigationGroup = 'Konten';
    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'nama';

    public static function form(Schema $schema): Schema
    {
        return TestimoniForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TestimonisTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTestimonis::route('/'),
            'create' => CreateTestimoni::route('/create'),
            'edit' => EditTestimoni::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasRole('admin') ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->hasRole('admin') ?? false;
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return auth()->user()?->hasRole('admin') ?? false;
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return auth()->user()?->hasRole('admin') ?? false;
    }
}
