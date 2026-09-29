{{-- SISI KIRI (7/12): TABEL PREVIEW TRANSAKSI LAPORAN PENJUALAN --}}
<div class="f-card-panel">
    <div class="f-section-header">
        <div class="f-section-header-left">
            <div class="f-section-icon-box">
                <x-heroicon-o-document-text />
            </div>
            <div>
                <h4 class="f-section-title">Ringkasan Transaksi Penjualan</h4>
                <p class="f-section-desc">
                    Periode: {{ \Illuminate\Support\Carbon::parse($startDate)->isoFormat('D MMM') }} s/d {{ \Illuminate\Support\Carbon::parse($endDate)->isoFormat('D MMM Y') }}
                </p>
            </div>
        </div>

        <a href="{{ route('filament.admin.resources.pesanans.index') }}" 
           style="font-size: 0.75rem; font-weight: 700; color: #ea580c; text-decoration: none;">
            Lihat Semua →
        </a>
    </div>

    @if($previewPesanan->count() > 0)
        <div style="overflow-x: auto;">
            <table class="f-table">
                <thead>
                    <tr>
                        <th>Kode / Pelanggan</th>
                        <th>Layanan & Unit</th>
                        <th>Status</th>
                        <th style="text-align: right;">Total</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($previewPesanan as $pesanan)
                        @php
                            $custName = $pesanan->user?->name ?? $pesanan->customer_name ?? 'Pelanggan Walk-In';
                            $layananName = $pesanan->details->first()?->layanan?->nama_layanan ?? 'Wrapping Custom';
                            $vehicleName = $pesanan->form?->nama_kendaraan ?? $pesanan->form?->tipe_kendaraan ?? '-';
                            $isWalkIn = ($pesanan->order_source === 'offline') || empty($pesanan->id_user);

                            $badgeClass = 'f-badge-info';
                            $statusLabel = $pesanan->status;
                            if (in_array($pesanan->status, [\App\Models\Pesanan::STATUS_SELESAI, 'selesai'])) {
                                $badgeClass = 'f-badge-success';
                                $statusLabel = 'Selesai';
                            } elseif (in_array($pesanan->status, [\App\Models\Pesanan::STATUS_DIKONFIRMASI, \App\Models\Pesanan::STATUS_SEDANG_DIPROSES])) {
                                $badgeClass = 'f-badge-info';
                                $statusLabel = 'Diproses';
                            } elseif (in_array($pesanan->status, [\App\Models\Pesanan::STATUS_MENUNGGU_KONFIRMASI_ADMIN, \App\Models\Pesanan::STATUS_MENUNGGU_VERIFIKASI_PEMBAYARAN])) {
                                $badgeClass = 'f-badge-warning';
                                $statusLabel = 'Butuh Verifikasi';
                            }
                        @endphp
                        <tr>
                            <td>
                                <div style="font-weight: 700; color: #111827; display: flex; align-items: center; gap: 0.35rem;">
                                    <span>{{ $pesanan->kode_pesanan }}</span>
                                    @if($isWalkIn)
                                        <span style="padding: 0.1rem 0.4rem; font-size: 0.6rem; font-weight: 800; border-radius: 0.3rem; background: #ffedd5; color: #c2410c;">Walk-In</span>
                                    @endif
                                </div>
                                <div style="font-size: 0.7rem; color: #6b7280;">
                                    {{ $custName }} • {{ \Illuminate\Support\Carbon::parse($pesanan->created_at)->format('d/m/Y') }}
                                </div>
                            </td>
                            <td>
                                <div style="font-weight: 600; color: #1f2937; max-width: 170px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    {{ $layananName }}
                                </div>
                                <div style="font-size: 0.7rem; color: #6b7280; max-width: 170px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    {{ $vehicleName }}
                                </div>
                            </td>
                            <td>
                                <span class="f-badge {{ $badgeClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td style="text-align: right; font-weight: 900; color: #111827; white-space: nowrap;">
                                Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}
                            </td>
                            <td style="text-align: center;">
                                <a href="{{ route('filament.admin.resources.pesanans.view', ['record' => $pesanan->id_pesanan]) }}" 
                                   style="display: inline-flex; align-items: center; justify-content: center; width: 1.75rem; height: 1.75rem; border-radius: 0.4rem; border: 1px solid #d1d5db; background: #ffffff; color: #4b5563; text-decoration: none;"
                                   title="Lihat Detail Pesanan">
                                    <x-heroicon-o-eye style="width: 1rem; height: 1rem;" />
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        {{-- Clean & Compact Empty State --}}
        <div class="f-empty-state">
            <div class="f-empty-icon-wrap">
                <x-heroicon-o-inbox />
            </div>
            <h5 style="font-size: 0.85rem; font-weight: 800; color: #1f2937; margin: 0;">
                Belum Ada Transaksi Pada Periode Ini
            </h5>
            <p style="font-size: 0.72rem; color: #6b7280; margin: 0.25rem 0 0.85rem 0;">
                Tidak ada pesanan yang sesuai dengan filter tanggal atau status terpilih.
            </p>
            <a href="{{ route('filament.admin.resources.pesanans.create') }}" 
               style="display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.45rem 0.9rem; border-radius: 0.6rem; background: #ea580c; color: #ffffff; font-size: 0.72rem; font-weight: 800; text-decoration: none; box-shadow: 0 2px 6px rgba(234, 88, 12, 0.2);">
                ➕ Buat Pesanan Baru
            </a>
        </div>
    @endif
</div>
