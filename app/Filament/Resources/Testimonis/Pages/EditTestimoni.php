<?php

namespace App\Filament\Resources\Testimonis\Pages;

use App\Filament\Resources\Testimonis\TestimoniResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTestimoni extends EditRecord
{
    protected static string $resource = TestimoniResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
