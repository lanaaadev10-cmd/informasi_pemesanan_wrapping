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
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
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

        $this->form->fill($settings->toArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Hero')
                    ->description('Atur judul, deskripsi, dan gambar latar hero halaman tentang kami.')
                    ->aside()
                    ->icon('heroicon-o-photo')
                    ->schema([
                        TextInput::make('tentang_kami_hero_title')
                            ->label('Judul Hero *')
                            ->placeholder('Contoh: Tentang Perusahaan Kami')
                            ->required()
                            ->helperText('Judul utama yang tampil di bagian hero.'),
                        Textarea::make('tentang_kami_hero_desc')
                            ->label('Deskripsi Hero')
                            ->placeholder('Tuliskan pengenalan singkat perusahaan...')
                            ->rows(4)
                            ->helperText('Deskripsi yang tampil di bawah judul hero.'),
                        FileUpload::make('tentang_kami_hero_image')
                            ->label('Foto Background Hero')
                            ->image()
                            ->disk('public')
                            ->directory('tentang-kami')
                            ->maxSize(10240)
                            ->helperText('Ukuran rekomendasi: 1920x600px. Format: JPG, PNG. Maks: 10MB.'),
                    ]),

                Section::make('Visi, Misi & Sejarah')
                    ->description('Atur visi, misi, dan sejarah perusahaan.')
                    ->aside()
                    ->icon('heroicon-o-document-text')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Textarea::make('visi')
                                    ->label('Visi Perusahaan')
                                    ->placeholder('Tuliskan visi atau tujuan jangka panjang perusahaan...')
                                    ->rows(4)
                                    ->helperText('Visi jangka panjang perusahaan.'),
                                Textarea::make('misi')
                                    ->label('Misi Perusahaan')
                                    ->placeholder('Tuliskan misi atau tujuan utama perusahaan...')
                                    ->rows(4)
                                    ->helperText('Misi utama yang mendukung visi perusahaan.'),
                            ]),
                        Textarea::make('sejarah')
                            ->label('Sejarah Perusahaan')
                            ->placeholder('Tuliskan latar belakang dan sejarah perkembangan perusahaan...')
                            ->rows(5)
                            ->helperText('Cerita awal berdiri hingga perkembangan perusahaan.'),
                    ]),

                Section::make('Tim')
                    ->description('Atur pengaturan bagian tim di halaman tentang kami.')
                    ->aside()
                    ->icon('heroicon-o-users')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Toggle::make('tentang_kami_show_team')
                                    ->label('Tampilkan Bagian Tim?')
                                    ->helperText('Aktifkan untuk menampilkan section tim di halaman tentang kami.'),
                                TextInput::make('tentang_kami_team_title')
                                    ->label('Judul Bagian Tim')
                                    ->placeholder('Contoh: Tim Profesional Kami')
                                    ->helperText('Judul yang muncul di atas daftar tim.'),
                            ]),
                        Textarea::make('tentang_kami_team_desc')
                            ->label('Deskripsi Bagian Tim')
                            ->placeholder('Tuliskan deskripsi singkat tentang tim perusahaan...')
                            ->rows(3)
                            ->helperText('Deskripsi yang muncul di atas daftar tim.'),
                    ]),

                Section::make('Pengaturan Tampilan')
                    ->description('Atur visibilitas bagian nilai-nilai dan sejarah.')
                    ->aside()
                    ->icon('heroicon-o-eye')
                    ->collapsible()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Toggle::make('tentang_kami_show_values')
                                    ->label('Tampilkan Bagian Nilai-Nilai?')
                                    ->helperText('Aktifkan untuk menampilkan nilai-nilai perusahaan.'),
                                TextInput::make('tentang_kami_values_columns')
                                    ->label('Jumlah Kolom Nilai')
                                    ->numeric()
                                    ->minValue(1)
                                    ->maxValue(4)
                                    ->helperText('Berapa kolom untuk menampilkan nilai-nilai (1-4).'),
                            ]),
                        Toggle::make('tentang_kami_show_history')
                            ->label('Tampilkan Bagian Sejarah?')
                            ->helperText('Aktifkan untuk menampilkan sejarah perusahaan.'),
                    ]),
            ]);
    }

    public function save(bool $shouldRedirect = true, bool $shouldSendSavedNotification = true): void
    {
        $this->callHook('beforeValidate');

        $data = $this->form->getState();

        $this->callHook('beforeSave');

        $settings = app(TentangKamiSettings::class);

        foreach ($data as $key => $value) {
            $settings->{$key} = $value;
        }

        $settings->save();

        $this->callHook('afterSave');

        Notification::make()
            ->success()
            ->title('Konten halaman tentang kami berhasil diperbarui!')
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
