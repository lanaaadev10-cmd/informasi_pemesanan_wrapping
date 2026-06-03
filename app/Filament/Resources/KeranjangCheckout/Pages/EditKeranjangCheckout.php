<?php

namespace App\Filament\Resources\KeranjangCheckout\Pages;

use App\Filament\Resources\KeranjangCheckoutResource;
use App\Settings\KeranjangCheckoutSettings;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Model;

class EditKeranjangCheckout extends EditRecord
{
    protected static string $resource = KeranjangCheckoutResource::class;

    protected static ?string $title = 'Edit Keranjang & Checkout';

    public function mount($record = null): void
    {
        $this->record = $this->getRecord();
        $this->authorizeAccess();
        $settings = app(KeranjangCheckoutSettings::class);
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
                    ->schema([
                        TextInput::make('keranjang_hero_text')->label('Hero Text')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                        TextInput::make('keranjang_title')->label('Title *')->required()->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                        TextInput::make('keranjang_subtitle')->label('Subtitle')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                    ]),

                Section::make('Keranjang')
                    ->schema([
                        TextInput::make('keranjang_empty_title')->label('Empty Title')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                        Textarea::make('keranjang_empty_desc')->label('Empty Description')->rows(2)->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                        TextInput::make('keranjang_warranty_title')->label('Warranty Title')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                        Textarea::make('keranjang_warranty_desc')->label('Warranty Description')->rows(2)->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                    ]),

                Section::make('Biaya & Tombol')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('keranjang_service_charge_label')->label('Service Charge Label')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            TextInput::make('keranjang_service_charge_amount')->label('Service Charge Amount')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            TextInput::make('keranjang_checkout_button_label')->label('Checkout Button')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                        ]),
                    ]),

                Section::make('Checkout Steps')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Grid::make(4)->schema([
                            TextInput::make('checkout_step_1_label')->label('Step 1')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            TextInput::make('checkout_step_2_label')->label('Step 2')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            TextInput::make('checkout_step_3_label')->label('Step 3')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            TextInput::make('checkout_step_4_label')->label('Step 4')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('checkout_step2_title')->label('Step 2 Title')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            TextInput::make('checkout_step2_subtitle')->label('Step 2 Subtitle')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            TextInput::make('checkout_step3_title')->label('Step 3 Title')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            TextInput::make('checkout_step3_subtitle')->label('Step 3 Subtitle')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                        ]),
                    ]),

                Section::make('Warranty Badges')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('checkout_warranty_badge_1')->label('Badge 1')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            TextInput::make('checkout_warranty_badge_2')->label('Badge 2')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            TextInput::make('checkout_warranty_badge_3')->label('Badge 3')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                        ]),
                    ]),

                Section::make('Lainnya')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('keranjang_section_data_kendaraan_title')->label('Section Data Kendaraan Title')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            TextInput::make('checkout_estimasi_durasi_label')->label('Estimasi Durasi Label')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            TextInput::make('checkout_lokasi_pengerjaan_label')->label('Lokasi Pengerjaan Label')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            Textarea::make('checkout_terms_text')->label('Terms Text')->rows(3)->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            TextInput::make('checkout_confirm_button_label')->label('Confirm Button Label')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                        ]),
                    ]),
            ]);
    }

    public function save(bool $shouldRedirect = true, bool $shouldSendSavedNotification = true): void
    {
        $this->callHook('beforeValidate');
        $data = $this->form->getState();
        $this->callHook('beforeSave');

        $settings = app(KeranjangCheckoutSettings::class);
        $defaults = (new \ReflectionClass($settings))->getDefaultProperties();
        $data = array_merge($defaults, $settings->toArray(), $data);
        foreach ($data as $key => $value) {
            $settings->{$key} = $value;
        }
        $settings->save();
        $this->callHook('afterSave');

        Notification::make()
            ->success()
            ->title('Keranjang & Checkout berhasil diperbarui!')
            ->send();
    }

    public function getRecord(): Model
    {
        return new \App\Models\DummyModel;
    }

    protected function getHeaderActions(): array
    {
        return [];
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
