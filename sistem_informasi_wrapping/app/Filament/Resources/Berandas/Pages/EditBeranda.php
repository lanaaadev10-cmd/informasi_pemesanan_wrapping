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
use Filament\Forms\Components\FileUpload;
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
                                    ->placeholder('Kendaraan Anda')
                                    ->required(),
                                    
                                FileUpload::make('home_hero_image')
                                    ->label('Gambar Latar Belakang Hero')
                                    ->image()
                                    ->disk('public')
                                    ->directory('beranda')
                                    ->helperText('Upload gambar latar belakang hero (opsional). Ukuran rekomendasi: 1920x1080px.')
                                    ->nullable(),
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
                                            ->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                                        Textarea::make('home_keunggulan_card1_desc')
                                            ->label('Keunggulan 1 — Deskripsi *')
                                            ->rows(3)
                                            ->required()
                                            ->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                                    ]),
                                Grid::make(1)
                                    ->schema([
                                        TextInput::make('home_keunggulan_card2_title')
                                            ->label('Keunggulan 2 — Judul *')
                                            ->required()
                                            ->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                                        Textarea::make('home_keunggulan_card2_desc')
                                            ->label('Keunggulan 2 — Deskripsi *')
                                            ->rows(3)
                                            ->required()
                                            ->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                                    ]),
                                Grid::make(1)
                                    ->schema([
                                        TextInput::make('home_keunggulan_card3_title')
                                            ->label('Keunggulan 3 — Judul *')
                                            ->required()
                                            ->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                                        Textarea::make('home_keunggulan_card3_desc')
                                            ->label('Keunggulan 3 — Deskripsi *')
                                            ->rows(3)
                                            ->required()
                                            ->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                                    ]),
                                Grid::make(1)
                                    ->schema([
                                        TextInput::make('home_keunggulan_card4_title')
                                            ->label('Keunggulan 4 — Judul *')
                                            ->required()
                                            ->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                                        Textarea::make('home_keunggulan_card4_desc')
                                            ->label('Keunggulan 4 — Deskripsi *')
                                            ->rows(3)
                                            ->required()
                                            ->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
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
                                            ->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                                        Textarea::make('home_step1_desc')
                                            ->label('Langkah 1 — Deskripsi *')
                                            ->rows(3)
                                            ->required()
                                            ->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                                    ]),
                                Grid::make(1)
                                    ->schema([
                                        TextInput::make('home_step2_title')
                                            ->label('Langkah 2 — Judul *')
                                            ->required()
                                            ->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                                        Textarea::make('home_step2_desc')
                                            ->label('Langkah 2 — Deskripsi *')
                                            ->rows(3)
                                            ->required()
                                            ->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                                    ]),
                                Grid::make(1)
                                    ->schema([
                                        TextInput::make('home_step3_title')
                                            ->label('Langkah 3 — Judul *')
                                            ->required()
                                            ->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                                        Textarea::make('home_step3_desc')
                                            ->label('Langkah 3 — Deskripsi *')
                                            ->rows(3)
                                            ->required()
                                            ->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
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

                Section::make('Galeri & Portofolio')
                    ->description('Atur judul dan deskripsi bagian galeri portofolio di halaman beranda.')
                    ->aside()
                    ->icon('heroicon-o-photo')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        TextInput::make('home_section_portofolio_badge')
                            ->label('Badge Galeri')
                            ->placeholder('Contoh: Showcase Portofolio')
                            ->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                        TextInput::make('home_section_portofolio_title')
                            ->label('Judul Galeri')
                            ->placeholder('Contoh: Mahakarya Kami')
                            ->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                        Textarea::make('home_section_portofolio_desc')
                            ->label('Deskripsi Galeri')
                            ->placeholder('Tuliskan deskripsi singkat bagian galeri...')
                            ->rows(2)
                            ->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                        TextInput::make('home_portofolio_lihat_semua')
                            ->label('Label Tombol Lihat Semua')
                            ->placeholder('Contoh: Lihat Semua')
                            ->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                    ]),

                Section::make('Kontak, Lokasi & Maps')
                    ->description('Atur judul, label informasi, dan keterangan peta lokasi kantor.')
                    ->aside()
                    ->icon('heroicon-o-map')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        TextInput::make('home_section_kontak_badge')
                            ->label('Badge Kontak')
                            ->placeholder('Contoh: Kontak & Lokasi')
                            ->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                        TextInput::make('home_section_kontak_title')
                            ->label('Judul Kontak')
                            ->placeholder('Contoh: Temukan Kami')
                            ->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                        Textarea::make('home_section_kontak_desc')
                            ->label('Deskripsi Kontak')
                            ->placeholder('Tuliskan deskripsi singkat bagian kontak...')
                            ->rows(2)
                            ->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                        Grid::make(4)->schema([
                            TextInput::make('home_kontak_alamat_label')
                                ->label('Label Alamat')
                                ->placeholder('Contoh: Alamat')
                                ->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            TextInput::make('home_kontak_telepon_label')
                                ->label('Label Telepon')
                                ->placeholder('Contoh: Telepon')
                                ->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            TextInput::make('home_kontak_email_label')
                                ->label('Label Email')
                                ->placeholder('Contoh: Email')
                                ->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            TextInput::make('home_kontak_jam_label')
                                ->label('Label Jam')
                                ->placeholder('Contoh: Jam Operasional')
                                ->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                        ]),
                        TextInput::make('home_kontak_no_map')
                            ->label('Keterangan Peta Tidak Tersedia')
                            ->placeholder('Contoh: Peta belum tersedia')
                            ->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
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
