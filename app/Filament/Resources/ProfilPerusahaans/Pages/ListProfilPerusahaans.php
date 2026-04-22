<?php

namespace App\Filament\Resources\ProfilPerusahaans\Pages;

use App\Filament\Resources\ProfilPerusahaans\ProfilPerusahaanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProfilPerusahaans extends ListRecords
{
    protected static string $resource = ProfilPerusahaanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
