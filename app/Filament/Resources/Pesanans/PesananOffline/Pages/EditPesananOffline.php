<?php

namespace App\Filament\Resources\Pesanans\PesananOffline\Pages;

use App\Filament\Resources\Pesanans\PesananOffline\PesananOfflineResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditPesananOffline extends EditRecord
{
    protected static string $resource = PesananOfflineResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('kembali')
                ->label('Kembali')
                ->icon('heroicon-o-arrow-left')
                ->url(fn () => PesananOfflineResource::getUrl('index')),
        ];
    }
}
