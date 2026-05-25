<?php

namespace App\Filament\Resources\HalamanGaleri\Pages;

use App\Filament\Resources\HalamanGaleriResource;
use App\Models\ProfilPerusahaan;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditHalamanGaleri extends EditRecord
{
    protected static string $resource = HalamanGaleriResource::class;

    protected static ?string $title = 'Edit Halaman Galeri';

    public function mount($record = null): void
    {
        $this->record = ProfilPerusahaan::first();

        if (! $this->record) {
            $this->record = ProfilPerusahaan::create([
                'nama_perusahaan' => 'Dantie Sticker',
                'galeri_hero_title' => 'Precision Mastery Gallery',
            ]);
        }

        $this->fillForm();
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

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Konten halaman galeri berhasil diperbarui!';
    }
}
