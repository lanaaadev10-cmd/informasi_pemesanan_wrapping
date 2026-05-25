<?php

namespace App\Filament\Resources\TentangKamis\Pages;

use App\Filament\Resources\TentangKamiResource;
use App\Models\ProfilPerusahaan;
use Filament\Resources\Pages\EditRecord;

class EditTentangKami extends EditRecord
{
    protected static string $resource = TentangKamiResource::class;

    public function mount(string|int $record = null): void
    {
        // Singleton pattern - always get/create first record
        $profil = ProfilPerusahaan::firstOrCreate(
            ['id' => 1],
            ['nama_perusahaan' => 'Perusahaan Anda']
        );
        parent::mount($profil->getKey());
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

