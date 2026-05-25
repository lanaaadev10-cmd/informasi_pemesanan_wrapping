<?php

namespace App\Filament\Resources\Pesanans\Pesanans\Pages;

use App\Filament\Resources\Pesanans\Pesanans\PesananResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPesanans extends ListRecords
{
    protected static string $resource = PesananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => \Filament\Schemas\Components\Tabs\Tab::make('Semua Pesanan'),
            'verifikasi' => \Filament\Schemas\Components\Tabs\Tab::make('Perlu Verifikasi')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'menunggu_verifikasi'))
                ->badge(\App\Models\Pesanan::where('status', 'menunggu_verifikasi')->count())
                ->badgeColor('warning'),
            'pembayaran' => \Filament\Schemas\Components\Tabs\Tab::make('Menunggu Bayar')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'menunggu_pembayaran'))
                ->badge(\App\Models\Pesanan::where('status', 'menunggu_pembayaran')->count()),
            'validasi' => \Filament\Schemas\Components\Tabs\Tab::make('Validasi Bayar')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'menunggu_konfirmasi'))
                ->badge(\App\Models\Pesanan::where('status', 'menunggu_konfirmasi')->count())
                ->badgeColor('danger'),
            'proses' => \Filament\Schemas\Components\Tabs\Tab::make('Dalam Proses')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'dibayar'))
                ->badge(\App\Models\Pesanan::where('status', 'dibayar')->count())
                ->badgeColor('info'),
            'selesai' => \Filament\Schemas\Components\Tabs\Tab::make('Selesai')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'selesai')),
        ];
    }
}
