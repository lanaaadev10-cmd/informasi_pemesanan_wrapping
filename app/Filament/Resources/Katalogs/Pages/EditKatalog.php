<?php

namespace App\Filament\Resources\Katalogs\Pages;

use App\Filament\Resources\KatalogResource;
use App\Settings\KatalogSettings;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Model;

class EditKatalog extends EditRecord
{
    protected static string $resource = KatalogResource::class;

    protected static ?string $title = 'Edit Halaman Katalog';

    public function mount($record = null): void
    {
        $this->record = $this->getRecord();
        $this->authorizeAccess();
        $settings = app(KatalogSettings::class);
        $data = $settings->toArray();
        $defaults = (new \ReflectionClass($settings))->getDefaultProperties();
        $this->form->fill(array_merge($defaults, $data));
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Hero')
                    ->icon('heroicon-o-photo')
                    ->schema([
                        TextInput::make('katalog_hero_title')->label('Judul Hero')->required()->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                        Textarea::make('katalog_hero_desc')->label('Deskripsi Hero')->rows(3)->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                        Textarea::make('katalog_intro_text')->label('Teks Intro')->rows(2)->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                    ]),

                Section::make('Filter & Tombol')
                    ->icon('heroicon-o-adjustments-horizontal')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('katalog_filter_all_label')->label('Label Filter: Semua')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            TagsInput::make('katalog_filter_categories')->label('Filter Kategori')->helperText(fn ($state) => 'Saat ini: ' . (is_array($state) ? implode(', ', $state) : '-')),
                            TextInput::make('katalog_card_book_button')->label('Tombol Pesan')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            TextInput::make('katalog_harga_custom_label')->label('Label Harga Custom')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                        ]),
                    ]),

                Section::make('Fitur Katalog')
                    ->icon('heroicon-o-star')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('katalog_feature_1_title')->label('Fitur 1: Judul')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            Textarea::make('katalog_feature_1_desc')->label('Fitur 1: Deskripsi')->rows(2)->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            TextInput::make('katalog_feature_2_title')->label('Fitur 2: Judul')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            Textarea::make('katalog_feature_2_desc')->label('Fitur 2: Deskripsi')->rows(2)->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            TextInput::make('katalog_feature_3_title')->label('Fitur 3: Judul')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            Textarea::make('katalog_feature_3_desc')->label('Fitur 3: Deskripsi')->rows(2)->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            TextInput::make('katalog_feature_4_title')->label('Fitur 4: Judul')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            Textarea::make('katalog_feature_4_desc')->label('Fitur 4: Deskripsi')->rows(2)->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                        ]),
                    ]),

                Section::make('Empty State')
                    ->icon('heroicon-o-inbox')
                    ->schema([
                        TextInput::make('katalog_empty_state_title')->label('Judul')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                        Textarea::make('katalog_empty_state_desc')->label('Deskripsi')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                    ]),
            ]);
    }

    public function save(bool $shouldRedirect = true, bool $shouldSendSavedNotification = true): void
    {
        $this->callHook('beforeValidate');
        $data = $this->form->getState();
        $this->callHook('beforeSave');

        $settings = app(KatalogSettings::class);
        $defaults = (new \ReflectionClass($settings))->getDefaultProperties();
        $data = array_merge($defaults, $settings->toArray(), $data);
        foreach ($data as $key => $value) {
            $settings->{$key} = $value;
        }
        $settings->save();
        $this->callHook('afterSave');

        Notification::make()
            ->success()
            ->title('Halaman Katalog berhasil diperbarui!')
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
                ->url(url('/katalog-layanan'))
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
