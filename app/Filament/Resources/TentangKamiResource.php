<?php

namespace App\Filament\Resources;

use App\Models\ProfilPerusahaan;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Repeater;
use App\Filament\Resources\TentangKamis\Pages\EditTentangKami;
use Illuminate\Database\Eloquent\Model;

class TentangKamiResource extends Resource
{
    protected static ?string $model = ProfilPerusahaan::class;

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-information-circle';
    protected static ?string $label = 'Edit Tentang Kami';
    protected static ?string $pluralLabel = 'Edit Tentang Kami';
    protected static ?string $navigationLabel = 'Edit Tentang Kami';
    protected static string|null|\UnitEnum $navigationGroup = 'Kelola Konten Website';
    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            // Hero Section
            TextInput::make('tentang_kami_hero_title')
                ->label('Judul Hero - Tentang Kami')
                ->placeholder('Contoh: Tentang Perusahaan Kami')
                ->required()
                ->columnSpanFull()
                ->helperText('Judul utama di bagian hero halaman tentang kami.'),

            Textarea::make('tentang_kami_hero_desc')
                ->label('Deskripsi Hero')
                ->placeholder('Tuliskan pengenalan singkat perusahaan...')
                ->rows(4)
                ->columnSpanFull()
                ->helperText('Deskripsi yang ditampilkan di bagian hero bersama dengan foto background.'),

            FileUpload::make('tentang_kami_hero_image')
                ->label('Foto Background Hero')
                ->image()
                ->disk('public')
                ->directory('tentang-kami')
                ->maxSize(10240)
                ->columnSpanFull()
                ->helperText('Foto latar belakang hero section. Format: JPG, PNG. Maksimal 10MB. Ukuran rekomendasi: 1920x600px.'),

            // Visi & Misi
            Textarea::make('visi')
                ->label('Visi Perusahaan')
                ->placeholder('Tuliskan visi atau tujuan jangka panjang perusahaan...')
                ->rows(4)
                ->columnSpan(1)
                ->helperText('Visi perusahaan untuk masa depan.'),

            Textarea::make('misi')
                ->label('Misi Perusahaan')
                ->placeholder('Tuliskan misi atau tujuan utama perusahaan...')
                ->rows(4)
                ->columnSpan(1)
                ->helperText('Misi yang menjadi panduan operasional perusahaan.'),

            Textarea::make('sejarah')
                ->label('Sejarah Perusahaan')
                ->placeholder('Tuliskan latar belakang dan sejarah perkembangan perusahaan...')
                ->rows(5)
                ->columnSpanFull()
                ->helperText('Cerita latar belakang, perjalanan, dan pencapaian perusahaan.'),

            // Tim Section Settings
            Toggle::make('tentang_kami_show_team')
                ->label('Tampilkan Bagian Tim?')
                ->columnSpan(1)
                ->helperText('Aktifkan untuk menampilkan daftar tim di halaman tentang kami.')
                ->default(true),

            TextInput::make('tentang_kami_team_title')
                ->label('Judul Bagian Tim')
                ->placeholder('Contoh: Tim Profesional Kami')
                ->columnSpan(1)
                ->helperText('Judul untuk bagian tim/anggota perusahaan.'),

            Textarea::make('tentang_kami_team_desc')
                ->label('Deskripsi Bagian Tim')
                ->placeholder('Tuliskan deskripsi singkat tentang tim perusahaan...')
                ->rows(3)
                ->columnSpanFull()
                ->helperText('Deskripsi yang ditampilkan di atas daftar anggota tim.'),

            // Nilai/Values Section
            Toggle::make('tentang_kami_show_values')
                ->label('Tampilkan Bagian Nilai-Nilai?')
                ->columnSpan(1)
                ->helperText('Aktifkan untuk menampilkan nilai-nilai perusahaan.')
                ->default(true),

            TextInput::make('tentang_kami_values_columns')
                ->label('Jumlah Kolom Nilai')
                ->numeric()
                ->minValue(1)
                ->maxValue(4)
                ->default(4)
                ->columnSpan(1)
                ->helperText('Berapa kolom untuk menampilkan nilai-nilai (1-4 kolom).'),

            // Sejarah Section Settings
            Toggle::make('tentang_kami_show_history')
                ->label('Tampilkan Bagian Sejarah?')
                ->columnSpan(1)
                ->helperText('Aktifkan untuk menampilkan sejarah di halaman tentang kami.')
                ->default(true),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => EditTentangKami::route('/'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasRole('admin') ?? false;
    }

    public static function canCreate(): bool { return false; }
    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool { return false; }

    protected static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    public static function getUrl(string $name = 'index', array $parameters = [], bool $isAbsolute = true): string
    {
        // Singleton - always go to first record
        if ($name === 'index') {
            return route('filament.admin.resources.tentang-kamis.index', [], $isAbsolute);
        }
        return parent::getUrl($name, $parameters, $isAbsolute);
    }
}
