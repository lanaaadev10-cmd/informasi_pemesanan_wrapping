<?php

namespace App\Filament\Resources\Ratings\Tables;

use App\Filament\Resources\Ratings\RatingResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class RatingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('tipe_rating')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'Pesanan' ? 'info' : 'warning'),

                TextColumn::make('layanan.nama_layanan')
                    ->label('Layanan')
                    ->searchable(),

                TextColumn::make('pesanan.kode_pesanan')
                    ->label('Kode Pesanan')
                    ->placeholder('-'),

                TextColumn::make('rating')
                    ->label('Bintang')
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state >= 4 => 'success',
                        $state >= 3 => 'warning',
                        default => 'danger',
                    }),

                TextColumn::make('ulasan')
                    ->label('Ulasan')
                    ->limit(40)
                    ->placeholder('-'),

                TextColumn::make('balasan_admin')
                    ->label('Balasan')
                    ->limit(30)
                    ->placeholder('-')
                    ->toggleable(),

                TextColumn::make('medias_count')
                    ->label('Foto')
                    ->counts('medias'),

                ToggleColumn::make('is_tampil')
                    ->label('Tampil'),

                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('is_tampil')
                    ->label('Status Tampil')
                    ->options([
                        '1' => 'Tampil',
                        '0' => 'Disembunyikan',
                    ]),
                SelectFilter::make('id_layanan')
                    ->label('Layanan')
                    ->relationship('layanan', 'nama_layanan'),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                ViewAction::make()
                    ->url(fn ($record) => RatingResource::getUrl('view', ['record' => $record])),
                DeleteAction::make(),
            ]);
    }
}
