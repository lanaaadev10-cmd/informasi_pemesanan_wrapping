<?php

namespace App\Filament\Resources\TentangKamis\Pages;

use App\Filament\Resources\TentangKamiResource;
use Filament\Resources\Pages\EditRecord;

class EditTentangKami extends EditRecord
{
    protected static string $resource = TentangKamiResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Jika belum ada record, ambil yang pertama atau buat default
        if (empty($data)) {
            $data = \App\Models\ProfilPerusahaan::first()?->toArray() ?? [];
        }
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
