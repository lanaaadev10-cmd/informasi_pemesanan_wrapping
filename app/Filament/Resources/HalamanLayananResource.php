<?php

namespace App\Filament\Resources;

use App\Models\ProfilPerusahaan;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use App\Filament\Resources\HalamanLayanan\Pages\EditHalamanLayanan;
use Illuminate\Database\Eloquent\Model;

class HalamanLayananResource extends Resource
{
    protected static ?string $model = ProfilPerusahaan::class;

    protected static ?string $label = 'Edit Halaman Layanan';
    protected static ?string $pluralLabel = 'Edit Halaman Layanan';
    protected static ?string $navigationLabel = 'Edit Halaman Layanan';
    protected static string|null|\UnitEnum $navigationGroup = 'Halaman Website';
    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Hero Section')
                ->description('Mengatur judul, deskripsi, dan background hero halaman layanan.')
                ->aside()
                ->schema([
                    TextInput::make('layanan_hero_title')
                        ->label('Judul Hero')
                        ->placeholder('Contoh: Precision in Every Layer.')
                        ->required()
                        ->columnSpanFull()
                        ->helperText('Judul utama di bagian hero halaman layanan.'),

                    Textarea::make('layanan_hero_desc')
                        ->label('Deskripsi Hero')
                        ->placeholder('Tuliskan deskripsi layanan...')
                        ->rows(4)
                        ->columnSpanFull()
                        ->helperText('Deskripsi yang ditampilkan di bawah judul hero.'),

                    FileUpload::make('layanan_hero_image')
                        ->label('Foto Background Hero')
                        ->image()
                        ->disk('public')
                        ->directory('layanan')
                        ->maxSize(10240)
                        ->columnSpanFull()
                        ->helperText('Foto latar belakang hero section. Format: JPG, PNG. Maksimal 10MB. Ukuran rekomendasi: 1920x600px.'),
                ]),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => EditHalamanLayanan::route('/'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasRole('admin') ?? false;
    }

    public static function canCreate(): bool { return false; }
    public static function canDelete(Model $record): bool { return false; }

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    public static function getUrl(?string $name = null, array $parameters = [], bool $isAbsolute = true, ?string $panel = null, ?Model $tenant = null, bool $shouldGuessMissingParameters = false, ?string $configuration = null): string
    {
        if ($name === 'index') {
            return route('filament.admin.resources.halaman-layanans.index', [], $isAbsolute);
        }
        return parent::getUrl($name, $parameters, $isAbsolute, $panel, $tenant, $shouldGuessMissingParameters, $configuration);
    }
}
