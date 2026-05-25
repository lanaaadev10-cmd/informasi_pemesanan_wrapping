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

    protected static ?string $label = 'Edit Beranda';
    protected static ?string $pluralLabel = 'Edit Beranda';
    protected static ?string $navigationLabel = 'Edit Beranda';
    protected static string|null|\UnitEnum $navigationGroup = 'Halaman Website';
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
                            ->required()
                            ->columnSpanFull(),
                        TextInput::make('home_hero_title_line1')
                            ->label('Judul Hero Baris 1')
                            ->placeholder('Contoh: Elevasi Estetika')
                            ->required(),
                        TextInput::make('home_hero_title_line2')
                            ->label('Judul Hero Baris 2')
                            ->placeholder('Contoh: Aset Mewah Anda.')
                            ->required(),
                        Textarea::make('home_subtitle')
                            ->label('Sub-deskripsi Hero')
                            ->placeholder('Tuliskan deskripsi singkat penawaran...')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
                    Grid::make(4)->schema([
                        TextInput::make('home_stat1_value')
                            ->label('Statistik 1 Angka')
                            ->placeholder('Contoh: 500+'),
                        TextInput::make('home_stat1_label')
                            ->label('Statistik 1 Label')
                            ->placeholder('Contoh: Supercars Wrapped'),
                        TextInput::make('home_stat2_value')
                            ->label('Statistik 2 Angka')
                            ->placeholder('Contoh: 5 Tahun'),
                        TextInput::make('home_stat2_label')
                            ->label('Statistik 2 Label')
                            ->placeholder('Contoh: Garansi Material'),
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
                                    ->required(),
                                Textarea::make('home_keunggulan_card1_desc')
                                    ->label('Deskripsi')
                                    ->rows(3)
                                    ->required(),
                            ]),
                        Section::make('Keunggulan 2')
                            ->compact()
                            ->schema([
                                TextInput::make('home_keunggulan_card2_title')
                                    ->label('Judul')
                                    ->required(),
                                Textarea::make('home_keunggulan_card2_desc')
                                    ->label('Deskripsi')
                                    ->rows(3)
                                    ->required(),
                            ]),
                        Section::make('Keunggulan 3')
                            ->compact()
                            ->schema([
                                TextInput::make('home_keunggulan_card3_title')
                                    ->label('Judul')
                                    ->required(),
                                Textarea::make('home_keunggulan_card3_desc')
                                    ->label('Deskripsi')
                                    ->rows(3)
                                    ->required(),
                            ]),
                        Section::make('Keunggulan 4')
                            ->compact()
                            ->schema([
                                TextInput::make('home_keunggulan_card4_title')
                                    ->label('Judul')
                                    ->required(),
                                Textarea::make('home_keunggulan_card4_desc')
                                    ->label('Deskripsi')
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
                                    ->required(),
                                Textarea::make('home_step1_desc')
                                    ->label('Deskripsi')
                                    ->rows(3)
                                    ->required(),
                            ]),
                        Section::make('Langkah 2')
                            ->compact()
                            ->schema([
                                TextInput::make('home_step2_title')
                                    ->label('Judul')
                                    ->required(),
                                Textarea::make('home_step2_desc')
                                    ->label('Deskripsi')
                                    ->rows(3)
                                    ->required(),
                            ]),
                        Section::make('Langkah 3')
                            ->compact()
                            ->schema([
                                TextInput::make('home_step3_title')
                                    ->label('Judul')
                                    ->required(),
                                Textarea::make('home_step3_desc')
                                    ->label('Deskripsi')
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
                        ->required(),
                    Textarea::make('home_cta_subtitle')
                        ->label('Sub-deskripsi CTA')
                        ->placeholder('Tuliskan deskripsi penawaran untuk memikat pelanggan...')
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
