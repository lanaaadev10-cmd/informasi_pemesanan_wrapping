<?php

namespace App\Filament\Resources;

use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use App\Filament\Resources\TentangKamis\Pages\EditTentangKami;
use App\Models\DummyModel;

class TentangKamiResource extends Resource
{
    protected static ?string $model = DummyModel::class;

    protected static ?string $label = 'Edit Tentang Kami';
    protected static ?string $pluralLabel = 'Edit Tentang Kami';
    protected static ?string $navigationLabel = 'Edit Tentang Kami';
    protected static string|null|\UnitEnum $navigationGroup = 'Website';
    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Hero')
                ->description('Atur bagian hero di halaman Tentang Kami.')
                ->aside()
                ->icon('heroicon-o-photo')
                ->schema([
                    TextInput::make('tentang_kami_hero_badge')
                        ->label('Badge Hero')
                        ->placeholder('Contoh: Tentang Kami')
                        ->helperText('Label kecil di atas judul hero.')
                        ->columnSpanFull(),
                    TextInput::make('tentang_kami_hero_title')
                        ->label('Judul Hero')
                        ->placeholder('Contoh: Precision in Every Layer')
                        ->required()
                        ->columnSpanFull(),
                    Textarea::make('tentang_kami_hero_desc')
                        ->label('Deskripsi Hero')
                        ->placeholder('Tuliskan pengenalan singkat perusahaan...')
                        ->rows(3)
                        ->columnSpanFull(),
                    FileUpload::make('tentang_kami_hero_image')
                        ->label('Foto Background Hero')
                        ->image()
                        ->disk('public')
                        ->directory('tentang-kami')
                        ->maxSize(10240)
                        ->columnSpanFull(),
                ]),

            Section::make('Visi & Misi')
                ->description('Atur judul dan konten visi & misi perusahaan.')
                ->aside()
                ->icon('heroicon-o-light-bulb')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('tentang_kami_visi_title')
                            ->label('Judul Visi')
                            ->placeholder('Contoh: Visi Kami'),
                        TextInput::make('tentang_kami_misi_title')
                            ->label('Judul Misi')
                            ->placeholder('Contoh: Misi Kami'),
                    ]),
                    Grid::make(2)->schema([
                        Textarea::make('visi')
                            ->label('Visi Perusahaan')
                            ->placeholder('Tuliskan visi atau tujuan jangka panjang perusahaan...')
                            ->rows(4),
                        Textarea::make('misi')
                            ->label('Misi Perusahaan')
                            ->placeholder('Tuliskan misi atau tujuan utama perusahaan...')
                            ->rows(4),
                    ]),
                ]),

            Section::make('Nilai-Nilai Perusahaan')
                ->description('Atur judul dan 3 nilai inti perusahaan.')
                ->aside()
                ->icon('heroicon-o-star')
                ->collapsible()
                ->collapsed()
                ->schema([
                    Toggle::make('tentang_kami_show_values')
                        ->label('Tampilkan Bagian Nilai-Nilai?')
                        ->columnSpanFull()
                        ->default(true),
                    TextInput::make('tentang_kami_values_title')
                        ->label('Judul Section Nilai')
                        ->placeholder('Contoh: Nilai yang Kami Junjung')
                        ->columnSpanFull(),
                    TextInput::make('tentang_kami_values_columns')
                        ->label('Jumlah Kolom Nilai')
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(4)
                        ->default(3),
                    Grid::make(3)->schema([
                        Section::make('Nilai 1')->compact()->schema([
                            TextInput::make('tentang_kami_values_1_title')->label('Judul')->placeholder('Contoh: Presisi'),
                            Textarea::make('tentang_kami_values_1_desc')->label('Deskripsi')->rows(3),
                        ]),
                        Section::make('Nilai 2')->compact()->schema([
                            TextInput::make('tentang_kami_values_2_title')->label('Judul')->placeholder('Contoh: Integritas'),
                            Textarea::make('tentang_kami_values_2_desc')->label('Deskripsi')->rows(3),
                        ]),
                        Section::make('Nilai 3')->compact()->schema([
                            TextInput::make('tentang_kami_values_3_title')->label('Judul')->placeholder('Contoh: Eksklusivitas'),
                            Textarea::make('tentang_kami_values_3_desc')->label('Deskripsi')->rows(3),
                        ]),
                    ]),
                ]),

            Section::make('Tim')
                ->description('Atur bagian tim perusahaan.')
                ->aside()
                ->icon('heroicon-o-users')
                ->collapsible()
                ->collapsed()
                ->schema([
                    Toggle::make('tentang_kami_show_team')
                        ->label('Tampilkan Bagian Tim?')
                        ->columnSpanFull()
                        ->default(true),
                    TextInput::make('tentang_kami_tim_badge')
                        ->label('Badge Tim')
                        ->placeholder('Contoh: Tim Kami'),
                    TextInput::make('tentang_kami_team_title')
                        ->label('Judul Bagian Tim')
                        ->placeholder('Contoh: Dibalik Setiap Detail Sempurna.')
                        ->columnSpanFull(),
                    Textarea::make('tentang_kami_team_desc')
                        ->label('Deskripsi Bagian Tim')
                        ->placeholder('Tuliskan deskripsi singkat tentang tim perusahaan...')
                        ->rows(3)
                        ->columnSpanFull(),
                ]),

            Section::make('Sejarah')
                ->description('Atur bagian sejarah perusahaan.')
                ->aside()
                ->icon('heroicon-o-clock')
                ->collapsible()
                ->collapsed()
                ->schema([
                    Toggle::make('tentang_kami_show_history')
                        ->label('Tampilkan Bagian Sejarah?')
                        ->columnSpanFull()
                        ->default(true),
                    TextInput::make('tentang_kami_sejarah_badge')
                        ->label('Badge Sejarah')
                        ->placeholder('Contoh: Sejarah Kami'),
                    TextInput::make('tentang_kami_sejarah_title')
                        ->label('Judul Sejarah')
                        ->placeholder('Contoh: Satu Dekade Dedikasi pada Perfeksi.')
                        ->columnSpanFull(),
                    Textarea::make('sejarah')
                        ->label('Sejarah Perusahaan')
                        ->placeholder('Tuliskan latar belakang dan sejarah perkembangan perusahaan...')
                        ->rows(5)
                        ->columnSpanFull(),
                ]),

            Section::make('Ajakan Bertindak (CTA)')
                ->description('Atur bagian CTA di halaman Tentang Kami.')
                ->aside()
                ->icon('heroicon-o-megaphone')
                ->collapsible()
                ->collapsed()
                ->schema([
                    TextInput::make('tentang_kami_cta_title')
                        ->label('Judul CTA')
                        ->placeholder('Contoh: Siap Mengubah Tampilan Kendaraan Anda?')
                        ->columnSpanFull(),
                    Textarea::make('tentang_kami_cta_desc')
                        ->label('Deskripsi CTA')
                        ->placeholder('Tuliskan ajakan untuk menghubungi...')
                        ->rows(2)
                        ->columnSpanFull(),
                    Grid::make(2)->schema([
                        TextInput::make('tentang_kami_cta_primary_button')
                            ->label('Tombol Utama CTA')
                            ->placeholder('Contoh: Hubungi Kami Sekarang'),
                        TextInput::make('tentang_kami_cta_secondary_button')
                            ->label('Tombol Sekunder CTA')
                            ->placeholder('Contoh: Lihat Portofolio'),
                    ]),
                ]),
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

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }
}
