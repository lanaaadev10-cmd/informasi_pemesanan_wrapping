<?php

namespace App\Filament\Resources\TentangKamis\Pages;

use App\Filament\Concerns\EditSettingsPage;
use App\Filament\Resources\TentangKamiResource;
use App\Settings\TentangKamiSettings;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditTentangKami extends EditRecord
{
    use EditSettingsPage;

    protected static string $resource = TentangKamiResource::class;

    protected function getSettingsClass(): string
    {
        return TentangKamiSettings::class;
    }

    protected function getSavedNotificationTitle(): string
    {
        return 'Halaman Tentang Kami berhasil diperbarui!';
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('view_website')
                ->label('Lihat Halaman Tentang Kami')
                ->icon('heroicon-m-arrow-top-right-on-square')
                ->color('gray')
                ->url(url('/tentang-kami'))
                ->openUrlInNewTab(),
        ];
    }
}
