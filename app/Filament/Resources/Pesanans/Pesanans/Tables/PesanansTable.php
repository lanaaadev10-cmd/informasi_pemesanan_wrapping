<?php

namespace App\Filament\Resources\Pesanans\Pesanans\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;
use App\Models\Pesanan;
use App\Models\Notifikasi;

class PesanansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('kode_pesanan')
                    ->label('Order ID')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable(),

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
                        'menunggu_konfirmasi_admin'      => 'warning',
                        'menunggu_pembayaran'            => 'info',
                        'menunggu_verifikasi_pembayaran' => 'warning',
                        'dikonfirmasi'                   => 'success',
                        'sedang_diproses'                => 'primary',
                        'selesai'                        => 'success',
                        'ditolak'                        => 'danger',
                        default                          => 'gray',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'menunggu_konfirmasi_admin'      => 'heroicon-m-clock',
                        'menunggu_pembayaran'            => 'heroicon-m-credit-card',
                        'menunggu_verifikasi_pembayaran' => 'heroicon-m-magnifying-glass',
                        'dikonfirmasi'                   => 'heroicon-m-check-badge',
                        'sedang_diproses'                => 'heroicon-m-wrench-screwdriver',
                        'selesai'                        => 'heroicon-m-check-circle',
                        'ditolak'                        => 'heroicon-m-x-circle',
                        default                          => 'heroicon-m-question-mark-circle',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'menunggu_konfirmasi_admin'      => 'Tunggu Konfirmasi',
                        'menunggu_pembayaran'            => 'Tunggu Pembayaran',
                        'menunggu_verifikasi_pembayaran' => 'Verifikasi Bayar',
                        'dikonfirmasi'                   => 'Pembayaran OK',
                        'sedang_diproses'                => 'Sedang Diproses',
                        'selesai'                        => 'Selesai',
                        'ditolak'                        => 'Ditolak',
                        default                          => ucfirst(str_replace('_', ' ', $state)),
                    }),

                ImageColumn::make('pembayaran.bukti_transfer')
                    ->label('Bukti Bayar')
                    ->square()
                    ->disk('public')
                    ->visibility('public')
                    ->defaultImageUrl(null),

                TextColumn::make('created_at')
                    ->label('Tgl Pesan')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        Pesanan::STATUS_MENUNGGU_KONFIRMASI_ADMIN      => 'Tunggu Konfirmasi Admin',
                        Pesanan::STATUS_MENUNGGU_PEMBAYARAN            => 'Menunggu Pembayaran',
                        Pesanan::STATUS_MENUNGGU_VERIFIKASI_PEMBAYARAN => 'Verifikasi Pembayaran',
                        Pesanan::STATUS_DIKONFIRMASI                   => 'Dikonfirmasi',
                        Pesanan::STATUS_SEDANG_DIPROSES                => 'Sedang Diproses',
                        Pesanan::STATUS_SELESAI                        => 'Selesai',
                        Pesanan::STATUS_DITOLAK                        => 'Ditolak',
                    ]),
            ])
            ->actions([

                // =====================================================
                // LANGKAH 2: Admin konfirmasi pesanan → menunggu_pembayaran
                // =====================================================
                Action::make('konfirmasiPesanan')
                    ->label('✅ Konfirmasi Pesanan')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->visible(fn (Pesanan $record): bool =>
                        $record->status === Pesanan::STATUS_MENUNGGU_KONFIRMASI_ADMIN
                    )
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi Pesanan')
                    ->modalDescription('Pesanan sudah dicek dan valid? User akan mendapat notifikasi untuk melakukan pembayaran.')
                    ->modalSubmitActionLabel('Ya, Konfirmasi')
                    ->action(function (Pesanan $record) {
                        $record->update(['status' => Pesanan::STATUS_MENUNGGU_PEMBAYARAN]);
                        \Filament\Notifications\Notification::make()
                            ->title('Pesanan dikonfirmasi!')
                            ->body('User telah dinotifikasi untuk melakukan pembayaran.')
                            ->success()->send();
                    }),

                // =====================================================
                // LANGKAH 4: Admin verifikasi pembayaran → dikonfirmasi
                // =====================================================
                Action::make('verifikasiPembayaran')
                    ->label('💳 Verifikasi Pembayaran')
                    ->icon('heroicon-o-banknotes')
                    ->color('warning')
                    ->visible(fn (Pesanan $record): bool =>
                        $record->status === Pesanan::STATUS_MENUNGGU_VERIFIKASI_PEMBAYARAN
                    )
                    ->requiresConfirmation()
                    ->modalHeading('Verifikasi Pembayaran')
                    ->modalDescription('Pastikan uang sudah masuk ke rekening. User akan bisa mengunduh invoice setelah ini.')
                    ->modalSubmitActionLabel('Ya, Pembayaran Valid')
                    ->action(function (Pesanan $record) {
                        $record->update(['status' => Pesanan::STATUS_DIKONFIRMASI]);

                        // Update status pembayaran
                        if ($record->pembayaran) {
                            $record->pembayaran->update(['verifikasi_admin' => 'diverifikasi']);
                        }

                        // Notifikasi ke user dikirim otomatis via Pesanan::booted()
                        \Filament\Notifications\Notification::make()
                            ->title('Pembayaran diverifikasi!')
                            ->body('User telah dinotifikasi. Invoice tersedia untuk diunduh.')
                            ->success()->send();
                    }),

                // =====================================================
                // LANGKAH 5: Admin mulai proses → sedang_diproses
                // =====================================================
                Action::make('mulaiProses')
                    ->label('🔧 Mulai Kerjakan')
                    ->icon('heroicon-o-wrench-screwdriver')
                    ->color('primary')
                    ->visible(fn (Pesanan $record): bool =>
                        $record->status === Pesanan::STATUS_DIKONFIRMASI
                    )
                    ->requiresConfirmation()
                    ->modalHeading('Mulai Pengerjaan')
                    ->modalDescription('Tandai pesanan ini sedang dikerjakan. User akan mendapat notifikasi.')
                    ->modalSubmitActionLabel('Mulai Kerjakan')
                    ->action(function (Pesanan $record) {
                        $record->update(['status' => Pesanan::STATUS_SEDANG_DIPROSES]);
                        \Filament\Notifications\Notification::make()
                            ->title('Pengerjaan dimulai!')
                            ->success()->send();
                    }),

                // =====================================================
                // LANGKAH 6: Admin selesaikan → selesai
                // =====================================================
                Action::make('selesaikan')
                    ->label('🎉 Selesaikan')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Pesanan $record): bool =>
                        $record->status === Pesanan::STATUS_SEDANG_DIPROSES
                    )
                    ->requiresConfirmation()
                    ->modalHeading('Selesaikan Pesanan')
                    ->modalDescription('Pengerjaan sudah selesai? User akan mendapat notifikasi untuk mengambil kendaraan.')
                    ->modalSubmitActionLabel('Ya, Tandai Selesai')
                    ->action(function (Pesanan $record) {
                        $record->update(['status' => Pesanan::STATUS_SELESAI]);
                        \Filament\Notifications\Notification::make()
                            ->title('Pesanan diselesaikan!')
                            ->success()->send();
                    }),

                // =====================================================
                // OPSIONAL: Tolak pesanan
                // =====================================================
                Action::make('tolakPesanan')
                    ->label('❌ Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (Pesanan $record): bool => in_array($record->status, [
                        Pesanan::STATUS_MENUNGGU_KONFIRMASI_ADMIN,
                        Pesanan::STATUS_MENUNGGU_VERIFIKASI_PEMBAYARAN,
                    ]))
                    ->form([
                        \Filament\Forms\Components\Textarea::make('alasan_tolak')
                            ->label('Alasan Penolakan')
                            ->required()
                            ->placeholder('Jelaskan alasan penolakan pesanan ini...'),
                    ])
                    ->action(function (Pesanan $record, array $data) {
                        $record->update([
                            'status'        => \App\Enums\OrderStatus::DITOLAK,
                            'catatan_admin' => $data['alasan_tolak'],
                        ]);
                        // Notifikasi ke user dikirim otomatis via Pesanan::booted()
                        \Filament\Notifications\Notification::make()
                            ->title('Pesanan ditolak.')
                            ->danger()->send();
                    }),

                // =====================================================
                // AKSI LAINNYA
                // =====================================================
                Action::make('cetakInvoice')
                    ->label('🖨️ Invoice')
                    ->icon('heroicon-o-printer')
                    ->color('gray')
                    ->url(fn (Pesanan $record): string => route('pesanan.invoice', $record->id_pesanan))
                    ->openUrlInNewTab(),

                DeleteAction::make(),
            ])
            ->bulkActions([]);
    }
}
