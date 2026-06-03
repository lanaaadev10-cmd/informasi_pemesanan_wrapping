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
use Filament\Forms\Components\FileUpload;
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

        $this->form->fill($settings->toArray());
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
                        ->helperText('Judul utama di bagian hero halaman galeri.'),
                    Textarea::make('galeri_hero_desc')
                        ->label('Deskripsi Hero')
                        ->placeholder('Tuliskan deskripsi galeri...')
                        ->rows(4)
                        ->helperText('Deskripsi yang ditampilkan di bawah judul hero.'),
                    FileUpload::make('galeri_hero_image')
                        ->label('Foto Background Hero')
                        ->image()
                        ->disk('public')
                        ->directory('galeri')
                        ->maxSize(10240)
                        ->helperText('Ukuran rekomendasi: 1920x600px. Format: JPG, PNG. Maks: 10MB.'),
                ]),
        ]);
    }

    public function save(bool $shouldRedirect = true, bool $shouldSendSavedNotification = true): void
    {
        $this->callHook('beforeValidate');

        $data = $this->form->getState();

        $this->callHook('beforeSave');

        $settings = app(GaleriSettings::class);

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
