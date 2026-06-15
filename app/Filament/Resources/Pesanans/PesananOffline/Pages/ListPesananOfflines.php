<?php

namespace App\Filament\Resources\Pesanans\PesananOffline\Pages;

use App\Filament\Resources\Pesanans\PesananOffline\PesananOfflineResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListPesananOfflines extends ListRecords
{
    protected static string $resource = PesananOfflineResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('buatPesanan')
                ->label('Buat Pesanan Offline')
                ->icon('heroicon-o-plus')
                ->color('warning')
                ->url(route('admin.offline.orders.create'))
                ->openUrlInNewTab(),
        ];
    }
}
