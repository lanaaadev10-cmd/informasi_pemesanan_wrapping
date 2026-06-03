<?php

namespace App\Filament\Resources\TentangKamiSetting\Pages;

use App\Filament\Resources\TentangKamiSettingResource;
use App\Settings\TentangKamiSettings;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Model;

class EditTentangKamiSetting extends EditRecord
{
    protected static string $resource = TentangKamiSettingResource::class;

    protected static ?string $title = 'Pengaturan Halaman Tentang Kami';

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
            ->columns(1)
            ->components([
                Section::make('Badge & Label')
                    ->icon('heroicon-o-tag')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('tentang_kami_hero_badge')->label('Badge Hero')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            TextInput::make('tentang_kami_visi_title')->label('Judul Visi')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            TextInput::make('tentang_kami_misi_title')->label('Judul Misi')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            TextInput::make('tentang_kami_values_title')->label('Judul Nilai-Nilai')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            TextInput::make('tentang_kami_sejarah_badge')->label('Badge Sejarah')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            TextInput::make('tentang_kami_sejarah_title')->label('Judul Sejarah')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            TextInput::make('tentang_kami_tim_badge')->label('Badge Tim')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                        ]),
                    ]),

                Section::make('Nilai-Nilai Perusahaan')
                    ->icon('heroicon-o-star')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('tentang_kami_values_1_title')->label('Nilai 1 - Judul')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            TextInput::make('tentang_kami_values_1_desc')->label('Nilai 1 - Deskripsi')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            TextInput::make('tentang_kami_values_2_title')->label('Nilai 2 - Judul')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            TextInput::make('tentang_kami_values_2_desc')->label('Nilai 2 - Deskripsi')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            TextInput::make('tentang_kami_values_3_title')->label('Nilai 3 - Judul')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            TextInput::make('tentang_kami_values_3_desc')->label('Nilai 3 - Deskripsi')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                        ]),
                    ]),

                Section::make('CTA Section')
                    ->icon('heroicon-o-cursor-arrow-rays')
                    ->schema([
                        TextInput::make('tentang_kami_cta_title')->label('Judul CTA')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                        Textarea::make('tentang_kami_cta_desc')->label('Deskripsi CTA')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-'))->rows(2),
                        TextInput::make('tentang_kami_cta_primary_button')->label('Tombol Utama CTA')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                        TextInput::make('tentang_kami_cta_secondary_button')->label('Tombol Sekunder CTA')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
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
        $settings->save();
        $this->callHook('afterSave');

        Notification::make()
            ->success()
            ->title('Pengaturan Halaman Tentang Kami berhasil diperbarui!')
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
