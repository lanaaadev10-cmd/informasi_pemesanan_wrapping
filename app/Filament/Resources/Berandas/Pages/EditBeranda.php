<?php

namespace App\Filament\Resources\Berandas\Pages;

use App\Filament\Resources\BerandaResource;
use App\Settings\HomepageSettings;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Model;

class EditBeranda extends EditRecord
{
    protected static string $resource = BerandaResource::class;

    protected static ?string $title = 'Edit Halaman Beranda';

    public function mount($record = null): void
    {
        $this->record = $this->getRecord();

        $this->authorizeAccess();

        $settings = app(HomepageSettings::class);
        $data = $settings->toArray();
        $defaults = (new \ReflectionClass($settings))->getDefaultProperties();
        $this->form->fill(array_merge($defaults, $data));
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Hero & Statistik')
                    ->description('Atur teks badge, judul hero, sub-deskripsi, dan data statistik di halaman beranda.')
                    ->aside()
                    ->icon('heroicon-o-photo')
                    ->schema([
                        TextInput::make('home_badge_text')
                            ->label('Badge Text (Atas) *')
                            ->placeholder('Contoh: Professional Car Wrapping Indonesia')
                            ->required()
                            ->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                        Grid::make(2)
                            ->schema([
                                TextInput::make('home_hero_title_line1')
                                    ->label('Judul Hero Baris 1 *')
                                    ->placeholder('Contoh: Elevasi Estetika')
                                    ->required()
                                    ->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                                TextInput::make('home_hero_title_line2')
                                    ->label('Judul Hero Baris 2 *')
                                    ->placeholder('Contoh: Aset Mewah Anda.')
                                    ->required()
                                    ->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            ]),
                        Textarea::make('home_subtitle')
                            ->label('Sub-deskripsi Hero *')
                            ->placeholder('Tuliskan deskripsi singkat penawaran...')
                            ->required()
                            ->rows(3)
                            ->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                        Grid::make(4)
                            ->schema([
                                TextInput::make('home_stat1_value')
                                    ->label('Statistik 1 — Angka')
                                    ->placeholder('Contoh: 500+')
                                    ->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                                TextInput::make('home_stat1_label')
                                    ->label('Statistik 1 — Label')
                                    ->placeholder('Contoh: Supercars Wrapped')
                                    ->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                                TextInput::make('home_stat2_value')
                                    ->label('Statistik 2 — Angka')
                                    ->placeholder('Contoh: 5 Tahun')
                                    ->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                                TextInput::make('home_stat2_label')
                                    ->label('Statistik 2 — Label')
                                    ->placeholder('Contoh: Garansi Material')
                                    ->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            ]),
                    ]),

                Section::make('Keunggulan Layanan')
                    ->description('Atur 4 kartu keunggulan yang ditampilkan di halaman beranda.')
                    ->aside()
                    ->icon('heroicon-o-star')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Grid::make(1)
                                    ->schema([
                                        TextInput::make('home_keunggulan_card1_title')
                                            ->label('Keunggulan 1 — Judul *')
                                            ->required()
                                            ->helperText(fn ($state) => 'Judul keunggulan pertama, contoh: Kualitas Premium, Garansi Seumur Hidup. Saat ini: ' . ($state ?: '-')),
                                        Textarea::make('home_keunggulan_card1_desc')
                                            ->label('Keunggulan 1 — Deskripsi *')
                                            ->rows(3)
                                            ->required()
                                            ->helperText(fn ($state) => 'Penjelasan detail tentang keunggulan pertama (1-2 kalimat). Saat ini: ' . ($state ?: '-')),
                                    ]),
                                Grid::make(1)
                                    ->schema([
                                        TextInput::make('home_keunggulan_card2_title')
                                            ->label('Keunggulan 2 — Judul *')
                                            ->required()
                                            ->helperText(fn ($state) => 'Judul keunggulan kedua, contoh: Pengerjaan Cepat, Harga Terjangkau. Saat ini: ' . ($state ?: '-')),
                                        Textarea::make('home_keunggulan_card2_desc')
                                            ->label('Keunggulan 2 — Deskripsi *')
                                            ->rows(3)
                                            ->required()
                                            ->helperText(fn ($state) => 'Penjelasan detail tentang keunggulan kedua (1-2 kalimat). Saat ini: ' . ($state ?: '-')),
                                    ]),
                                Grid::make(1)
                                    ->schema([
                                        TextInput::make('home_keunggulan_card3_title')
                                            ->label('Keunggulan 3 — Judul *')
                                            ->required()
                                            ->helperText(fn ($state) => 'Judul keunggulan ketiga, contoh: Tim Profesional, Bahan Berkualitas. Saat ini: ' . ($state ?: '-')),
                                        Textarea::make('home_keunggulan_card3_desc')
                                            ->label('Keunggulan 3 — Deskripsi *')
                                            ->rows(3)
                                            ->required()
                                            ->helperText(fn ($state) => 'Penjelasan detail tentang keunggulan ketiga (1-2 kalimat). Saat ini: ' . ($state ?: '-')),
                                    ]),
                                Grid::make(1)
                                    ->schema([
                                        TextInput::make('home_keunggulan_card4_title')
                                            ->label('Keunggulan 4 — Judul *')
                                            ->required()
                                            ->helperText(fn ($state) => 'Judul keunggulan keempat, contoh: Desain Custom, Konsultasi Gratis. Saat ini: ' . ($state ?: '-')),
                                        Textarea::make('home_keunggulan_card4_desc')
                                            ->label('Keunggulan 4 — Deskripsi *')
                                            ->rows(3)
                                            ->required()
                                            ->helperText(fn ($state) => 'Penjelasan detail tentang keunggulan keempat (1-2 kalimat). Saat ini: ' . ($state ?: '-')),
                                    ]),
                            ]),
                    ]),

                Section::make('Langkah Mudah (Fast Process)')
                    ->description('Atur 3 langkah mudah yang menjelaskan alur pemesanan.')
                    ->aside()
                    ->icon('heroicon-o-queue-list')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Grid::make(1)
                                    ->schema([
                                        TextInput::make('home_step1_title')
                                            ->label('Langkah 1 — Judul *')
                                            ->required()
                                            ->helperText(fn ($state) => 'Nama langkah pertama, contoh: Konsultasi, Pilih Warna. Saat ini: ' . ($state ?: '-')),
                                        Textarea::make('home_step1_desc')
                                            ->label('Langkah 1 — Deskripsi *')
                                            ->rows(3)
                                            ->required()
                                            ->helperText(fn ($state) => 'Penjelasan tentang apa yang dilakukan di langkah pertama (1-2 kalimat). Saat ini: ' . ($state ?: '-')),
                                    ]),
                                Grid::make(1)
                                    ->schema([
                                        TextInput::make('home_step2_title')
                                            ->label('Langkah 2 — Judul *')
                                            ->required()
                                            ->helperText(fn ($state) => 'Nama langkah kedua, contoh: Proses Pengerjaan, Pembayaran. Saat ini: ' . ($state ?: '-')),
                                        Textarea::make('home_step2_desc')
                                            ->label('Langkah 2 — Deskripsi *')
                                            ->rows(3)
                                            ->required()
                                            ->helperText(fn ($state) => 'Penjelasan tentang apa yang dilakukan di langkah kedua (1-2 kalimat). Saat ini: ' . ($state ?: '-')),
                                    ]),
                                Grid::make(1)
                                    ->schema([
                                        TextInput::make('home_step3_title')
                                            ->label('Langkah 3 — Judul *')
                                            ->required()
                                            ->helperText(fn ($state) => 'Nama langkah ketiga, contoh: Hasil Jadi, Quality Control. Saat ini: ' . ($state ?: '-')),
                                        Textarea::make('home_step3_desc')
                                            ->label('Langkah 3 — Deskripsi *')
                                            ->rows(3)
                                            ->required()
                                            ->helperText(fn ($state) => 'Penjelasan tentang apa yang dilakukan di langkah ketiga (1-2 kalimat). Saat ini: ' . ($state ?: '-')),
                                    ]),
                            ]),
                    ]),

                Section::make('Ajakan Bertindak (CTA Banner)')
                    ->description('Atur banner ajakan bertindak di bagian bawah halaman beranda.')
                    ->aside()
                    ->icon('heroicon-o-megaphone')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        TextInput::make('home_cta_title')
                            ->label('Judul CTA *')
                            ->placeholder('Contoh: Siap Mengubah Tampilan Kendaraan?')
                            ->required()
                            ->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                        Textarea::make('home_cta_subtitle')
                            ->label('Sub-deskripsi CTA *')
                            ->placeholder('Tuliskan deskripsi penawaran untuk memikat pelanggan...')
                            ->required()
                            ->rows(3)
                            ->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                    ]),

                Section::make('Tombol Hero')
                    ->description('Atur teks pada tombol di bagian hero.')
                    ->aside()
                    ->icon('heroicon-o-hand-raised')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('home_hero_cta_primary')
                                    ->label('Tombol Utama (Primary)')
                                    ->placeholder('Contoh: Pesan Sekarang')
                                    ->helperText(fn ($state) => 'Teks tombol utama di hero. Saat ini: ' . ($state ?: '-')),
                                TextInput::make('home_hero_cta_secondary')
                                    ->label('Tombol Kedua (Secondary)')
                                    ->placeholder('Contoh: Lihat Portofolio')
                                    ->helperText(fn ($state) => 'Teks tombol kedua di hero. Saat ini: ' . ($state ?: '-')),
                            ]),
                    ]),

                Section::make('Header Keunggulan')
                    ->description('Atur badge, judul, dan teks selengkapnya di bagian keunggulan.')
                    ->aside()
                    ->icon('heroicon-o-star')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('home_section_keunggulan_badge')
                                    ->label('Badge Keunggulan')
                                    ->placeholder('Contoh: Keunggulan Layanan')
                                    ->helperText(fn ($state) => 'Label kecil di atas judul section keunggulan. Saat ini: ' . ($state ?: '-'))
                                    ->columnSpanFull(),
                                TextInput::make('home_section_keunggulan_title')
                                    ->label('Judul Section Keunggulan')
                                    ->placeholder('Contoh: Mengapa Memilih Wapping?')
                                    ->helperText(fn ($state) => 'Judul utama bagian keunggulan. Saat ini: ' . ($state ?: '-'))
                                    ->columnSpanFull(),
                                TextInput::make('home_card_selengkapnya')
                                    ->label('Teks "Selengkapnya"')
                                    ->placeholder('Contoh: Selengkapnya')
                                    ->helperText(fn ($state) => 'Teks link pada card keunggulan. Saat ini: ' . ($state ?: '-')),
                            ]),
                    ]),

                Section::make('Header Portofolio')
                    ->description('Atur badge, judul, deskripsi, dan teks lihat semua di bagian portofolio.')
                    ->aside()
                    ->icon('heroicon-o-photo')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('home_section_portofolio_badge')
                                    ->label('Badge Portofolio')
                                    ->placeholder('Contoh: Showcase Portofolio')
                                    ->helperText(fn ($state) => 'Label kecil di atas judul section portofolio. Saat ini: ' . ($state ?: '-'))
                                    ->columnSpanFull(),
                                TextInput::make('home_section_portofolio_title')
                                    ->label('Judul Section Portofolio')
                                    ->placeholder('Contoh: Mahakarya Kami')
                                    ->helperText(fn ($state) => 'Judul utama bagian portofolio. Saat ini: ' . ($state ?: '-'))
                                    ->columnSpanFull(),
                                Textarea::make('home_section_portofolio_desc')
                                    ->label('Deskripsi Section Portofolio')
                                    ->placeholder('Contoh: Berikut adalah hasil pengerjaan car wrapping premium...')
                                    ->helperText(fn ($state) => 'Deskripsi singkat di bawah judul portofolio. Saat ini: ' . ($state ?: '-'))
                                    ->rows(2)
                                    ->columnSpanFull(),
                                TextInput::make('home_portofolio_lihat_semua')
                                    ->label('Teks "Lihat Semua"')
                                    ->placeholder('Contoh: Lihat Semua')
                                    ->helperText(fn ($state) => 'Teks link untuk melihat semua portofolio. Saat ini: ' . ($state ?: '-')),
                            ]),
                    ]),

                Section::make('Header CTA Langkah')
                    ->description('Atur badge, tagline, dan teks tombol di bagian CTA langkah mudah.')
                    ->aside()
                    ->icon('heroicon-o-queue-list')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('home_cta_langkah_badge')
                                    ->label('Badge Langkah')
                                    ->placeholder('Contoh: Langkah Mudah')
                                    ->helperText(fn ($state) => 'Label kecil di dalam kotak langkah. Saat ini: ' . ($state ?: '-'))
                                    ->columnSpanFull(),
                                TextInput::make('home_cta_langkah_tagline')
                                    ->label('Tagline Langkah')
                                    ->placeholder('Contoh: Fast Process')
                                    ->helperText(fn ($state) => 'Tagline pendamping badge langkah. Saat ini: ' . ($state ?: '-')),
                                TextInput::make('home_cta_wa_button')
                                    ->label('Teks Tombol WhatsApp')
                                    ->placeholder('Contoh: Hubungi WhatsApp')
                                    ->helperText(fn ($state) => 'Teks tombol CTA WhatsApp. Saat ini: ' . ($state ?: '-')),
                                TextInput::make('home_cta_pelajari_button')
                                    ->label('Teks Tombol Pelajari')
                                    ->placeholder('Contoh: Pelajari Prosedur')
                                    ->helperText(fn ($state) => 'Teks tombol kedua CTA. Saat ini: ' . ($state ?: '-')),
                            ]),
                    ]),
            ]);
    }

    public function save(bool $shouldRedirect = true, bool $shouldSendSavedNotification = true): void
    {
        $this->callHook('beforeValidate');

        $data = $this->form->getState();

        $this->callHook('beforeSave');

        $settings = app(HomepageSettings::class);
        $defaults = (new \ReflectionClass($settings))->getDefaultProperties();
        $data = array_merge($defaults, $settings->toArray(), $data);
        foreach ($data as $key => $value) {
            $settings->{$key} = $value;
        }

        $settings->settingsConfig()->resetDefaultValueLoadedProperties();
        $settings->save();

        $this->callHook('afterSave');

        Notification::make()
            ->success()
            ->title('Konten halaman beranda berhasil diperbarui!')
            ->send();
    }

    public function getRecord(): Model
    {
        return new \App\Models\DummyModel;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('view_website')
                ->label('Lihat Website')
                ->icon('heroicon-m-arrow-top-right-on-square')
                ->color('gray')
                ->url(url('/'))
                ->openUrlInNewTab(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function authorizeAccess(): void
    {
        abort_unless(static::getResource()::canViewAny(), 403);
    }
}
