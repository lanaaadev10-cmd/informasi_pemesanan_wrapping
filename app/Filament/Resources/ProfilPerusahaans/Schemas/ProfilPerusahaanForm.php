<?php

namespace App\Filament\Resources\ProfilPerusahaans\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ColorPicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

/**
 * Form Schema untuk ProfilPerusahaan Resource.
 *
 * Menggunakan Tabs agar form CMS terorganisir secara profesional.
 * Tab 1: Informasi Utama (nama, deskripsi, kontak)
 * Tab 2: Tentang Kami (visi, misi, sejarah)
 * Tab 3: Sosial Media
 * Tab 4: Branding & SEO
 */
class ProfilPerusahaanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Profil Perusahaan')
                    ->tabs([
                        // ============================
                        // TAB 1: INFORMASI UTAMA
                        // ============================
                        Tab::make('Informasi Utama')
                            ->icon('heroicon-o-building-office')
                            ->schema([
                                Section::make('Identitas Perusahaan')
                                    ->description('Nama dan deskripsi utama perusahaan.')
                                    ->aside()
                                    ->schema([
                                        TextInput::make('nama_perusahaan')
                                            ->label('Nama Perusahaan')
                                            ->placeholder('Contoh: Dantie Sticker')
                                            ->required()
                                            ->maxLength(255)
                                            ->prefixIcon('heroicon-m-building-office'),

                                        Textarea::make('deskripsi')
                                            ->label('Deskripsi / Tagline')
                                            ->placeholder('Tuliskan visi singkat atau keunggulan perusahaan...')
                                            ->required()
                                            ->rows(4)
                                            ->columnSpanFull(),
                                    ]),

                                Section::make('Kontak & Lokasi')
                                    ->description('Info kontak agar pelanggan bisa menghubungi.')
                                    ->aside()
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                TextInput::make('email')
                                                    ->label('Alamat Email')
                                                    ->email()
                                                    ->required()
                                                    ->prefixIcon('heroicon-m-envelope'),

                                                TextInput::make('nomor_telepon')
                                                    ->label('Nomor WhatsApp / Telepon')
                                                    ->tel()
                                                    ->required()
                                                    ->prefixIcon('heroicon-m-phone'),
                                            ]),

                                        TextInput::make('alamat')
                                            ->label('Alamat Lengkap')
                                            ->placeholder('Jl. Contoh No. 123, Kota...')
                                            ->required()
                                            ->prefixIcon('heroicon-m-map-pin'),

                                        TextInput::make('maps_url')
                                            ->label('Google Maps Embed URL')
                                            ->placeholder('https://www.google.com/maps/embed?pb=...')
                                            ->helperText('Buka Google Maps → Share → Embed a map → copy bagian src="..." nya saja.')
                                            ->prefixIcon('heroicon-m-globe-alt')
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        // ============================
                        // TAB 2: TENTANG KAMI (CMS)
                        // ============================
                        Tab::make('Tentang Kami')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Section::make('Visi & Misi')
                                    ->description('Konten halaman Tentang Kami yang bisa diedit oleh admin.')
                                    ->aside()
                                    ->schema([
                                        RichEditor::make('visi')
                                            ->label('Visi Perusahaan')
                                            ->placeholder('Tuliskan visi perusahaan...')
                                            ->toolbarButtons([
                                                'bold', 'italic', 'underline',
                                                'bulletList', 'orderedList',
                                                'h2', 'h3',
                                            ])
                                            ->columnSpanFull(),

                                        RichEditor::make('misi')
                                            ->label('Misi Perusahaan')
                                            ->placeholder('Tuliskan misi perusahaan...')
                                            ->toolbarButtons([
                                                'bold', 'italic', 'underline',
                                                'bulletList', 'orderedList',
                                                'h2', 'h3',
                                            ])
                                            ->columnSpanFull(),
                                    ]),

                                Section::make('Sejarah Perusahaan')
                                    ->description('Ceritakan perjalanan perusahaan dari awal hingga saat ini.')
                                    ->aside()
                                    ->collapsible()
                                    ->schema([
                                        RichEditor::make('sejarah')
                                            ->label('Sejarah')
                                            ->placeholder('Tuliskan sejarah singkat perusahaan...')
                                            ->toolbarButtons([
                                                'bold', 'italic', 'underline',
                                                'bulletList', 'orderedList',
                                                'h2', 'h3', 'blockquote',
                                            ])
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        // ============================
                        // TAB 3: SOSIAL MEDIA
                        // ============================
                        Tab::make('Sosial Media')
                            ->icon('heroicon-o-share')
                            ->schema([
                                Section::make('Link Sosial Media')
                                    ->description('Masukkan URL akun sosial media perusahaan.')
                                    ->aside()
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                TextInput::make('instagram_url')
                                                    ->label('Instagram')
                                                    ->placeholder('https://instagram.com/...')
                                                    ->url()
                                                    ->prefixIcon('heroicon-m-camera'),

                                                TextInput::make('facebook_url')
                                                    ->label('Facebook')
                                                    ->placeholder('https://facebook.com/...')
                                                    ->url()
                                                    ->prefixIcon('heroicon-m-hand-thumb-up'),

                                                TextInput::make('tiktok_url')
                                                    ->label('TikTok')
                                                    ->placeholder('https://tiktok.com/@...')
                                                    ->url()
                                                    ->prefixIcon('heroicon-m-play'),

                                                TextInput::make('whatsapp_url')
                                                    ->label('WhatsApp (Link Langsung)')
                                                    ->placeholder('https://wa.me/628...')
                                                    ->url()
                                                    ->prefixIcon('heroicon-m-chat-bubble-left'),
                                            ]),
                                    ]),
                            ]),

                        // ============================
                        // TAB 4: BRANDING & SEO
                        // ============================
                        Tab::make('Branding & SEO')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                Section::make('Logo Perusahaan')
                                    ->description('Upload logo untuk ditampilkan di website.')
                                    ->aside()
                                    ->schema([
                                        FileUpload::make('logo')
                                            ->label('Logo Perusahaan')
                                            ->image()
                                            ->imageEditor()
                                            ->disk('public')
                                            ->directory('logos')
                                            ->visibility('public')
                                            ->maxSize(10240)
                                            ->helperText('Gunakan gambar PNG transparan untuk hasil terbaik (Maks. 10MB).')
                                            ->columnSpanFull(),
                                    ]),

                                Section::make('SEO & Metadata')
                                    ->description('Optimasi mesin pencari (Google) untuk website.')
                                    ->aside()
                                    ->collapsible()
                                    ->schema([
                                        TextInput::make('meta_title')
                                            ->label('Meta Title')
                                            ->placeholder('Dantie Sticker — Jasa Wrapping Profesional')
                                            ->maxLength(70)
                                            ->helperText('Judul yang muncul di hasil pencarian Google (maks. 70 karakter).')
                                            ->prefixIcon('heroicon-m-magnifying-glass'),

                                        Textarea::make('meta_description')
                                            ->label('Meta Description')
                                            ->placeholder('Jasa wrapping kendaraan dan stiker berkualitas premium...')
                                            ->rows(3)
                                            ->helperText('Deskripsi singkat untuk hasil pencarian Google (maks. 160 karakter).'),
                                    ]),
                            ]),
                        // ============================
                        // TAB 5: BERANDA (CMS)
                        // ============================
                        Tab::make('Beranda')
                            ->icon('heroicon-o-home')
                            ->schema([
                                // --- HERO SECTION ---
                                Section::make('Hero — Judul & Tagline')
                                    ->description('Konten utama yang tampil pertama kali di halaman Beranda.')
                                    ->aside()
                                    ->schema([
                                        TextInput::make('home_badge_text')
                                            ->label('Label Pill (Badge)')
                                            ->placeholder('Professional Car Wrapping Indonesia')
                                            ->helperText('Teks kecil di atas judul besar.')
                                            ->maxLength(100)
                                            ->prefixIcon('heroicon-m-tag'),

                                        Grid::make(2)->schema([
                                            TextInput::make('home_hero_title_line1')
                                                ->label('Judul Baris 1')
                                                ->placeholder('Elevasi Estetika')
                                                ->maxLength(60),

                                            TextInput::make('home_hero_title_line2')
                                                ->label('Judul Baris 2 (Highlight Oranye)')
                                                ->placeholder('Aset Mewah Anda.')
                                                ->maxLength(60),
                                        ]),

                                        Textarea::make('home_subtitle')
                                            ->label('Subjudul / Deskripsi Hero')
                                            ->placeholder('Layanan premium yang melindungi dan memperindah mobil kesayangan Anda...')
                                            ->rows(3)
                                            ->columnSpanFull(),
                                    ]),

                                // --- STATISTIK MINI ---
                                Section::make('Statistik Singkat')
                                    ->description('Dua angka statistik yang tampil di bawah CTA hero.')
                                    ->aside()
                                    ->collapsible()
                                    ->schema([
                                        Grid::make(2)->schema([
                                            TextInput::make('home_stat1_value')
                                                ->label('Nilai Statistik 1')
                                                ->placeholder('500+'),
                                            TextInput::make('home_stat1_label')
                                                ->label('Label Statistik 1')
                                                ->placeholder('Supercars Wrapped'),
                                            TextInput::make('home_stat2_value')
                                                ->label('Nilai Statistik 2')
                                                ->placeholder('5 Tahun'),
                                            TextInput::make('home_stat2_label')
                                                ->label('Label Statistik 2')
                                                ->placeholder('Garansi Material'),
                                        ]),
                                    ]),

                                // --- KEUNGGULAN (4 Cards) ---
                                Section::make('Keunggulan Layanan — 4 Kartu')
                                    ->description('Konten 4 kartu pada seksi "Mengapa Memilih Wapping?".')
                                    ->aside()
                                    ->collapsible()
                                    ->schema([
                                        Grid::make(2)->schema([
                                            TextInput::make('home_keunggulan_card1_title')
                                                ->label('Kartu 1 — Judul')
                                                ->placeholder('Kualitas Material Grade-A'),
                                            Textarea::make('home_keunggulan_card1_desc')
                                                ->label('Kartu 1 — Deskripsi')
                                                ->rows(3),

                                            TextInput::make('home_keunggulan_card2_title')
                                                ->label('Kartu 2 — Judul')
                                                ->placeholder('Teknisi Tersertifikasi'),
                                            Textarea::make('home_keunggulan_card2_desc')
                                                ->label('Kartu 2 — Deskripsi')
                                                ->rows(3),

                                            TextInput::make('home_keunggulan_card3_title')
                                                ->label('Kartu 3 — Judul')
                                                ->placeholder('Pengerjaan Tepat Waktu'),
                                            Textarea::make('home_keunggulan_card3_desc')
                                                ->label('Kartu 3 — Deskripsi')
                                                ->rows(3),

                                            TextInput::make('home_keunggulan_card4_title')
                                                ->label('Kartu 4 — Judul (Oranye)')
                                                ->placeholder('Garansi Hingga 5 Tahun'),
                                            Textarea::make('home_keunggulan_card4_desc')
                                                ->label('Kartu 4 — Deskripsi')
                                                ->rows(3),
                                        ]),
                                    ]),

                                // --- CTA SECTION ---
                                Section::make('Seksi CTA — "Siap Mengubah?"')
                                    ->description('Teks pada bagian ajakan bertindak di tengah halaman.')
                                    ->aside()
                                    ->collapsible()
                                    ->schema([
                                        TextInput::make('home_cta_title')
                                            ->label('Judul CTA')
                                            ->placeholder('Siap Mengubah Tampilan Kendaraan?')
                                            ->maxLength(100),
                                        Textarea::make('home_cta_subtitle')
                                            ->label('Subjudul CTA')
                                            ->placeholder('Hubungi konsultan desain gratis sekarang...')
                                            ->rows(3)
                                            ->columnSpanFull(),
                                    ]),

                                // --- LANGKAH MUDAH (3 Steps) ---
                                Section::make('Langkah Mudah — 3 Tahap')
                                    ->description('Teks pada 3 langkah proses yang tampil di samping CTA.')
                                    ->aside()
                                    ->collapsible()
                                    ->schema([
                                        Grid::make(2)->schema([
                                            TextInput::make('home_step1_title')
                                                ->label('Tahap 1 — Judul')
                                                ->placeholder('Konsultasi & Estimasi'),
                                            Textarea::make('home_step1_desc')
                                                ->label('Tahap 1 — Deskripsi')
                                                ->rows(2),

                                            TextInput::make('home_step2_title')
                                                ->label('Tahap 2 — Judul')
                                                ->placeholder('Pilihan Wrapping'),
                                            Textarea::make('home_step2_desc')
                                                ->label('Tahap 2 — Deskripsi')
                                                ->rows(2),

                                            TextInput::make('home_step3_title')
                                                ->label('Tahap 3 — Judul')
                                                ->placeholder('Pengerjaan Rapi'),
                                            Textarea::make('home_step3_desc')
                                                ->label('Tahap 3 — Deskripsi')
                                                ->rows(2),
                                        ]),
                                    ]),
                            ]),
                        // ============================
                        // TAB 6: DASHBOARD CUSTOMER (CMS)
                        // ============================
                        Tab::make('Dashboard Customer')
                            ->icon('heroicon-o-user')
                            ->schema([
                                Section::make('Greeting Header & Empty State')
                                    ->description('Konfigurasi teks sambutan dan tampilan ketika tidak ada pengerjaan aktif.')
                                    ->aside()
                                    ->schema([
                                        TextInput::make('dashboard_title')
                                            ->label('Judul Greeting (Halo, [Nama User])')
                                            ->placeholder('Halo')
                                            ->helperText('Teks pembuka greeting.')
                                            ->maxLength(100)
                                            ->prefixIcon('heroicon-m-hand-raised'),

                                        TextInput::make('dashboard_subtitle')
                                            ->label('Subjudul Greeting')
                                            ->placeholder('Pantau status pengerjaan kendaraan premium Anda di sini.')
                                            ->maxLength(255)
                                            ->prefixIcon('heroicon-m-information-circle'),

                                        TextInput::make('dashboard_empty_title')
                                            ->label('Judul Tanpa Pengerjaan')
                                            ->placeholder('Tidak Ada Pengerjaan Aktif')
                                            ->maxLength(100)
                                            ->prefixIcon('heroicon-m-inbox'),

                                        Textarea::make('dashboard_empty_desc')
                                            ->label('Deskripsi Tanpa Pengerjaan')
                                            ->placeholder('Anda tidak memiliki pesanan kendaraan yang sedang dikerjakan saat ini...')
                                            ->rows(3),
                                    ]),

                                Section::make('Member Status Card')
                                    ->description('Konfigurasi kartu status keanggotaan/member premium.')
                                    ->aside()
                                    ->collapsible()
                                    ->schema([
                                        TextInput::make('dashboard_member_title')
                                            ->label('Judul Tingkat Member')
                                            ->placeholder('Premium Gold')
                                            ->maxLength(100)
                                            ->prefixIcon('heroicon-m-academic-cap'),

                                        TextInput::make('dashboard_member_desc')
                                            ->label('Keterangan Tingkat Member')
                                            ->placeholder('Satu langkah lagi menuju Platinum')
                                            ->maxLength(255)
                                            ->prefixIcon('heroicon-m-arrow-trending-up'),

                                        TextInput::make('dashboard_member_progress')
                                            ->label('Progress Bar (%)')
                                            ->numeric()
                                            ->minValue(0)
                                            ->maxValue(100)
                                            ->placeholder('85')
                                            ->prefixIcon('heroicon-m-chart-bar'),

                                        RichEditor::make('dashboard_member_benefits')
                                            ->label('Keuntungan & Hak Istimewa Member')
                                            ->placeholder('Tuliskan diskon dan hak prioritas member...')
                                            ->toolbarButtons([
                                                'bold', 'italic', 'underline',
                                                'bulletList', 'orderedList',
                                            ])
                                            ->columnSpanFull(),
                                    ]),

                                Section::make('Layanan Cepat (4 Cards)')
                                    ->description('Konfigurasi 4 kartu layanan cepat di bawah dashboard.')
                                    ->aside()
                                    ->collapsible()
                                    ->schema([
                                        Grid::make(2)->schema([
                                            // Card 1
                                            TextInput::make('dashboard_service_1_title')
                                                ->label('Layanan Cepat 1 — Judul')
                                                ->placeholder('Paint Protection Film'),
                                            TextInput::make('dashboard_service_1_icon')
                                                ->label('Layanan Cepat 1 — Ikon (Phosphor ph-*)')
                                                ->placeholder('ph-shield')
                                                ->helperText('Contoh: ph-shield, ph-palette, ph-armchair, ph-sparkles'),
                                            Textarea::make('dashboard_service_1_desc')
                                                ->label('Layanan Cepat 1 — Deskripsi')
                                                ->rows(2)
                                                ->columnSpanFull(),

                                            // Card 2
                                            TextInput::make('dashboard_service_2_title')
                                                ->label('Layanan Cepat 2 — Judul')
                                                ->placeholder('Color Change'),
                                            TextInput::make('dashboard_service_2_icon')
                                                ->label('Layanan Cepat 2 — Ikon (Phosphor ph-*)')
                                                ->placeholder('ph-palette'),
                                            Textarea::make('dashboard_service_2_desc')
                                                ->label('Layanan Cepat 2 — Deskripsi')
                                                ->rows(2)
                                                ->columnSpanFull(),

                                            // Card 3
                                            TextInput::make('dashboard_service_3_title')
                                                ->label('Layanan Cepat 3 — Judul')
                                                ->placeholder('Interior Styling'),
                                            TextInput::make('dashboard_service_3_icon')
                                                ->label('Layanan Cepat 3 — Ikon (Phosphor ph-*)')
                                                ->placeholder('ph-armchair'),
                                            Textarea::make('dashboard_service_3_desc')
                                                ->label('Layanan Cepat 3 — Deskripsi')
                                                ->rows(2)
                                                ->columnSpanFull(),

                                            // Card 4
                                            TextInput::make('dashboard_service_4_title')
                                                ->label('Layanan Cepat 4 — Judul')
                                                ->placeholder('Detailing'),
                                            TextInput::make('dashboard_service_4_icon')
                                                ->label('Layanan Cepat 4 — Ikon (Phosphor ph-*)')
                                                ->placeholder('ph-sparkles'),
                                            Textarea::make('dashboard_service_4_desc')
                                                ->label('Layanan Cepat 4 — Deskripsi')
                                                ->rows(2)
                                                ->columnSpanFull(),
                                        ]),
                                    ]),
                            ]),
                        // ============================
                        // TAB 7: TENTANG KAMI (HALAMAN)
                        // ============================
                        Tab::make('Halaman Tentang Kami')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Section::make('Hero Section')
                                    ->description('Bagian pembuka halaman Tentang Kami')
                                    ->aside()
                                    ->schema([
                                        TextInput::make('tentang_kami_hero_title')
                                            ->label('Judul Hero')
                                            ->placeholder('Tentang Premium Wrap Studio')
                                            ->maxLength(255)
                                            ->columnSpanFull(),

                                        Textarea::make('tentang_kami_hero_desc')
                                            ->label('Deskripsi Hero')
                                            ->placeholder('Kami adalah studio profesional yang berdedikasi untuk memberikan layanan wrapping terbaik...')
                                            ->rows(4)
                                            ->columnSpanFull(),
                                    ]),

                                Section::make('Section Tim')
                                    ->description('Informasi tentang tim profesional')
                                    ->aside()
                                    ->schema([
                                        TextInput::make('tentang_kami_team_title')
                                            ->label('Judul Section Tim')
                                            ->placeholder('Dibalik Setiap Detail Sempurna')
                                            ->maxLength(255)
                                            ->columnSpanFull(),

                                        Textarea::make('tentang_kami_team_desc')
                                            ->label('Deskripsi Section Tim')
                                            ->placeholder('Tim profesional kami terdiri dari teknisi bersertifikat dengan pengalaman puluhan tahun...')
                                            ->rows(4)
                                            ->columnSpanFull(),
                                    ]),

                                Section::make('Data Tim (JSON)')
                                    ->description('Format: [{"nama": "Nama", "posisi": "Posisi", "foto": "path/to/image"}]')
                                    ->aside()
                                    ->collapsible()
                                    ->schema([
                                        Textarea::make('tentang_kami_team_members')
                                            ->label('Data Tim JSON')
                                            ->placeholder('[{"nama": "Adrian Wirya", "posisi": "Lead Technician", "foto": "team/adrian.jpg"}]')
                                            ->rows(6)
                                            ->columnSpanFull()
                                            ->helperText('Setiap member harus memiliki: nama, posisi, foto'),
                                    ]),
                            ]),

                        // ============================
                        // TAB 8: LAYANAN (HALAMAN)
                        // ============================
                        Tab::make('Halaman Layanan')
                            ->icon('heroicon-o-sparkles')
                            ->schema([
                                Section::make('Hero Section')
                                    ->description('Bagian pembuka halaman Layanan')
                                    ->aside()
                                    ->schema([
                                        TextInput::make('layanan_hero_title')
                                            ->label('Judul Hero')
                                            ->placeholder('Precision in Every Layer')
                                            ->maxLength(255)
                                            ->columnSpanFull(),

                                        Textarea::make('layanan_hero_desc')
                                            ->label('Deskripsi Hero')
                                            ->placeholder('Pilih dari berbagai kategori wrapping premium untuk kendaraan kesayangan Anda...')
                                            ->rows(3)
                                            ->columnSpanFull(),
                                    ]),

                                // --- LAYANAN 1 ---
                                Section::make('Layanan 1 — Stealth Matte')
                                    ->description('Konfigurasi layanan pertama')
                                    ->aside()
                                    ->collapsible()
                                    ->schema([
                                        Grid::make(2)->schema([
                                            TextInput::make('layanan_1_nama')
                                                ->label('Nama Layanan')
                                                ->placeholder('Stealth Matte'),
                                            TextInput::make('layanan_1_harga')
                                                ->label('Harga')
                                                ->placeholder('Rp 12.500.000'),
                                        ]),

                                        Textarea::make('layanan_1_fitur')
                                            ->label('Fitur (JSON Array, pisahkan dengan comma)')
                                            ->placeholder('["Proteksi UV 10 Tahun", "Garansi 5 Tahun", "Proses 3 Hari"]')
                                            ->rows(3)
                                            ->columnSpanFull()
                                            ->helperText('Format: ["fitur1", "fitur2", "fitur3"]'),

                                        FileUpload::make('layanan_1_gambar')
                                            ->label('Gambar Layanan')
                                            ->image()
                                            ->disk('public')
                                            ->directory('services')
                                            ->visibility('public')
                                            ->maxSize(10240)
                                            ->columnSpanFull(),
                                    ]),

                                // --- LAYANAN 2 ---
                                Section::make('Layanan 2 — Mirror Glossy')
                                    ->description('Konfigurasi layanan kedua')
                                    ->aside()
                                    ->collapsible()
                                    ->schema([
                                        Grid::make(2)->schema([
                                            TextInput::make('layanan_2_nama')
                                                ->label('Nama Layanan')
                                                ->placeholder('Mirror Glossy'),
                                            TextInput::make('layanan_2_harga')
                                                ->label('Harga')
                                                ->placeholder('Rp 11.800.000'),
                                        ]),

                                        Textarea::make('layanan_2_fitur')
                                            ->label('Fitur (JSON Array)')
                                            ->placeholder('["Kilau Premium", "Tahan Air", "Proses 3 Hari"]')
                                            ->rows(3)
                                            ->columnSpanFull(),

                                        FileUpload::make('layanan_2_gambar')
                                            ->label('Gambar Layanan')
                                            ->image()
                                            ->disk('public')
                                            ->directory('services')
                                            ->visibility('public')
                                            ->maxSize(10240)
                                            ->columnSpanFull(),
                                    ]),

                                // --- LAYANAN 3 ---
                                Section::make('Layanan 3 — Satin Silk')
                                    ->description('Konfigurasi layanan ketiga')
                                    ->aside()
                                    ->collapsible()
                                    ->schema([
                                        Grid::make(2)->schema([
                                            TextInput::make('layanan_3_nama')
                                                ->label('Nama Layanan')
                                                ->placeholder('Satin Silk'),
                                            TextInput::make('layanan_3_harga')
                                                ->label('Harga')
                                                ->placeholder('Rp 13.200.000'),
                                        ]),

                                        Textarea::make('layanan_3_fitur')
                                            ->label('Fitur (JSON Array)')
                                            ->placeholder('["Tekstur Silk", "Anti Gores", "Proses 3 Hari"]')
                                            ->rows(3)
                                            ->columnSpanFull(),

                                        FileUpload::make('layanan_3_gambar')
                                            ->label('Gambar Layanan')
                                            ->image()
                                            ->disk('public')
                                            ->directory('services')
                                            ->visibility('public')
                                            ->maxSize(10240)
                                            ->columnSpanFull(),
                                    ]),

                                // --- LAYANAN 4 ---
                                Section::make('Layanan 4 — Paint Protection')
                                    ->description('Konfigurasi layanan keempat')
                                    ->aside()
                                    ->collapsible()
                                    ->schema([
                                        Grid::make(2)->schema([
                                            TextInput::make('layanan_4_nama')
                                                ->label('Nama Layanan')
                                                ->placeholder('Paint Protection'),
                                            TextInput::make('layanan_4_harga')
                                                ->label('Harga')
                                                ->placeholder('Rp 25.000.000'),
                                        ]),

                                        Textarea::make('layanan_4_fitur')
                                            ->label('Fitur (JSON Array)')
                                            ->placeholder('["Self-healing", "Tahan 10 Tahun", "Proses 5 Hari"]')
                                            ->rows(3)
                                            ->columnSpanFull(),

                                        FileUpload::make('layanan_4_gambar')
                                            ->label('Gambar Layanan')
                                            ->image()
                                            ->disk('public')
                                            ->directory('services')
                                            ->visibility('public')
                                            ->maxSize(10240)
                                            ->columnSpanFull(),
                                    ]),

                                // --- SECTION GARANSI ---
                                Section::make('Section Garansi & CTA')
                                    ->description('Bagian informasi garansi dan ajakan bertindak')
                                    ->aside()
                                    ->collapsible()
                                    ->schema([
                                        TextInput::make('layanan_garansi_title')
                                            ->label('Judul Garansi')
                                            ->placeholder('Garansi Resmi')
                                            ->maxLength(255),

                                        Textarea::make('layanan_garansi_desc')
                                            ->label('Deskripsi Garansi')
                                            ->placeholder('Setiap layanan dilengkapi dengan garansi resmi...')
                                            ->rows(3),

                                        TextInput::make('layanan_cta_title')
                                            ->label('Judul CTA')
                                            ->placeholder('Siap Mengubah Tampilan Kendaraan Anda?')
                                            ->maxLength(255),

                                        Textarea::make('layanan_cta_desc')
                                            ->label('Deskripsi CTA')
                                            ->placeholder('Hubungi konsultan desain gratis sekarang dan dapatkan estimasi profesional...')
                                            ->rows(3),
                                    ]),
                            ]),

                        // ============================
                        // TAB 9: KONFIGURASI TENTANG KAMI
                        // ============================
                        Tab::make('Konfigurasi Tentang Kami')
                            ->icon('heroicon-o-cog-6-tooth')
                            ->schema([
                                Section::make('Layout Configuration')
                                    ->description('Customize tampilan halaman Tentang Kami')
                                    ->aside()
                                    ->schema([
                                        Select::make('tentang_kami_values_columns')
                                            ->label('Jumlah Kolom Section Nilai')
                                            ->options([
                                                3 => '3 Kolom (Recommended)',
                                                4 => '4 Kolom',
                                            ])
                                            ->default(3)
                                            ->native(false)
                                            ->helperText('Pilih berapa kolom untuk menampilkan nilai/komitmen perusahaan'),

                                        Grid::make(3)->schema([
                                            Toggle::make('tentang_kami_show_values')
                                                ->label('Tampilkan Section Nilai')
                                                ->default(true)
                                                ->inline(),

                                            Toggle::make('tentang_kami_show_history')
                                                ->label('Tampilkan Section Sejarah')
                                                ->default(true)
                                                ->inline(),

                                            Toggle::make('tentang_kami_show_team')
                                                ->label('Tampilkan Section Tim')
                                                ->default(true)
                                                ->inline(),
                                        ]),
                                    ]),
                            ]),

                        // ============================
                        // TAB 10: KONFIGURASI LAYANAN
                        // ============================
                        Tab::make('Konfigurasi Layanan')
                            ->icon('heroicon-o-cog-6-tooth')
                            ->schema([
                                Section::make('Layout & Display Configuration')
                                    ->description('Customize tampilan halaman Layanan')
                                    ->aside()
                                    ->schema([
                                        Grid::make(2)->schema([
                                            Select::make('layanan_grid_columns')
                                                ->label('Grid Columns untuk Service Cards')
                                                ->options([
                                                    3 => '3 Kolom',
                                                    4 => '4 Kolom (Default)',
                                                ])
                                                ->default(4)
                                                ->native(false)
                                                ->helperText('Sesuaikan jumlah service card per baris'),

                                            Select::make('layanan_card_style')
                                                ->label('Style Service Cards')
                                                ->options([
                                                    'standard' => 'Standard',
                                                    'compact' => 'Compact',
                                                    'large' => 'Large',
                                                ])
                                                ->default('standard')
                                                ->native(false),
                                        ]),

                                        Grid::make(3)->schema([
                                            Toggle::make('layanan_show_benefits')
                                                ->label('Tampilkan "Mengapa Memilih Kami?"')
                                                ->default(true)
                                                ->inline(),

                                            Toggle::make('layanan_show_warranty')
                                                ->label('Tampilkan Section Garansi')
                                                ->default(true)
                                                ->inline(),
                                        ]),
                                    ]),
                            ]),

                        // ============================
                        // TAB 12: KATALOG PAGE CONFIG
                        // ============================
                        Tab::make('Katalog Page')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                Section::make('Hero Section')
                                    ->description('Bagian pembuka halaman Katalog')
                                    ->aside()
                                    ->schema([
                                        TextInput::make('katalog_hero_title')
                                            ->label('Judul Hero')
                                            ->placeholder('Wrap Catalog')
                                            ->maxLength(255),
                                        Textarea::make('katalog_hero_desc')
                                            ->label('Deskripsi Hero')
                                            ->placeholder('Choose your perfect wrapping finish...')
                                            ->rows(3),
                                    ]),

                                Section::make('Content Text')
                                    ->description('Teks introductory dan empty state')
                                    ->aside()
                                    ->collapsible()
                                    ->schema([
                                        Textarea::make('katalog_intro_text')
                                            ->label('Intro/Deskripsi Katalog')
                                            ->placeholder('Deskripsi tentang pilihan-pilihan yang tersedia...')
                                            ->rows(3),
                                        TextInput::make('katalog_empty_state_title')
                                            ->label('Empty State Title')
                                            ->placeholder('Tidak Ada Katalog'),
                                        Textarea::make('katalog_empty_state_desc')
                                            ->label('Empty State Description')
                                            ->rows(2),
                                    ]),

                                Section::make('Feature Highlights (4 Cards)')
                                    ->description('Keunggulan/feature yang ditampilkan di halaman katalog')
                                    ->aside()
                                    ->collapsible()
                                    ->schema([
                                        Grid::make(2)->schema([
                                            TextInput::make('katalog_feature_1_title')
                                                ->label('Feature 1 Title')
                                                ->placeholder('Premium Films'),
                                            Textarea::make('katalog_feature_1_desc')
                                                ->label('Feature 1 Description')
                                                ->rows(2),

                                            TextInput::make('katalog_feature_2_title')
                                                ->label('Feature 2 Title')
                                                ->placeholder('Expert Installers'),
                                            Textarea::make('katalog_feature_2_desc')
                                                ->label('Feature 2 Description')
                                                ->rows(2),

                                            TextInput::make('katalog_feature_3_title')
                                                ->label('Feature 3 Title')
                                                ->placeholder('Lifetime Warranty'),
                                            Textarea::make('katalog_feature_3_desc')
                                                ->label('Feature 3 Description')
                                                ->rows(2),

                                            TextInput::make('katalog_feature_4_title')
                                                ->label('Feature 4 Title')
                                                ->placeholder('Professional Quality'),
                                            Textarea::make('katalog_feature_4_desc')
                                                ->label('Feature 4 Description')
                                                ->rows(2),
                                        ]),
                                    ]),
                            ]),

                        // ============================
                        // TAB 13: GALERI PAGE CONFIG
                        // ============================
                        Tab::make('Galeri Page')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                Section::make('Hero Section')
                                    ->description('Bagian pembuka halaman Galeri')
                                    ->aside()
                                    ->schema([
                                        TextInput::make('galeri_hero_title')
                                            ->label('Judul Hero')
                                            ->placeholder('Precision Mastery Gallery')
                                            ->maxLength(255),
                                        Textarea::make('galeri_hero_desc')
                                            ->label('Deskripsi Hero')
                                            ->placeholder('Lihat hasil karya premium kami...')
                                            ->rows(3),
                                    ]),

                                Section::make('Content Text')
                                    ->description('Teks introductory dan empty state')
                                    ->aside()
                                    ->collapsible()
                                    ->schema([
                                        Textarea::make('galeri_intro_text')
                                            ->label('Intro/Deskripsi Galeri')
                                            ->placeholder('Deskripsi tentang portfolio kami...')
                                            ->rows(3),
                                        TextInput::make('galeri_empty_state_title')
                                            ->label('Empty State Title')
                                            ->placeholder('Tidak Ada Galeri'),
                                        Textarea::make('galeri_empty_state_desc')
                                            ->label('Empty State Description')
                                            ->rows(2),
                                    ]),
                            ]),

                        // ============================
                        // TAB 14: PESANAN & KERANJANG CONFIG
                        // ============================
                        Tab::make('Pesanan & Keranjang')
                            ->icon('heroicon-o-shopping-cart')
                            ->schema([
                                Section::make('Pesanan Page')
                                    ->description('Konfigurasi halaman daftar pesanan')
                                    ->aside()
                                    ->schema([
                                        TextInput::make('pesanan_page_title_all')
                                            ->label('Pesanan Page Title')
                                            ->placeholder('Pesanan Anda')
                                            ->maxLength(255),
                                        Textarea::make('pesanan_page_desc_all')
                                            ->label('Pesanan Page Description')
                                            ->placeholder('Lihat daftar semua pesanan Anda...')
                                            ->rows(2),
                                        Grid::make(3)->schema([
                                            TextInput::make('pesanan_filter_semua_label')
                                                ->label('Filter: Semua Label')
                                                ->placeholder('Semua')
                                                ->maxLength(100),
                                            TextInput::make('pesanan_filter_berjalan_label')
                                                ->label('Filter: Berjalan Label')
                                                ->placeholder('Berjalan')
                                                ->maxLength(100),
                                            TextInput::make('pesanan_filter_selesai_label')
                                                ->label('Filter: Selesai Label')
                                                ->placeholder('Selesai')
                                                ->maxLength(100),
                                        ]),
                                        TextInput::make('pesanan_empty_state_title')
                                            ->label('Empty State Title')
                                            ->placeholder('Tidak Ada Pesanan'),
                                        Textarea::make('pesanan_empty_state_desc')
                                            ->label('Empty State Description')
                                            ->rows(2),
                                        TextInput::make('pesanan_new_order_button_label')
                                            ->label('New Order Button Label')
                                            ->placeholder('Mulai Pesanan Baru'),
                                    ]),

                                Section::make('Keranjang Page')
                                    ->description('Konfigurasi halaman keranjang belanja')
                                    ->aside()
                                    ->collapsible()
                                    ->schema([
                                        TextInput::make('keranjang_hero_text')
                                            ->label('Hero Text')
                                            ->placeholder('YOUR SELECTION'),
                                        TextInput::make('keranjang_title')
                                            ->label('Page Title')
                                            ->placeholder('Keranjang Belanja'),
                                        Textarea::make('keranjang_subtitle')
                                            ->label('Page Subtitle')
                                            ->placeholder('Tinjau pilihan layanan Anda...')
                                            ->rows(2),
                                        TextInput::make('keranjang_empty_title')
                                            ->label('Empty State Title')
                                            ->placeholder('Keranjang Kosong'),
                                        Textarea::make('keranjang_empty_desc')
                                            ->label('Empty State Description')
                                            ->rows(2),
                                        TextInput::make('keranjang_warranty_title')
                                            ->label('Warranty Section Title')
                                            ->placeholder('Garansi & Proteksi'),
                                        Textarea::make('keranjang_warranty_desc')
                                            ->label('Warranty Section Description')
                                            ->rows(2),
                                        Grid::make(2)->schema([
                                            TextInput::make('keranjang_service_charge_label')
                                                ->label('Service Charge Label')
                                                ->placeholder('Biaya Layanan'),
                                            TextInput::make('keranjang_service_charge_amount')
                                                ->label('Service Charge Amount')
                                                ->placeholder('10%'),
                                        ]),
                                        TextInput::make('keranjang_checkout_button_label')
                                            ->label('Checkout Button Label')
                                            ->placeholder('Lanjut ke Pembayaran'),
                                    ]),
                            ]),

                        // ============================
                        // TAB 15: CHECKOUT PAGE CONFIG
                        // ============================
                        Tab::make('Checkout Page')
                            ->icon('heroicon-o-credit-card')
                            ->schema([
                                Section::make('Step Labels')
                                    ->description('Label untuk setiap step checkout')
                                    ->aside()
                                    ->schema([
                                        Grid::make(2)->schema([
                                            TextInput::make('checkout_step_1_label')
                                                ->label('Step 1 Label')
                                                ->placeholder('Pilih Layanan'),
                                            TextInput::make('checkout_step_2_label')
                                                ->label('Step 2 Label')
                                                ->placeholder('Data Kendaraan'),
                                            TextInput::make('checkout_step_3_label')
                                                ->label('Step 3 Label')
                                                ->placeholder('Review'),
                                            TextInput::make('checkout_step_4_label')
                                                ->label('Step 4 Label')
                                                ->placeholder('Pembayaran'),
                                        ]),
                                    ]),

                                Section::make('Step 2 Content')
                                    ->description('Judul dan subtitle step data kendaraan')
                                    ->aside()
                                    ->collapsible()
                                    ->schema([
                                        TextInput::make('checkout_step2_title')
                                            ->label('Step 2 Title')
                                            ->placeholder('Data Kendaraan & Jadwal'),
                                        Textarea::make('checkout_step2_subtitle')
                                            ->label('Step 2 Subtitle')
                                            ->rows(2),
                                    ]),

                                Section::make('Step 3 Content')
                                    ->description('Judul dan subtitle step review')
                                    ->aside()
                                    ->collapsible()
                                    ->schema([
                                        TextInput::make('checkout_step3_title')
                                            ->label('Step 3 Title')
                                            ->placeholder('Konfirmasi Pemesanan'),
                                        Textarea::make('checkout_step3_subtitle')
                                            ->label('Step 3 Subtitle')
                                            ->rows(2),
                                    ]),

                                Section::make('Warranty Badges & Buttons')
                                    ->description('Badge text dan button labels')
                                    ->aside()
                                    ->collapsible()
                                    ->schema([
                                        Grid::make(2)->schema([
                                            TextInput::make('checkout_warranty_badge_1')
                                                ->label('Warranty Badge 1')
                                                ->placeholder('Garansi 2 Tahun'),
                                            TextInput::make('checkout_warranty_badge_2')
                                                ->label('Warranty Badge 2')
                                                ->placeholder('UV Protection'),
                                        ]),
                                        TextInput::make('checkout_confirm_button_label')
                                            ->label('Confirm Button Label')
                                            ->placeholder('Konfirmasi Pemesanan'),
                                    ]),
                            ]),

                        // ============================
                        // TAB 16: PROFIL & THREE PILLARS CONFIG
                        // ============================
                        Tab::make('Profil - Three Pillars')
                            ->icon('heroicon-o-sparkles')
                            ->schema([
                                Section::make('Three Pillars Section')
                                    ->description('Konfigurasi 3 pilar utama profil perusahaan')
                                    ->aside()
                                    ->schema([
                                        Grid::make(1)->schema([
                                            // Pillar 1
                                            TextInput::make('profil_pillar_1_title')
                                                ->label('Pillar 1 Title')
                                                ->placeholder('Precision Engineering')
                                                ->maxLength(255),
                                            Textarea::make('profil_pillar_1_desc')
                                                ->label('Pillar 1 Description')
                                                ->rows(3),

                                            // Pillar 2
                                            TextInput::make('profil_pillar_2_title')
                                                ->label('Pillar 2 Title')
                                                ->placeholder('Bespoke Materials')
                                                ->maxLength(255),
                                            Textarea::make('profil_pillar_2_desc')
                                                ->label('Pillar 2 Description')
                                                ->rows(3),

                                            // Pillar 3
                                            TextInput::make('profil_pillar_3_title')
                                                ->label('Pillar 3 Title')
                                                ->placeholder('White-Glove Service')
                                                ->maxLength(255),
                                            Textarea::make('profil_pillar_3_desc')
                                                ->label('Pillar 3 Description')
                                                ->rows(3),
                                        ]),
                                    ]),
                            ]),

                        // ============================
                        // TAB 17: DASHBOARD HEADERS CONFIG
                        // ============================
                        Tab::make('Dashboard Headers')
                            ->icon('heroicon-o-user')
                            ->schema([
                                Section::make('Section Titles')
                                    ->description('Judul untuk section di dashboard customer')
                                    ->aside()
                                    ->schema([
                                        TextInput::make('dashboard_quick_services_title')
                                            ->label('Quick Services Section Title')
                                            ->placeholder('Layanan Cepat')
                                            ->maxLength(255),
                                        TextInput::make('dashboard_activity_title')
                                            ->label('Activity Section Title')
                                            ->placeholder('Aktivitas Terakhir')
                                            ->maxLength(255),
                                    ]),
                            ]),
                    ])
                    ->persistTabInQueryString()
                    ->columnSpanFull(),
            ]);
    }
}
