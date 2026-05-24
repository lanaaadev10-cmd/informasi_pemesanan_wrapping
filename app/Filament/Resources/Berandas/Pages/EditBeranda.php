<?php

namespace App\Filament\Resources\Berandas\Pages;

use App\Filament\Resources\BerandaResource;
use App\Models\ProfilPerusahaan;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditBeranda extends EditRecord
{
    protected static string $resource = BerandaResource::class;

    protected static ?string $title = 'Edit Beranda';

    public function mount($record = null): void
    {
        $this->record = ProfilPerusahaan::first();

        if (! $this->record) {
            $this->record = ProfilPerusahaan::create([
                'nama_perusahaan' => 'Dantie Sticker',
                'home_badge_text' => 'Professional Car Wrapping Indonesia',
                'home_hero_title_line1' => 'Elevasi Estetika',
                'home_hero_title_line2' => 'Aset Mewah Anda.',
                'home_subtitle' => 'Layanan premium yang melindungi dan memperindah mobil kesayangan Anda. Hubungi kami untuk penawaran terbaik.',
                'home_stat1_value' => '500+',
                'home_stat1_label' => 'Supercars Wrapped',
                'home_stat2_value' => '5 Tahun',
                'home_stat2_label' => 'Garansi Material',
            ]);
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
        return 'Konten halaman beranda berhasil diperbarui!';
    }
}
