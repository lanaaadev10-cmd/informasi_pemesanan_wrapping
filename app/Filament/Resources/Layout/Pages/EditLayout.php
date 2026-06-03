<?php

namespace App\Filament\Resources\Layout\Pages;

use App\Filament\Resources\LayoutResource;
use App\Settings\LayoutSettings;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Illuminate\Database\Eloquent\Model;

class EditLayout extends EditRecord
{
    protected static string $resource = LayoutResource::class;

    protected static ?string $title = 'Tampilan & Layout';

    public function mount($record = null): void
    {
        $this->record = $this->getRecord();
        $this->authorizeAccess();
        $settings = app(LayoutSettings::class);
        $data = $settings->toArray();
        $defaults = (new \ReflectionClass($settings))->getDefaultProperties();
        $this->form->fill(array_merge($defaults, $data));
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Tampilan')
                    ->icon('heroicon-o-swatch')
                    ->schema([
                        TextInput::make('accent_color')
                            ->label('Warna Aksen')
                            ->placeholder('#f2994a')
                            ->helperText('Warna utama untuk button, link, dan elemen highlight (format hex: #f2994a).'),
                        TextInput::make('primary_layout')
                            ->label('Layout Utama')
                            ->placeholder('default')
                            ->helperText('Tata letak halaman (gunakan: default, compact, atau modern).'),
                        Toggle::make('dark_mode')
                            ->label('Mode Gelap')
                            ->default(true)
                            ->helperText('Aktifkan untuk menggunakan tema gelap di website.'),
                    ]),

                Section::make('Katalog Layanan')
                    ->icon('heroicon-o-rectangle-stack')
                    ->schema([
                        TextInput::make('layanan_grid_columns')
                            ->label('Jumlah Kolom Grid')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(6)
                            ->default(3)
                            ->helperText('Jumlah kolom untuk menampilkan kartu layanan (1-6).'),
                        TextInput::make('layanan_card_style')
                            ->label('Gaya Kartu Layanan')
                            ->helperText('Gaya tampilan kartu (minimal, standard, premium).'),
                        Toggle::make('layanan_show_benefits')
                            ->label('Tampilkan Keuntungan')
                            ->default(true)
                            ->helperText('Tampilkan daftar keuntungan di setiap kartu layanan.'),
                        Toggle::make('layanan_show_warranty')
                            ->label('Tampilkan Garansi')
                            ->default(true)
                            ->helperText('Tampilkan informasi garansi di kartu layanan.'),
                    ]),
            ]);
    }

    public function save(bool $shouldRedirect = true, bool $shouldSendSavedNotification = true): void
    {
        $this->callHook('beforeValidate');
        $data = $this->form->getState();
        $this->callHook('beforeSave');

        $settings = app(LayoutSettings::class);
        $defaults = (new \ReflectionClass($settings))->getDefaultProperties();
        $data = array_merge($defaults, $settings->toArray(), $data);
        foreach ($data as $key => $value) {
            $settings->{$key} = $value;
        }
        $settings->save();
        $this->callHook('afterSave');

        Notification::make()
            ->success()
            ->title('Tampilan & Layout berhasil diperbarui!')
            ->send();
    }

    public function getRecord(): Model
    {
        return new \App\Models\DummyModel;
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
