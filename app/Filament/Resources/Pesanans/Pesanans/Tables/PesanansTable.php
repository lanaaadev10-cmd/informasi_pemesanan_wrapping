<?php

namespace App\Filament\Resources\Pesanans\Pesanans\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;
use App\Models\Pesanan;
use App\Filament\Resources\Pesanans\Pesanans\PesananResource;

class PesanansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_pesanan')
                    ->label('Order ID')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('user.name')
                    ->label('Pelanggan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('total_harga')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'menunggu_verifikasi' => 'warning',
                        'perlu_diperbaiki'    => 'danger',
                        'diverifikasi'       => 'info',
                        'menunggu_pembayaran' => 'warning',
                        'menunggu_konfirmasi' => 'danger',
                        'dibayar'            => 'info',
                        'selesai'            => 'success',
                        'dibatalkan'         => 'danger',
                        default => 'gray',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'menunggu_verifikasi' => 'heroicon-m-magnifying-glass',
                        'perlu_diperbaiki'    => 'heroicon-m-exclamation-triangle',
                        'diverifikasi'       => 'heroicon-m-check-badge',
                        'menunggu_pembayaran' => 'heroicon-m-credit-card',
                        'menunggu_konfirmasi' => 'heroicon-m-banknotes',
                        'dibayar'            => 'heroicon-m-wrench-screwdriver',
                        'selesai'            => 'heroicon-m-check-circle',
                        'dibatalkan'         => 'heroicon-m-x-circle',
                        default => 'heroicon-m-question-mark-circle',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'menunggu_verifikasi' => 'Perlu Verifikasi',
                        'menunggu_konfirmasi' => 'Cek Bukti Bayar',
                        'dibayar'            => 'Dalam Proses',
                        default => str_replace('_', ' ', ucfirst($state))
                    }),
                ImageColumn::make('pembayaran.bukti_transfer')
                    ->label('Bukti Bayar')
                    ->square()
                    ->disk('public')
                    ->visibility('public')
                    ->defaultImageUrl(null),
                TextColumn::make('created_at')
                    ->label('Tgl Pesan')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'menunggu_verifikasi' => 'Perlu Verifikasi',
                        'menunggu_pembayaran' => 'Menunggu Pembayaran',
                        'menunggu_konfirmasi' => 'Cek Bukti Bayar',
                        'dibayar'            => 'Dalam Proses',
                        'selesai'            => 'Selesai',
                        'dibatalkan'         => 'Dibatalkan',
                    ]),
            ])
            ->actions([
                // AKSI 1: VERIFIKASI AWAL
                Action::make('verifikasiAwal')
                    ->label('Verifikasi Detail')
                    ->icon('heroicon-o-check-badge')
                    ->color('warning')
                    ->visible(fn (Pesanan $record): bool => $record->status === 'menunggu_verifikasi')
                    ->requiresConfirmation()
                    ->modalHeading('Verifikasi Pesanan')
                    ->modalDescription('Apakah data pesanan sudah benar? Jika iya, user akan diminta membayar.')
                    ->action(function (Pesanan $record) {
                        $record->update(['status' => 'menunggu_pembayaran']);
                        \App\Models\Notifikasi::create([
                            'id_user'    => $record->id_user,
                            'id_pesanan' => $record->id_pesanan,
                            'judul'      => 'Pesanan Diverifikasi',
                            'pesan'      => 'Pesanan Anda #' . $record->kode_pesanan . ' telah diverifikasi. Silakan lakukan pembayaran.',
                            'tipe'       => 'info',
                            'is_read'    => false,
                        ]);
                        \Filament\Notifications\Notification::make()->title('Pesanan Diverifikasi')->success()->send();
                    }),

                // AKSI 2: KONFIRMASI PEMBAYARAN
                Action::make('validasiBayar')
                    ->label('Validasi Pembayaran')
                    ->icon('heroicon-o-banknotes')
                    ->color('success')
                    ->visible(fn (Pesanan $record): bool => $record->status === 'menunggu_konfirmasi')
                    ->requiresConfirmation()
                    ->modalHeading('Validasi Bukti Transfer')
                    ->modalDescription('Pastikan uang sudah masuk ke rekening sebelum menekan tombol ini.')
                    ->action(function (Pesanan $record) {
                        $record->update(['status' => 'dibayar']);
                        \App\Models\Notifikasi::create([
                            'id_user'    => $record->id_user,
                            'id_pesanan' => $record->id_pesanan,
                            'judul'      => 'Pembayaran Diterima',
                            'pesan'      => 'Pembayaran Anda telah kami terima. Kami akan segera memproses pengerjaan kendaraan Anda.',
                            'tipe'       => 'info',
                            'is_read'    => false,
                        ]);
                        \Filament\Notifications\Notification::make()->title('Pembayaran Divalidasi')->success()->send();
                    }),

                // AKSI 3: SELESAIKAN PROSES
                Action::make('setSelesai')
                    ->label('Selesaikan Pengerjaan')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Pesanan $record): bool => $record->status === 'dibayar')
                    ->requiresConfirmation()
                    ->action(function (Pesanan $record) {
                        $record->update(['status' => 'selesai']);
                        \Filament\Notifications\Notification::make()->title('Pesanan Selesai')->success()->send();
                    }),

                Action::make('updateStatus')
                    ->label('Ganti Status')
                    ->icon('heroicon-o-chevron-up-down')
                    ->color('gray')
                    ->form([
                        \Filament\Forms\Components\Select::make('status')
                            ->options([
                                'menunggu_verifikasi' => 'Perlu Verifikasi',
                                'menunggu_pembayaran' => 'Menunggu Pembayaran',
                                'menunggu_konfirmasi' => 'Cek Bukti Bayar',
                                'dibayar'            => 'Dalam Proses',
                                'selesai'            => 'Selesai',
                                'dibatalkan'         => 'Dibatalkan',
                            ])
                            ->required(),
                    ])
                    ->action(function (Pesanan $record, array $data) {
                        $record->update(['status' => $data['status']]);
                        \Filament\Notifications\Notification::make()->title('Status Diperbarui')->success()->send();
                    }),

                Action::make('cetakInvoice')
                    ->label('Cetak Struk')
                    ->icon('heroicon-o-printer')
                    ->color('info')
                    ->url(fn (Pesanan $record): string => route('pesanan.invoice', $record->id_pesanan))
                    ->openUrlInNewTab(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                //
            ]);
    }
}
