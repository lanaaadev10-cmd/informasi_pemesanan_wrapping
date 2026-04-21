<?php

namespace App\Filament\Resources\ProfilPerusahaans\Pages;

use App\Filament\Resources\ProfilPerusahaans\ProfilPerusahaanResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProfilPerusahaan extends EditRecord
{
    protected static string $resource = ProfilPerusahaanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
