<?php

namespace App\Filament\Resources\ProfilPerusahaans\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;

class ProfilPerusahaanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Settings')
                    ->tabs([
                        // --- TAB 1: IDENTITAS ---
                        Tabs\Tab::make('Informasi Utama')
                            ->icon('heroicon-m-building-office')
                            ->schema([
                                Section::make()->schema([
                                    TextInput::make('nama_perusahaan')
                                        ->label('Nama Perusahaan')
                                        ->placeholder('Contoh: Dantie Sticker')
                                        ->required()
                                        ->maxLength(255),
                                    Textarea::make('deskripsi')
                                        ->label('Deskripsi Singkat (Footer/Hero)')
                                        ->rows(3),
                                    Textarea::make('sejarah')
                                        ->label('Sejarah Perusahaan')
                                        ->rows(6),
                                ]),
                            ]),

                        // --- TAB 2: KONTAK ---
                        Tabs\Tab::make('Kontak & Lokasi')
                            ->icon('heroicon-m-map-pin')
                            ->schema([
                                Section::make()->schema([
                                    Grid::make(2)->schema([
                                        TextInput::make('email')->email()->required(),
                                        TextInput::make('nomor_telepon')->label('WhatsApp')->tel()->required(),
                                    ]),
                                    TextInput::make('alamat')->required(),
                                    TextInput::make('maps_url')
                                        ->label('Google Maps Embed URL')
                                        ->helperText('Copy bagian src="..." dari kode embed Google Maps'),
                                ]),
                            ]),

                        // --- TAB 3: VISI & MISI ---
                        Tabs\Tab::make('Visi & Misi')
                            ->icon('heroicon-m-sparkles')
                            ->schema([
                                Section::make()->schema([
                                    Textarea::make('visi')->label('Visi')->rows(4),
                                    Textarea::make('misi')->label('Misi')->rows(4),
                                ]),
                            ]),

                        // --- TAB 4: BRANDING ---
                        Tabs\Tab::make('Branding')
                            ->icon('heroicon-m-photo')
                            ->schema([
                                Section::make()->schema([
                                    FileUpload::make('logo')
                                        ->image()
                                        ->imageEditor()
                                        ->directory('logos'),
                                ]),
                            ]),

                        // --- TAB 5: CMS BERANDA ---
                        Tabs\Tab::make('CMS: Beranda')
                            ->icon('heroicon-m-home')
                            ->schema([
                                Section::make('Hero Section (Header Atas)')
                                    ->description('Konten utama yang pertama kali dilihat pengunjung saat membuka website.')
                                    ->icon('heroicon-o-presentation-chart-line')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            Textarea::make('home_title')->label('Judul Besar Hero')->rows(2),
                                            Textarea::make('home_subtitle')->label('Sub-judul Hero')->rows(2),
                                        ]),
                                        FileUpload::make('home_hero_image')->label('Gambar Background Hero')->image()->directory('cms/home'),
                                    ]),

                                Section::make('Professional Section')
                                    ->description('Bagian yang menjelaskan profesionalitas bisnis Anda.')
                                    ->icon('heroicon-o-briefcase')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            TextInput::make('home_prof_title')->label('Judul Professional'),
                                            TextInput::make('home_prof_subtitle')->label('Sub-judul Professional'),
                                        ]),
                                    ]),

                                Section::make('Catalog Section')
                                    ->description('Pengaturan judul untuk daftar layanan/paket.')
                                    ->icon('heroicon-o-shopping-bag')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            TextInput::make('home_catalog_title')->label('Judul Catalog'),
                                            TextInput::make('home_catalog_subtitle')->label('Sub-judul Catalog'),
                                        ]),
                                    ]),

                                Section::make('Recent Activity Section')
                                    ->description('Bagian yang menampilkan riwayat pengerjaan terbaru.')
                                    ->icon('heroicon-o-clock')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            TextInput::make('home_recent_title')->label('Judul Recent Activity'),
                                            TextInput::make('home_recent_subtitle')->label('Sub-judul Recent Activity'),
                                        ]),
                                    ]),

                                Section::make('Final CTA Section')
                                    ->description('Bagian ajakan terakhir di bawah halaman beranda.')
                                    ->icon('heroicon-o-megaphone')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            TextInput::make('home_cta_title')->label('Judul CTA'),
                                            TextInput::make('home_cta_subtitle')->label('Sub-judul CTA'),
                                        ]),
                                    ]),
                            ]),

                        // --- TAB 6: CMS KATALOG & GALERI ---
                        Tabs\Tab::make('CMS: Katalog & Galeri')
                            ->icon('heroicon-m-shopping-bag')
                            ->schema([
                                Grid::make(2)->schema([
                                    Section::make('Halaman Katalog')->schema([
                                        TextInput::make('katalog_title')->label('Judul'),
                                        TextInput::make('katalog_subtitle')->label('Sub-judul'),
                                    ])->columnSpan(1),
                                    Section::make('Halaman Galeri')->schema([
                                        TextInput::make('galeri_title')->label('Judul'),
                                        TextInput::make('galeri_subtitle')->label('Sub-judul'),
                                    ])->columnSpan(1),
                                ]),
                            ]),

                        // --- TAB 7: CMS DASHBOARD & PROSES ---
                        Tabs\Tab::make('CMS: Dashboard & Proses')
                            ->icon('heroicon-m-user-circle')
                            ->schema([
                                Section::make('User Dashboard')->schema([
                                    TextInput::make('dashboard_title')->label('Welcome Title'),
                                    TextInput::make('dashboard_subtitle')->label('Welcome Subtitle'),
                                ]),
                                Section::make('Alur Pemesanan (Step by Step)')->schema([
                                    Grid::make(2)->schema([
                                        Section::make('Step 1')->schema([
                                            TextInput::make('step_1_title')->label('Judul'),
                                            Textarea::make('step_1_desc')->label('Deskripsi')->rows(2),
                                        ])->columnSpan(1),
                                        Section::make('Step 2')->schema([
                                            TextInput::make('step_2_title')->label('Judul'),
                                            Textarea::make('step_2_desc')->label('Deskripsi')->rows(2),
                                        ])->columnSpan(1),
                                        Section::make('Step 3')->schema([
                                            TextInput::make('step_3_title')->label('Judul'),
                                            Textarea::make('step_3_desc')->label('Deskripsi')->rows(2),
                                        ])->columnSpan(1),
                                        Section::make('Step 4')->schema([
                                            TextInput::make('step_4_title')->label('Judul'),
                                            Textarea::make('step_4_desc')->label('Deskripsi')->rows(2),
                                        ])->columnSpan(1),
                                    ]),
                                ]),
                            ]),

                        // --- TAB 8: CMS AUTH ---
                        Tabs\Tab::make('CMS: Auth (Login/Reg)')
                            ->icon('heroicon-m-lock-closed')
                            ->schema([
                                Grid::make(2)->schema([
                                    Section::make('Login Page')->schema([
                                        TextInput::make('login_form_title'),
                                        TextInput::make('login_form_subtitle'),
                                        FileUpload::make('login_image')->image()->directory('cms/auth'),
                                    ])->columnSpan(1),
                                    Section::make('Register Page')->schema([
                                        TextInput::make('register_form_title'),
                                        TextInput::make('register_form_subtitle'),
                                        FileUpload::make('register_image')->image()->directory('cms/auth'),
                                    ])->columnSpan(1),
                                ]),
                            ]),

                        // --- TAB 9: CMS TESTIMONI ---
                        Tabs\Tab::make('CMS: Testimoni')
                            ->icon('heroicon-m-chat-bubble-bottom-center-text')
                            ->schema([
                                Section::make('Kelola Testimoni Pelanggan')
                                    ->description('Testimoni yang Anda masukkan di sini akan muncul di halaman depan.')
                                    ->schema([
                                        \Filament\Forms\Components\Repeater::make('testimonis_json')
                                            ->label('Daftar Testimoni')
                                            ->addActionLabel('Tambah Testimoni')
                                            ->schema([
                                                Grid::make(2)->schema([
                                                    TextInput::make('nama')->required(),
                                                    TextInput::make('jabatan')->placeholder('Contoh: Pelanggan Setia / CEO'),
                                                ]),
                                                Textarea::make('isi')->label('Isi Testimoni')->required()->rows(3),
                                                Grid::make(2)->schema([
                                                    FileUpload::make('foto')->image()->directory('testimonis'),
                                                    \Filament\Forms\Components\Select::make('rating')
                                                        ->options([
                                                            5 => '⭐⭐⭐⭐⭐ (5 Bintang)',
                                                            4 => '⭐⭐⭐⭐ (4 Bintang)',
                                                            3 => '⭐⭐⭐ (3 Bintang)',
                                                            2 => '⭐⭐ (2 Bintang)',
                                                            1 => '⭐ (1 Bintang)',
                                                        ])
                                                        ->default(5),
                                                ]),
                                            ])
                                            ->collapsible()
                                            ->itemLabel(fn (array $state): ?string => $state['nama'] ?? null)
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        // --- TAB 10: CMS PROFIL (ABOUT US) ---
                        Tabs\Tab::make('CMS: Profil (About)')
                            ->icon('heroicon-m-user-group')
                            ->schema([
                                Section::make('Hero & Stats')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            TextInput::make('about_hero_title')->label('Judul Hero Profil'),
                                            TextInput::make('about_hero_subtitle')->label('Sub-judul Hero Profil'),
                                        ]),
                                        Grid::make(4)->schema([
                                            TextInput::make('stats_experience')->label('Thn Pengalaman')->placeholder('5+'),
                                            TextInput::make('stats_projects')->label('Project Selesai')->placeholder('1.2k'),
                                            TextInput::make('stats_satisfaction')->label('Kepuasan')->placeholder('99%'),
                                            TextInput::make('stats_support')->label('Support')->placeholder('24h'),
                                        ]),
                                    ]),
                                Section::make('Feature Highlight')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            TextInput::make('about_feature_title')->label('Judul Fitur Utama'),
                                            FileUpload::make('about_feature_image')->label('Gambar Fitur')->image()->directory('cms/about'),
                                        ]),
                                        Textarea::make('about_feature_desc')->label('Deskripsi Fitur')->rows(3),
                                        \Filament\Forms\Components\Repeater::make('about_feature_list')
                                            ->label('Daftar Poin Fitur')
                                            ->simple(
                                                TextInput::make('item')->required(),
                                            )
                                            ->addActionLabel('Tambah Poin')
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull()
                    ->persistTabInQueryString(),
            ]);
    }
}
