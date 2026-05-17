<?php

namespace App\Filament\Resources\ProfilPerusahaans\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
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
                    ])
                    ->persistTabInQueryString()
                    ->columnSpanFull(),
            ]);
    }
}
