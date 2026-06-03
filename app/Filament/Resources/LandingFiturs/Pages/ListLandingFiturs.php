<?php

namespace App\Filament\Resources\LandingFiturs\Pages;

use App\Filament\Resources\LandingFiturs\LandingFiturResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLandingFiturs extends ListRecords
{
    protected static string $resource = LandingFiturResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
