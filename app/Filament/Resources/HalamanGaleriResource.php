<?php

namespace App\Filament\Resources;

use App\Models\ProfilPerusahaan;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use App\Filament\Resources\HalamanGaleri\Pages\EditHalamanGaleri;
use Illuminate\Database\Eloquent\Model;

class HalamanGaleriResource extends Resource
{
    protected static ?string $model = ProfilPerusahaan::class;

    protected static ?string $label = 'Edit Halaman Galeri';
    protected static ?string $pluralLabel = 'Edit Halaman Galeri';
    protected static ?string $navigationLabel = 'Edit Halaman Galeri';
    protected static string|null|\UnitEnum $navigationGroup = 'Halaman Website';
    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Hero Section')
                ->description('Mengatur judul, deskripsi, dan background hero halaman galeri.')
                ->aside()
                ->schema([
                    TextInput::make('galeri_hero_title')
                        ->label('Judul Hero')
                        ->placeholder('Contoh: Precision Mastery Gallery')
                        ->required()
                        ->columnSpanFull()
                        ->helperText('Judul utama di bagian hero halaman galeri.'),

                    Textarea::make('galeri_hero_desc')
                        ->label('Deskripsi Hero')
                        ->placeholder('Tuliskan deskripsi galeri...')
                        ->rows(4)
                        ->columnSpanFull()
                        ->helperText('Deskripsi yang ditampilkan di bawah judul hero.'),

                    FileUpload::make('galeri_hero_image')
                        ->label('Foto Background Hero')
                        ->image()
                        ->disk('public')
                        ->directory('galeri')
                        ->maxSize(10240)
                        ->columnSpanFull()
                        ->helperText('Foto latar belakang hero section. Format: JPG, PNG. Maksimal 10MB. Ukuran rekomendasi: 1920x600px.'),
                ]),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => EditHalamanGaleri::route('/'),
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
            return route('filament.admin.resources.halaman-galeris.index', [], $isAbsolute);
        }
        return parent::getUrl($name, $parameters, $isAbsolute, $panel, $tenant, $shouldGuessMissingParameters, $configuration);
    }
}
