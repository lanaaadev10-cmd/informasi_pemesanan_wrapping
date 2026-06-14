<?php

namespace App\Filament\Resources\TentangKamis\Pages;

use App\Filament\Resources\TentangKamiResource;
use App\Settings\TentangKamiSettings;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Illuminate\Database\Eloquent\Model;

class EditTentangKami extends EditRecord
{
    protected static string $resource = TentangKamiResource::class;

    protected static ?string $title = 'Edit Halaman Tentang Kami';

    public function mount($record = null): void
    {
        $this->record = $this->getRecord();

        $this->authorizeAccess();

        $settings = app(TentangKamiSettings::class);
        $data = $settings->toArray();
        $defaults = (new \ReflectionClass($settings))->getDefaultProperties();
        $this->form->fill(array_merge($defaults, $data));
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Hero')
                    ->description('Atur bagian hero di halaman Tentang Kami.')
                    ->aside()
                    ->icon('heroicon-o-photo')
                    ->schema([
                        TextInput::make('tentang_kami_hero_badge')
                            ->label('Badge Hero')
                            ->placeholder('Contoh: Tentang Kami')
                            ->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-'))
                            ->columnSpanFull(),
                        TextInput::make('tentang_kami_hero_title')
                            ->label('Judul Hero *')
                            ->placeholder('Contoh: Precision in Every Layer')
                            ->required()
                            ->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-'))
                            ->columnSpanFull(),
                        Textarea::make('tentang_kami_hero_desc')
                            ->label('Deskripsi Hero')
                            ->placeholder('Tuliskan pengenalan singkat perusahaan...')
                            ->rows(3)
                            ->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-'))
                            ->columnSpanFull(),
                        FileUpload::make('tentang_kami_hero_image')
                            ->label('Foto Background Hero')
                            ->image()
                            ->disk('public')
                            ->directory('tentang-kami')
                            ->maxSize(10240)
                            ->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-'))
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
                                ->placeholder('Contoh: Visi Kami')
                                ->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            TextInput::make('tentang_kami_misi_title')
                                ->label('Judul Misi')
                                ->placeholder('Contoh: Misi Kami')
                                ->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                        ]),
                        Grid::make(2)->schema([
                            Textarea::make('visi')
                                ->label('Visi Perusahaan')
                                ->placeholder('Tuliskan visi atau tujuan jangka panjang perusahaan...')
                                ->rows(4)
                                ->helperText(fn ($state) => 'Saat ini: ' . (strlen($state ?? '') > 50 ? substr($state, 0, 50) . '...' : ($state ?: '-'))),
                            Textarea::make('misi')
                                ->label('Misi Perusahaan')
                                ->placeholder('Tuliskan misi atau tujuan utama perusahaan...')
                                ->rows(4)
                                ->helperText(fn ($state) => 'Saat ini: ' . (strlen($state ?? '') > 50 ? substr($state, 0, 50) . '...' : ($state ?: '-'))),
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
                            ->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-'))
                            ->columnSpanFull(),
                        TextInput::make('tentang_kami_values_columns')
                            ->label('Jumlah Kolom Nilai')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(4)
                            ->default(3),
                        Grid::make(3)->schema([
                            Grid::make(1)->schema([
                                TextInput::make('tentang_kami_values_1_title')
                                    ->label('Nilai 1 — Judul')
                                    ->placeholder('Contoh: Presisi')
                                    ->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                                Textarea::make('tentang_kami_values_1_desc')
                                    ->label('Nilai 1 — Deskripsi')
                                    ->rows(3)
                                    ->helperText(fn ($state) => 'Saat ini: ' . (strlen($state ?? '') > 50 ? substr($state, 0, 50) . '...' : ($state ?: '-'))),
                            ]),
                            Grid::make(1)->schema([
                                TextInput::make('tentang_kami_values_2_title')
                                    ->label('Nilai 2 — Judul')
                                    ->placeholder('Contoh: Integritas')
                                    ->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                                Textarea::make('tentang_kami_values_2_desc')
                                    ->label('Nilai 2 — Deskripsi')
                                    ->rows(3)
                                    ->helperText(fn ($state) => 'Saat ini: ' . (strlen($state ?? '') > 50 ? substr($state, 0, 50) . '...' : ($state ?: '-'))),
                            ]),
                            Grid::make(1)->schema([
                                TextInput::make('tentang_kami_values_3_title')
                                    ->label('Nilai 3 — Judul')
                                    ->placeholder('Contoh: Eksklusivitas')
                                    ->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                                Textarea::make('tentang_kami_values_3_desc')
                                    ->label('Nilai 3 — Deskripsi')
                                    ->rows(3)
                                    ->helperText(fn ($state) => 'Saat ini: ' . (strlen($state ?? '') > 50 ? substr($state, 0, 50) . '...' : ($state ?: '-'))),
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
                            ->placeholder('Contoh: Tim Kami')
                            ->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                        TextInput::make('tentang_kami_team_title')
                            ->label('Judul Bagian Tim')
                            ->placeholder('Contoh: Dibalik Setiap Detail Sempurna.')
                            ->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-'))
                            ->columnSpanFull(),
                        Textarea::make('tentang_kami_team_desc')
                            ->label('Deskripsi Bagian Tim')
                            ->placeholder('Tuliskan deskripsi singkat tentang tim perusahaan...')
                            ->rows(3)
                            ->helperText(fn ($state) => 'Saat ini: ' . (strlen($state ?? '') > 50 ? substr($state, 0, 50) . '...' : ($state ?: '-')))
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
                            ->placeholder('Contoh: Sejarah Kami')
                            ->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                        TextInput::make('tentang_kami_sejarah_title')
                            ->label('Judul Sejarah')
                            ->placeholder('Contoh: Satu Dekade Dedikasi pada Perfeksi.')
                            ->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-'))
                            ->columnSpanFull(),
                        Textarea::make('sejarah')
                            ->label('Sejarah Perusahaan')
                            ->placeholder('Tuliskan latar belakang dan sejarah perkembangan perusahaan...')
                            ->rows(5)
                            ->helperText(fn ($state) => 'Saat ini: ' . (strlen($state ?? '') > 50 ? substr($state, 0, 50) . '...' : ($state ?: '-')))
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
                            ->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-'))
                            ->columnSpanFull(),
                        Textarea::make('tentang_kami_cta_desc')
                            ->label('Deskripsi CTA')
                            ->placeholder('Tuliskan ajakan untuk menghubungi...')
                            ->rows(2)
                            ->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-'))
                            ->columnSpanFull(),
                        Grid::make(2)->schema([
                            TextInput::make('tentang_kami_cta_primary_button')
                                ->label('Tombol Utama CTA')
                                ->placeholder('Contoh: Hubungi Kami Sekarang')
                                ->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            TextInput::make('tentang_kami_cta_secondary_button')
                                ->label('Tombol Sekunder CTA')
                                ->placeholder('Contoh: Lihat Portofolio')
                                ->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                        ]),
                    ]),
            ]);
    }

    public function save(bool $shouldRedirect = true, bool $shouldSendSavedNotification = true): void
    {
        $this->callHook('beforeValidate');

        $data = $this->form->getState();

        $this->callHook('beforeSave');

        $settings = app(TentangKamiSettings::class);
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
            ->title('Konten halaman Tentang Kami berhasil diperbarui!')
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
                ->url(url('/tentang-kami'))
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
