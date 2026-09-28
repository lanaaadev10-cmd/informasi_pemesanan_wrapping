<?php

namespace App\Filament\Widgets;

use App\Models\Rating;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentRatingsWidget extends BaseWidget
{
    protected static ?string $heading = 'Rating & Testimoni Terbaru';
    protected static ?string $description = '5 ulasan paling baru dari pelanggan';
    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    protected static ?int $defaultPaginationPageOption = 5;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Rating::with(['user', 'layanan', 'pesanan'])
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pelanggan')
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('layanan.nama_layanan')
                    ->label('Layanan')
                    ->searchable(),

                Tables\Columns\TextColumn::make('rating')
                    ->label('Rating')
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state >= 4 => 'success',
                        $state >= 3 => 'warning',
                        default => 'danger',
                    })
                    ->formatStateUsing(fn (int $state): string => str_repeat('★', $state) . str_repeat('☆', 5 - $state)),

                Tables\Columns\TextColumn::make('ulasan')
                    ->label('Ulasan')
                    ->limit(40)
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('balasan_admin')
                    ->label('Balasan')
                    ->badge()
                    ->color(fn ($state): string => $state ? 'success' : 'gray')
                    ->formatStateUsing(fn ($state): string => $state ? 'Sudah dibalas' : 'Belum dibalas'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->paginated(false);
    }
}