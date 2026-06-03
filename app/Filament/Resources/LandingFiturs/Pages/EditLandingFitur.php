<?php

namespace App\Filament\Resources\LandingFiturs\Pages;

use App\Filament\Resources\LandingFiturs\LandingFiturResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLandingFitur extends EditRecord
{
    protected static string $resource = LandingFiturResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
