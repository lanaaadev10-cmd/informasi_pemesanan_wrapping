<?php

namespace App\Filament\Resources\Company\Pages;

use App\Filament\Concerns\EditSettingsPage;
use App\Filament\Resources\CompanyResource;
use App\Settings\CompanySettings;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EditCompany extends EditRecord
{
    use EditSettingsPage;

    protected static string $resource = CompanyResource::class;

    protected static ?string $title = 'Setting Maps & Logo';

    protected function getSettingsClass(): string
    {
        return CompanySettings::class;
    }

    protected function getSavedNotificationTitle(): string
    {
        return 'Setting Maps & Logo berhasil diperbarui!';
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
                            ->helperText('Link Google Maps lokasi perusahaan (untuk tombol "Buka Maps" dan tampilan peta).'),
                        TextInput::make('jam_operasional')
                            ->label('Jam Operasional')
                            ->placeholder('Contoh: Senin - Sabtu, 08:00 - 17:00')
                            ->helperText('Jam kerja operasional perusahaan yang ditampilkan di halaman kontak.'),
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
}
