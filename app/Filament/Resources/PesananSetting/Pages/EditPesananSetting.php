<?php

namespace App\Filament\Resources\PesananSetting\Pages;

use App\Filament\Resources\PesananSettingResource;
use App\Settings\PesananSettings;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Model;

class EditPesananSetting extends EditRecord
{
    protected static string $resource = PesananSettingResource::class;

    protected static ?string $title = 'Edit Halaman Pesanan';

    public function mount($record = null): void
    {
        $this->record = $this->getRecord();
        $this->authorizeAccess();
        $settings = app(PesananSettings::class);
        $data = $settings->toArray();
        $defaults = (new \ReflectionClass($settings))->getDefaultProperties();
        $this->form->fill(array_merge($defaults, $data));
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Halaman Pesanan')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        TextInput::make('pesanan_page_title_all')->label('Judul Halaman')->required()->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                        Textarea::make('pesanan_page_desc_all')->label('Deskripsi Halaman')->rows(2)->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                    ]),

                Section::make('Filter')
                    ->icon('heroicon-o-funnel')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('pesanan_filter_semua_label')->label('Label: Semua')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            TextInput::make('pesanan_filter_berjalan_label')->label('Label: Berjalan')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            TextInput::make('pesanan_filter_selesai_label')->label('Label: Selesai')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                        ]),
                    ]),

                Section::make('Empty State')
                    ->icon('heroicon-o-inbox')
                    ->schema([
                        TextInput::make('pesanan_empty_state_title')->label('Judul')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                        Textarea::make('pesanan_empty_state_desc')->label('Deskripsi')->rows(2)->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                    ]),

                Section::make('Tombol')
                    ->icon('heroicon-o-cursor-arrow-rays')
                    ->schema([
                        TextInput::make('pesanan_new_order_button_label')->label('Tombol Pesan Baru')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                    ]),
            ]);
    }

    public function save(bool $shouldRedirect = true, bool $shouldSendSavedNotification = true): void
    {
        $this->callHook('beforeValidate');
        $data = $this->form->getState();
        $this->callHook('beforeSave');

        $settings = app(PesananSettings::class);
        $defaults = (new \ReflectionClass($settings))->getDefaultProperties();
        $data = array_merge($defaults, $settings->toArray(), $data);
        foreach ($data as $key => $value) {
            $settings->{$key} = $value;
        }
        $settings->save();
        $this->callHook('afterSave');

        Notification::make()
            ->success()
            ->title('Halaman Pesanan berhasil diperbarui!')
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
                ->url(url(route('pesanan.index')))
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
