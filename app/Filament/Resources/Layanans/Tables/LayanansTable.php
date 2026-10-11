<?php

namespace App\Filament\Resources\Layanans\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class LayanansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('id_layanan', 'asc')
            ->columns([
                TextColumn::make('id_layanan')
                    ->label('ID')
                    ->sortable()
                    ->width('60px'),

                ImageColumn::make('foto_contoh')
                    ->label('Foto')
                    ->disk('public')
                    ->square()
                    ->defaultImageUrl(asset('images/layanan/wrapping-mobil.jpg')),

                TextColumn::make('nama_layanan')
                    ->label('Nama Layanan / Paket')
                    ->searchable()
                    ->weight('bold')
                    ->sortable()
                    ->wrap(),

                TextColumn::make('kategori')
                    ->label('Kendaraan')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'mobil' => 'info',
                        'motor' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (?string $state) => match ($state) {
                        'mobil' => '🚗 Mobil',
                        'motor' => '🛵 Motor',
                        default => ucfirst($state ?? '-'),
                    })
                    ->sortable(),

                TextColumn::make('tipe_paket')
                    ->label('Tipe Pengerjaan')
                    ->badge()
                    ->color('gray')
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'wrapping'    => 'Full Wrap',
                        'jasa-pasang' => 'Jasa Pasang',
                        'partial'     => 'Parsial / Atap',
                        'window-film' => 'Kaca Film',
                        'lighting'    => 'Lampu Bi-LED',
                        'audio'       => 'Audio Mobil',
                        'striping'    => 'Striping & Decal',
                        default       => ucfirst(str_replace('-', ' ', $state ?? '-')),
                    })
                    ->sortable(),

                TextColumn::make('jenis_layanan')
                    ->label('Model Bahan')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'paket'       => 'success',
                        'jasa_pasang' => 'info',
                        'custom'      => 'warning',
                        default       => 'gray',
                    })
                    ->formatStateUsing(fn (?string $state) => match ($state) {
                        'paket'       => '📦 Paket All-In',
                        'jasa_pasang' => '✂️ Jasa Pasang',
                        'custom'      => '🎨 Custom Project',
                        default       => ucfirst($state ?? '-'),
                    })
                    ->sortable(),

                TextColumn::make('harga')
                    ->label('Tarif / Harga')
                    ->money('IDR')
                    ->sortable()
                    ->weight('bold')
                    ->color(fn ($record) => $record->tipe_layanan === 'fix' ? 'success' : 'warning')
                    ->placeholder('Custom Quote'),

                TextColumn::make('estimasi_waktu')
                    ->label('Estimasi')
                    ->placeholder('-'),

                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('kategori')
                    ->label('Kategori Kendaraan')
                    ->options([
                        'mobil' => '🚗 Mobil (Roda 4)',
                        'motor' => '🛵 Sepeda Motor (Roda 2)',
                    ]),

                SelectFilter::make('jenis_layanan')
                    ->label('Model Penyediaan Bahan')
                    ->options([
                        'paket'       => '📦 Paket All-In (Bahan Toko)',
                        'jasa_pasang' => '✂️ Jasa Pasang Saja (Bawa Bahan)',
                        'custom'      => '🎨 Custom Project',
                    ]),

                SelectFilter::make('tipe_paket')
                    ->label('Tipe Pengerjaan')
                    ->options([
                        'wrapping'    => 'Full Body Wrapping',
                        'partial'     => 'Wrapping Parsial / Atap',
                        'jasa-pasang' => 'Jasa Pasang Decal / Panel',
                        'window-film' => 'Kaca Film Tolak Panas',
                        'lighting'    => 'Lampu Bi-LED',
                        'audio'       => 'Audio Mobil',
                        'striping'    => 'Striping & Decal',
                    ]),

                SelectFilter::make('tipe_layanan')
                    ->label('Model Tarif')
                    ->options([
                        'fix'    => 'Fixed Price (Harga Pasti)',
                        'custom' => 'Custom Quote (Estimasi)',
                    ]),

                TernaryFilter::make('is_active')
                    ->label('Status Aktif')
                    ->placeholder('Semua Status')
                    ->trueLabel('Hanya Layanan Aktif')
                    ->falseLabel('Hanya Layanan Nonaktif'),

                TrashedFilter::make(),
            ])
            ->actions([
                \Filament\Actions\ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
                \Filament\Actions\RestoreAction::make(),
                \Filament\Actions\ForceDeleteAction::make(),
            ]);
    }
}