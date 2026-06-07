<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TentangKamis\Pages\EditTentangKami;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TentangKamiResource extends Resource
{
    protected static ?string $model = \App\Models\DummyModel::class;

    protected static ?string $label = 'Kelola Halaman Tentang Kami';
    protected static ?string $pluralLabel = 'Kelola Halaman Tentang Kami';
    protected static ?string $navigationLabel = 'Kelola Halaman Tentang Kami';
    protected static string|null|\UnitEnum $navigationGroup = 'Kelola Landing Page';
    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            // Hero Section
            Section::make('Hero Halaman Tentang Kami')
                ->description('Mengatur bagian banner atas.')
                ->icon('heroicon-o-photo')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('tentang_kami_hero_badge')
                            ->label('Badge Hero')
                            ->placeholder('Contoh: Precision in Every Layer')
                            ->helperText('Badge kecil yang muncul di atas judul hero.'),
                        TextInput::make('tentang_kami_hero_title')
                            ->label('Judul Hero *')
                            ->placeholder('Contoh: Tentang Perusahaan Kami')
                            ->required()
                            ->helperText('Judul utama halaman Tentang Kami.'),
                    ]),
                    Textarea::make('tentang_kami_hero_desc')
                        ->label('Deskripsi Hero')
                        ->placeholder('Tuliskan pengenalan singkat perusahaan...')
                        ->rows(3)
                        ->helperText('Pengenalan singkat di bawah judul hero (2-3 kalimat).'),
                    FileUpload::make('tentang_kami_hero_image')
                        ->label('Foto Background Hero')
                        ->image()
                        ->disk('public')
                        ->directory('tentang-kami')
                        ->helperText('Foto latar belakang hero. Ukuran rekomendasi: 1920x600px.'),
                ]),

            // Visi & Misi
            Section::make('Visi & Misi Perusahaan')
                ->description('Visi, misi, dan target jangka panjang.')
                ->icon('heroicon-o-flag')
                ->collapsible()
                ->collapsed()
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('tentang_kami_visi_title')
                            ->label('Judul Visi')
                            ->placeholder('Contoh: Visi Kami')
                            ->helperText('Judul section Visi.'),
                        TextInput::make('tentang_kami_misi_title')
                            ->label('Judul Misi')
                            ->placeholder('Contoh: Misi Kami')
                            ->helperText('Judul section Misi.'),
                        Textarea::make('visi')
                            ->label('Isi Visi Perusahaan')
                            ->placeholder('Tuliskan visi jangka panjang...')
                            ->rows(4)
                            ->helperText('Visi jangka panjang perusahaan.'),
                        Textarea::make('misi')
                            ->label('Isi Misi Perusahaan')
                            ->placeholder('Tuliskan misi utama...')
                            ->rows(4)
                            ->helperText('Misi utama perusahaan.'),
                    ]),
                ]),

            // Sejarah Section
            Section::make('Sejarah Perusahaan')
                ->description('Cerita dan data statistik sejarah perusahaan.')
                ->icon('heroicon-o-academic-cap')
                ->collapsible()
                ->collapsed()
                ->schema([
                    Toggle::make('tentang_kami_show_history')
                        ->label('Tampilkan Bagian Sejarah?')
                        ->default(true)
                        ->helperText('Aktifkan untuk menampilkan sejarah di website.'),
                    Grid::make(2)->schema([
                        TextInput::make('tentang_kami_sejarah_badge')
                            ->label('Badge Sejarah')
                            ->placeholder('Contoh: Satu Dekade Dedikasi'),
                        TextInput::make('tentang_kami_sejarah_title')
                            ->label('Judul Sejarah')
                            ->placeholder('Contoh: Sejarah Perkembangan Kami'),
                    ]),
                    Textarea::make('sejarah')
                        ->label('Isi Sejarah Perusahaan')
                        ->placeholder('Tuliskan latar belakang sejarah...')
                        ->rows(5)
                        ->helperText('Ceritakan perjalanan dan perkembangan perusahaan.'),
                    
                    // Statistik & Image Sejarah
                    Section::make('Statistik & Gambar Sejarah')
                        ->description('Data angka pencapaian dan foto di bagian sejarah.')
                        ->compact()
                        ->schema([
                            Grid::make(3)->schema([
                                TextInput::make('tentang_kami_sejarah_stats_number')
                                    ->label('Angka Statistik Sejarah')
                                    ->placeholder('Contoh: 10+'),
                                TextInput::make('tentang_kami_sejarah_stats_label')
                                    ->label('Label Statistik Sejarah')
                                    ->placeholder('Contoh: Tahun Pengalaman'),
                                TextInput::make('tentang_kami_sejarah_anniversary_badge')
                                    ->label('Badge Anniversary')
                                    ->placeholder('Contoh: Established'),
                            ]),
                            Grid::make(2)->schema([
                                TextInput::make('tentang_kami_sejarah_anniversary_number')
                                    ->label('Tahun Didirikan')
                                    ->placeholder('Contoh: 2014'),
                                TextInput::make('tentang_kami_sejarah_anniversary_label')
                                    ->label('Label Anniversary')
                                    ->placeholder('Contoh: Melayani Sejak 2014'),
                            ]),
                            Grid::make(2)->schema([
                                FileUpload::make('tentang_kami_sejarah_image_shop')
                                    ->label('Foto Workshop / Studio')
                                    ->image()
                                    ->disk('public')
                                    ->directory('tentang-kami')
                                    ->helperText('Gambar area pengerjaan/workshop.'),
                                FileUpload::make('tentang_kami_sejarah_image_supercar')
                                    ->label('Foto Detail / Close-up')
                                    ->image()
                                    ->disk('public')
                                    ->directory('tentang-kami')
                                    ->helperText('Gambar hasil wrapping premium.'),
                            ]),
                        ]),
                ]),

            // Nilai-Nilai / Values
            Section::make('Nilai-Nilai Inti Perusahaan')
                ->description('Komitmen dan nilai yang dipegang teguh.')
                ->icon('heroicon-o-heart')
                ->collapsible()
                ->collapsed()
                ->schema([
                    Toggle::make('tentang_kami_show_values')
                        ->label('Tampilkan Bagian Nilai-Nilai?')
                        ->default(true)
                        ->helperText('Aktifkan untuk menampilkan nilai-nilai di website.'),
                    Grid::make(2)->schema([
                        TextInput::make('tentang_kami_values_title')
                            ->label('Judul Nilai-Nilai')
                            ->placeholder('Contoh: Nilai Yang Kami Junjung'),
                        TextInput::make('tentang_kami_values_columns')
                            ->label('Jumlah Kolom Tampilan')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(4)
                            ->default(3)
                            ->helperText('Jumlah kolom grid (1-4).'),
                    ]),
                    
                    Grid::make(3)->schema([
                        Section::make('Nilai 1')
                            ->compact()
                            ->schema([
                                TextInput::make('tentang_kami_values_1_title')->label('Judul Nilai 1'),
                                Textarea::make('tentang_kami_values_1_desc')->label('Deskripsi Nilai 1')->rows(2),
                            ]),
                        Section::make('Nilai 2')
                            ->compact()
                            ->schema([
                                TextInput::make('tentang_kami_values_2_title')->label('Judul Nilai 2'),
                                Textarea::make('tentang_kami_values_2_desc')->label('Deskripsi Nilai 2')->rows(2),
                            ]),
                        Section::make('Nilai 3')
                            ->compact()
                            ->schema([
                                TextInput::make('tentang_kami_values_3_title')->label('Judul Nilai 3'),
                                Textarea::make('tentang_kami_values_3_desc')->label('Deskripsi Nilai 3')->rows(2),
                            ]),
                    ]),
                ]),

            // Tim Section Settings
            Section::make('Pengaturan Tampilan Tim')
                ->description('Mengatur bagian anggota tim di halaman Tentang Kami.')
                ->icon('heroicon-o-users')
                ->collapsible()
                ->collapsed()
                ->schema([
                    Toggle::make('tentang_kami_show_team')
                        ->label('Tampilkan Bagian Tim?')
                        ->default(true)
                        ->helperText('Aktifkan untuk menampilkan tim di website.'),
                    Grid::make(2)->schema([
                        TextInput::make('tentang_kami_tim_badge')
                            ->label('Badge Tim')
                            ->placeholder('Contoh: Tim Profesional Kami'),
                        TextInput::make('tentang_kami_team_title')
                            ->label('Judul Bagian Tim')
                            ->placeholder('Contoh: Dibalik Setiap Detail Sempurna.'),
                    ]),
                    Textarea::make('tentang_kami_team_desc')
                        ->label('Deskripsi Bagian Tim')
                        ->placeholder('Tuliskan deskripsi singkat tim...')
                        ->rows(3)
                        ->helperText('Penjelasan singkat tentang keahlian tim (2-3 kalimat).'),
                    \Filament\Forms\Components\Repeater::make('tentang_kami_team_members')
                        ->label('Anggota Tim')
                        ->schema([
                            TextInput::make('nama')
                                ->label('Nama')
                                ->required(),
                            TextInput::make('posisi')
                                ->label('Posisi / Jabatan')
                                ->required(),
                            FileUpload::make('foto')
                                ->label('Foto')
                                ->image()
                                ->disk('public')
                                ->directory('tentang-kami/tim')
                                ->required(),
                        ])
                        ->columnSpanFull(),
                ]),

            // CTA Section
            Section::make('Ajakan Bertindak (CTA)')
                ->description('Mengatur banner ajakan bertindak di bawah halaman Tentang Kami.')
                ->icon('heroicon-o-cursor-arrow-rays')
                ->collapsible()
                ->collapsed()
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('tentang_kami_cta_title')
                            ->label('Judul CTA')
                            ->placeholder('Contoh: Siap Mengubah Tampilan Kendaraan?'),
                        Textarea::make('tentang_kami_cta_desc')
                            ->label('Deskripsi CTA')
                            ->placeholder('Tuliskan penawaran khusus...')
                            ->rows(2),
                    ]),
                    Grid::make(2)->schema([
                        TextInput::make('tentang_kami_cta_primary_button')
                            ->label('Tombol Utama CTA')
                            ->placeholder('Contoh: Hubungi Kami'),
                        TextInput::make('tentang_kami_cta_secondary_button')
                            ->label('Tombol Sekunder CTA')
                            ->placeholder('Contoh: Lihat Katalog'),
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
