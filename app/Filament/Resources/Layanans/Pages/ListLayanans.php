<?php

namespace App\Filament\Resources\Layanans\Pages;

use App\Filament\Resources\Layanans\LayananResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\CreateAction;

class ListLayanans extends ListRecords
{
    protected static string $resource = LayananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}