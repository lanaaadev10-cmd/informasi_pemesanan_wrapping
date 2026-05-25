<?php

namespace App\Filament\Resources\Pesanans\Pesanans\Pages;

use App\Filament\Resources\Pesanans\Pesanans\PesananResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPesanan extends ViewRecord
{
    protected static string $resource = PesananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
