<?php

namespace App\Filament\Resources\Company\Pages;

use App\Filament\Resources\CompanyResource;
use App\Settings\CompanySettings;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Model;

class EditCompany extends EditRecord
{
    protected static string $resource = CompanyResource::class;

    protected static ?string $title = 'Profil Perusahaan';

    public function mount($record = null): void
    {
        $this->record = $this->getRecord();
        $this->authorizeAccess();
        $settings = app(CompanySettings::class);
        $data = $settings->toArray();
        $defaults = (new \ReflectionClass($settings))->getDefaultProperties();
        $this->form->fill(array_merge($defaults, $data));
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Informasi Perusahaan')
                    ->icon('heroicon-o-building-office')
                    ->schema([
                        TextInput::make('nama_perusahaan')
                            ->label('Nama Perusahaan')
                            ->required()
                            ->helperText('Nama resmi perusahaan yang akan ditampilkan di berbagai tempat.'),
                        Textarea::make('deskripsi')
                            ->label('Deskripsi')
                            ->rows(3)
                            ->helperText('Deskripsi singkat tentang perusahaan (untuk profil, tentang kami, dll).'),
                        Textarea::make('alamat')
                            ->label('Alamat')
                            ->rows(2)
                            ->helperText('Alamat lengkap kantor/studio perusahaan.'),
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->helperText('Email resmi perusahaan untuk hubungi kami.'),
                        TextInput::make('nomor_telepon')
                            ->label('Nomor Telepon')
                            ->helperText('Nomor telepon perusahaan (digunakan di halaman kontak dan invoice).'),
                    ]),

                Section::make('Logo')
                    ->icon('heroicon-o-photo')
                    ->schema([
                        FileUpload::make('logo')
                            ->label('Logo Perusahaan')
                            ->image()
                            ->disk('public')
                            ->directory('company')
                            ->helperText('Logo perusahaan (JPG/PNG, max 10MB). Akan ditampilkan di navbar dan invoice.'),
                    ]),

                Section::make('Sosial Media & Kontak')
                    ->icon('heroicon-o-globe-alt')
                    ->schema([
                        TextInput::make('maps_url')
                            ->label('Google Maps URL')
                            ->helperText('Link Google Maps lokasi perusahaan (untuk tombol "Buka Maps").'),
                        TextInput::make('instagram_url')
                            ->label('Instagram URL')
                            ->helperText('URL profil Instagram perusahaan.'),
                        TextInput::make('facebook_url')
                            ->label('Facebook URL')
                            ->helperText('URL halaman Facebook perusahaan.'),
                        TextInput::make('tiktok_url')
                            ->label('TikTok URL')
                            ->helperText('URL akun TikTok perusahaan.'),
                        TextInput::make('whatsapp_url')
                            ->label('WhatsApp URL')
                            ->helperText('URL WhatsApp (format: https://wa.me/62xxxxxxxxx).'),
                    ]),
            ]);
    }

    public function save(bool $shouldRedirect = true, bool $shouldSendSavedNotification = true): void
    {
        $this->callHook('beforeValidate');
        $data = $this->form->getState();
        $this->callHook('beforeSave');

        $settings = app(CompanySettings::class);
        $defaults = (new \ReflectionClass($settings))->getDefaultProperties();
        $data = array_merge($defaults, $settings->toArray(), $data);
        foreach ($data as $key => $value) {
            $settings->{$key} = $value;
        }
        $settings->save();
        $this->callHook('afterSave');

        Notification::make()
            ->success()
            ->title('Profil Perusahaan berhasil diperbarui!')
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
