<?php

namespace App\Filament\Resources;

use App\Models\ProfilPerusahaan;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use App\Filament\Resources\Berandas\Pages\EditBeranda;

class BerandaResource extends Resource
{
    protected static ?string $model = ProfilPerusahaan::class;

    protected static ?string $label = 'Kelola Halaman Beranda';
    protected static ?string $pluralLabel = 'Kelola Halaman Beranda';
    protected static ?string $navigationLabel = 'Kelola Halaman Beranda';
    protected static string|null|\UnitEnum $navigationGroup = 'Kelola Landing Page';
    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Hero & Statistik')
                ->description('Mengatur bagian banner atas dan data statistik.')
                ->aside()
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('home_badge_text')
                            ->label('Badge Text (Atas)')
                            ->placeholder('Contoh: Professional Car Wrapping Indonesia')
                            ->helperText('Label kecil yang muncul di atas judul hero di halaman utama. Biasanya berisi tag atau kategori.')
                            ->required()
                            ->columnSpanFull(),
                        TextInput::make('home_hero_title_line1')
                            ->label('Judul Hero Baris 1')
                            ->placeholder('Contoh: Elevasi Estetika')
                            ->helperText('Baris pertama judul utama hero section. Buat menarik dan singkat (max 5 kata).')
                            ->required(),
                        TextInput::make('home_hero_title_line2')
                            ->label('Judul Hero Baris 2')
                            ->placeholder('Contoh: Aset Mewah Anda.')
                            ->helperText('Baris kedua judul. Lengkapi baris pertama untuk dampak maksimal (max 5 kata).')
                            ->required(),
                        Textarea::make('home_subtitle')
                            ->label('Sub-deskripsi Hero')
                            ->placeholder('Tuliskan deskripsi singkat penawaran...')
                            ->helperText('Deskripsi singkat di bawah judul. Jelaskan value proposition dalam 2-3 kalimat.')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
                    Grid::make(4)->schema([
                        TextInput::make('home_stat1_value')
                            ->label('Statistik 1 Angka')
                            ->placeholder('Contoh: 500+')
                            ->helperText('Angka/nilai untuk statistik pertama (contoh: 500+, 10 tahun, 99%).'),
                        TextInput::make('home_stat1_label')
                            ->label('Statistik 1 Label')
                            ->placeholder('Contoh: Supercars Wrapped')
                            ->helperText('Deskripsi untuk statistik pertama (contoh: Supercars Wrapped, Clients Satisfied).'),
                        TextInput::make('home_stat2_value')
                            ->label('Statistik 2 Angka')
                            ->placeholder('Contoh: 5 Tahun')
                            ->helperText('Angka/nilai untuk statistik kedua.'),
                        TextInput::make('home_stat2_label')
                            ->label('Statistik 2 Label')
                            ->placeholder('Contoh: Garansi Material')
                            ->helperText('Deskripsi untuk statistik kedua.'),
                    ]),
                ]),

            Section::make('Keunggulan Layanan')
                ->description('Mengatur 4 kartu keunggulan di halaman utama.')
                ->aside()
                ->collapsible()
                ->schema([
                    Grid::make(2)->schema([
                        Section::make('Keunggulan 1')
                            ->compact()
                            ->schema([
                                TextInput::make('home_keunggulan_card1_title')
                                    ->label('Judul')
                                    ->helperText('Judul keunggulan pertama (contoh: Kualitas Premium, Garansi Seumur Hidup).')
                                    ->required(),
                                Textarea::make('home_keunggulan_card1_desc')
                                    ->label('Deskripsi')
                                    ->helperText('Penjelasan detail tentang keunggulan ini (1-2 kalimat).')
                                    ->rows(3)
                                    ->required(),
                            ]),
                        Section::make('Keunggulan 2')
                            ->compact()
                            ->schema([
                                TextInput::make('home_keunggulan_card2_title')
                                    ->label('Judul')
                                    ->helperText('Judul keunggulan kedua.')
                                    ->required(),
                                Textarea::make('home_keunggulan_card2_desc')
                                    ->label('Deskripsi')
                                    ->helperText('Penjelasan detail tentang keunggulan ini (1-2 kalimat).')
                                    ->rows(3)
                                    ->required(),
                            ]),
                        Section::make('Keunggulan 3')
                            ->compact()
                            ->schema([
                                TextInput::make('home_keunggulan_card3_title')
                                    ->label('Judul')
                                    ->helperText('Judul keunggulan ketiga.')
                                    ->required(),
                                Textarea::make('home_keunggulan_card3_desc')
                                    ->label('Deskripsi')
                                    ->helperText('Penjelasan detail tentang keunggulan ini (1-2 kalimat).')
                                    ->rows(3)
                                    ->required(),
                            ]),
                        Section::make('Keunggulan 4')
                            ->compact()
                            ->schema([
                                TextInput::make('home_keunggulan_card4_title')
                                    ->label('Judul')
                                    ->helperText('Judul keunggulan keempat.')
                                    ->required(),
                                Textarea::make('home_keunggulan_card4_desc')
                                    ->label('Deskripsi')
                                    ->helperText('Penjelasan detail tentang keunggulan ini (1-2 kalimat).')
                                    ->rows(3)
                                    ->required(),
                            ]),
                    ]),
                ]),

            Section::make('Langkah Mudah (Fast Process)')
                ->description('Mengatur 3 langkah pengerjaan.')
                ->aside()
                ->collapsible()
                ->schema([
                    Grid::make(3)->schema([
                        Section::make('Langkah 1')
                            ->compact()
                            ->schema([
                                TextInput::make('home_step1_title')
                                    ->label('Judul')
                                    ->helperText('Nama langkah pertama (contoh: Konsultasi, Pilih Warna, Proses).')
                                    ->required(),
                                Textarea::make('home_step1_desc')
                                    ->label('Deskripsi')
                                    ->helperText('Penjelasan singkat tentang apa yang terjadi di langkah ini.')
                                    ->rows(3)
                                    ->required(),
                            ]),
                        Section::make('Langkah 2')
                            ->compact()
                            ->schema([
                                TextInput::make('home_step2_title')
                                    ->label('Judul')
                                    ->helperText('Nama langkah kedua.')
                                    ->required(),
                                Textarea::make('home_step2_desc')
                                    ->label('Deskripsi')
                                    ->helperText('Penjelasan singkat tentang apa yang terjadi di langkah ini.')
                                    ->rows(3)
                                    ->required(),
                            ]),
                        Section::make('Langkah 3')
                            ->compact()
                            ->schema([
                                TextInput::make('home_step3_title')
                                    ->label('Judul')
                                    ->helperText('Nama langkah ketiga.')
                                    ->required(),
                                Textarea::make('home_step3_desc')
                                    ->label('Deskripsi')
                                    ->helperText('Penjelasan singkat tentang apa yang terjadi di langkah ini.')
                                    ->rows(3)
                                    ->required(),
                            ]),
                    ]),
                ]),

            Section::make('Ajakan Bertindak (CTA Banner)')
                ->description('Mengatur bagian banner ajakan bertindak paling bawah.')
                ->aside()
                ->collapsible()
                ->schema([
                    TextInput::make('home_cta_title')
                        ->label('Judul CTA')
                        ->placeholder('Contoh: Siap Mengubah Tampilan Kendaraan?')
                        ->helperText('Judul utama yang mengajak pelanggan mengambil tindakan. Buat menarik dan ringkas.')
                        ->required(),
                    Textarea::make('home_cta_subtitle')
                        ->label('Sub-deskripsi CTA')
                        ->placeholder('Tuliskan deskripsi penawaran untuk memikat pelanggan...')
                        ->helperText('Deskripsi pendukung CTA. Jelaskan benefit atau penawaran khusus dalam 1-2 kalimat.')
                        ->required()
                        ->rows(3),
                ]),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => EditBeranda::route('/'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasRole('admin') ?? false;
    }

    public static function canCreate(): bool { return false; }
    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool { return false; }
}
