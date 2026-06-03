<?php

namespace App\Filament\Resources\Galeris\Schemas;

use App\Settings\GaleriSettings;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class GaleriForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('judul')
                ->label('Judul Pekerjaan')
                ->placeholder('Contoh: Sticker Custom Premium untuk Mobil Avanza')
                ->required()
                ->maxLength(255)
                ->columnSpan(2)
                ->helperText('Judul yang menarik untuk galeri/portofolio. Akan ditampilkan di halaman galeri.'),

            TextInput::make('sub_judul')
                ->label('Sub Judul (Opsional)')
                ->placeholder('Contoh: Premium Design dengan Tinta Berkualitas')
                ->maxLength(255)
                ->columnSpan(1)
                ->helperText('Detail tambahan atau kategori jenis pekerjaan.'),

            Textarea::make('deskripsi')
                ->label('Deskripsi Pekerjaan')
                ->placeholder('Tuliskan detail cerita, proses, dan pencapaian di balik pekerjaan ini...')
                ->rows(5)
                ->columnSpanFull()
                ->helperText('Cerita lengkap tentang pekerjaan ini. Jelaskan proses, material yang digunakan, dan hasil akhir.'),

            FileUpload::make('foto')
                ->label('Foto Utama Galeri')
                ->image()
                ->imageEditor()
                ->directory('galeri')
                ->disk('public')
                ->maxSize(10240)
                ->required()
                ->columnSpanFull()
                ->helperText('Format: JPG, PNG, WebP. Maksimal 10MB. Ukuran rekomendasi: 1200x800px untuk hasil optimal di website.'),

            Select::make('kategori')
                ->label('Kategori')
                ->options(function () {
                    $settings = app(GaleriSettings::class);
                    $categories = $settings->galeri_filter_categories ?? [];
                    $options = [];
                    foreach ($categories as $cat) {
                        if (isset($cat['slug'], $cat['label'])) {
                            $options[$cat['slug']] = $cat['label'];
                        }
                    }
                    return $options;
                })
                ->columnSpan(1)
                ->native(false)
                ->helperText('Pilih kategori pekerjaan. Kategori dikelola di menu Edit Galeri > Filter Kategori.'),

            TextInput::make('badge_text')
                ->label('Teks Badge (Label)')
                ->placeholder('Contoh: Featured, Best Seller, Recommended, Premium')
                ->columnSpan(1)
                ->helperText('Label khusus yang akan ditampilkan di sudut foto sebagai penanda spesial.'),

            DatePicker::make('tanggal_upload')
                ->label('Tanggal Upload')
                ->required()
                ->columnSpan(1)
                ->helperText('Tanggal pekerjaan ini selesai/dipublikasikan.'),

            Toggle::make('is_featured')
                ->label('Tampilkan sebagai Featured?')
                ->columnSpan(1)
                ->helperText('Jika aktif, pekerjaan ini akan ditampilkan di bagian "Featured Works" di halaman utama galeri.')
                ->default(false),
        ]);
    }
}
