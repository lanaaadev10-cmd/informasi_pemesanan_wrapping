<?php

namespace App\Filament\Resources\Pesanans\Pages;

use App\Filament\Resources\Pesanans\PesananResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Section;

class ListPesanans extends ListRecords
{
    protected static string $resource = PesananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('➕ Buat Pesanan Walk-In')
                ->icon('heroicon-o-plus-circle'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => \Filament\Schemas\Components\Tabs\Tab::make('Semua Pesanan'),
            'offline' => \Filament\Schemas\Components\Tabs\Tab::make('🟠 Offline (Walk-in)')
                ->modifyQueryUsing(fn ($query) => $query->whereIn('order_source', ['offline', 'walk_in']))
                ->badge(\App\Models\Pesanan::whereIn('order_source', ['offline', 'walk_in'])->count())
                ->badgeColor('warning'),
            'verifikasi' => \Filament\Schemas\Components\Tabs\Tab::make('Perlu Verifikasi')
                ->modifyQueryUsing(fn ($query) => $query->where('status', \App\Models\Pesanan::STATUS_MENUNGGU_VERIFIKASI_PEMBAYARAN))
                ->badge(\App\Models\Pesanan::where('status', \App\Models\Pesanan::STATUS_MENUNGGU_VERIFIKASI_PEMBAYARAN)->count())
                ->badgeColor('warning'),
            'pembayaran' => \Filament\Schemas\Components\Tabs\Tab::make('Menunggu Bayar')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'menunggu_pembayaran'))
                ->badge(\App\Models\Pesanan::where('status', 'menunggu_pembayaran')->count()),
            'validasi' => \Filament\Schemas\Components\Tabs\Tab::make('Pembayaran OK')
                ->modifyQueryUsing(fn ($query) => $query->where('status', \App\Models\Pesanan::STATUS_DIKONFIRMASI))
                ->badge(\App\Models\Pesanan::where('status', \App\Models\Pesanan::STATUS_DIKONFIRMASI)->count())
                ->badgeColor('success'),
            'proses' => \Filament\Schemas\Components\Tabs\Tab::make('Dalam Proses')
                ->modifyQueryUsing(fn ($query) => $query->where('status', \App\Models\Pesanan::STATUS_SEDANG_DIPROSES))
                ->badge(\App\Models\Pesanan::where('status', \App\Models\Pesanan::STATUS_SEDANG_DIPROSES)->count())
                ->badgeColor('info'),
            'selesai' => \Filament\Schemas\Components\Tabs\Tab::make('Selesai')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'selesai')),
        ];
    }
}
