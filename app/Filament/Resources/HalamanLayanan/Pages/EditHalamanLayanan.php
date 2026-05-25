<?php

namespace App\Filament\Resources\HalamanLayanan\Pages;

use App\Filament\Resources\HalamanLayananResource;
use App\Models\ProfilPerusahaan;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditHalamanLayanan extends EditRecord
{
    protected static string $resource = HalamanLayananResource::class;

    protected static ?string $title = 'Edit Halaman Layanan';

    public function mount($record = null): void
    {
        $this->record = ProfilPerusahaan::first();

        if (! $this->record) {
            $this->record = ProfilPerusahaan::create([
                'nama_perusahaan' => 'Dantie Sticker',
                'layanan_hero_title' => 'Precision in Every Layer.',
            ]);
        }

        $this->fillForm();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('view_website')
                ->label('Lihat Halaman Layanan')
                ->icon('heroicon-m-arrow-top-right-on-square')
                ->color('gray')
                ->url(url('/layanan'))
                ->openUrlInNewTab(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Konten halaman layanan berhasil diperbarui!';
    }
}
