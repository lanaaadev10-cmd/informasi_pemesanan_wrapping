<?php

namespace App\Filament\Resources\HalamanGaleri\Pages;

use App\Filament\Resources\HalamanGaleriResource;
use App\Settings\GaleriSettings;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Section;
use Illuminate\Database\Eloquent\Model;

class EditHalamanGaleri extends EditRecord
{
    protected static string $resource = HalamanGaleriResource::class;

    protected static ?string $title = 'Edit Halaman Galeri';

    public function mount($record = null): void
    {
        $this->record = $this->getRecord();

        $this->authorizeAccess();

        $settings = app(GaleriSettings::class);
        $data = $settings->toArray();
        $defaults = (new \ReflectionClass($settings))->getDefaultProperties();
        $this->form->fill(array_merge($defaults, $data));
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Hero')
                ->description('Atur judul, deskripsi, dan gambar latar hero halaman galeri.')
                ->aside()
                ->icon('heroicon-o-photo')
                ->schema([
                    TextInput::make('galeri_hero_title')
                        ->label('Judul Hero *')
                        ->placeholder('Contoh: Precision Mastery Gallery')
                        ->required()
                        ->helperText('Judul utama halaman galeri. Ditampilkan besar di bagian atas halaman.'),
                    Textarea::make('galeri_hero_desc')
                        ->label('Deskripsi Hero')
                        ->placeholder('Tuliskan deskripsi galeri...')
                        ->rows(4)
                        ->helperText('Penjelasan singkat tentang galeri. Ceritakan jenis karya/portofolio yang ditampilkan (2-3 kalimat).'),
                ]),

            Section::make('Filter Kategori')
                ->description('Atur daftar kategori yang muncul sebagai tombol filter di halaman galeri. Kategori yang diisi di sini akan muncul sebagai pilihan dropdown saat menambah/edit galeri.')
                ->aside()
                ->icon('heroicon-o-funnel')
                ->schema([
                    TextInput::make('galeri_filter_all_label')
                        ->label('Label Tombol "Semua"')
                        ->placeholder('Contoh: All Works')
                        ->helperText('Teks tombol untuk menampilkan semua item galeri (tanpa filter kategori).'),
                    Repeater::make('galeri_filter_categories')
                        ->label('Daftar Kategori')
                        ->schema([
                            TextInput::make('slug')
                                ->label('Slug')
                                ->placeholder('Contoh: sports-cars')
                                ->helperText('Identifier unik (tanpa spasi, gunakan dash). Digunakan untuk filtering di URL.')
                                ->required()
                                ->distinct()
                                ->maxLength(255),
                            TextInput::make('label')
                                ->label('Label')
                                ->placeholder('Contoh: Sports Cars')
                                ->helperText('Nama kategori yang ditampilkan untuk pengguna.')
                                ->required()
                                ->maxLength(255),
                        ])
                        ->columns(2)
                        ->addActionLabel('Tambah Kategori')
                        ->reorderable()
                        ->itemLabel(fn (array $state): ?string => $state['label'] ?? null)
                        ->collapsible(),
                ]),
        ]);
    }

    public function save(bool $shouldRedirect = true, bool $shouldSendSavedNotification = true): void
    {
        $this->callHook('beforeValidate');

        $data = $this->form->getState();

        $this->callHook('beforeSave');

        $settings = app(GaleriSettings::class);
        $defaults = (new \ReflectionClass($settings))->getDefaultProperties();
        $data = array_merge($defaults, $settings->toArray(), $data);

        foreach ($data as $key => $value) {
            $settings->{$key} = $value;
        }

        $settings->save();

        $this->callHook('afterSave');

        Notification::make()
            ->success()
            ->title('Konten halaman galeri berhasil diperbarui!')
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
                ->label('Lihat Halaman Galeri')
                ->icon('heroicon-m-arrow-top-right-on-square')
                ->color('gray')
                ->url(url('/galeri'))
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
