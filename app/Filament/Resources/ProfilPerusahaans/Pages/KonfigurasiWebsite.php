<?php

namespace App\Filament\Resources\ProfilPerusahaans\Pages;

use App\Filament\Resources\ProfilPerusahaans\ProfilPerusahaanResource;
use App\Models\ProfilPerusahaan;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class KonfigurasiWebsite extends EditRecord
{
    protected static string $resource = ProfilPerusahaanResource::class;

    protected static ?string $title = 'Konfigurasi Website';

    public function mount($record = null): void
    {
        // Selalu ambil data pertama (ID 1)
        $this->record = ProfilPerusahaan::first();

        if (! $this->record) {
            // Jika belum ada data, buat data default
            $this->record = ProfilPerusahaan::create(['nama_perusahaan' => 'Dantie Wrapping']);
        }

        $this->fillForm();
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

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Konfigurasi website berhasil diperbarui!';
    }
}
