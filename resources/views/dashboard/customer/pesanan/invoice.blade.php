<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #WAP-{{ $pesanan->kode_pesanan }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/regular/style.css" />
    <link rel="stylesheet" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/fill/style.css" />
    <link rel="stylesheet" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/bold/style.css" />

@php
    $subtotal   = $pesanan->details->sum('subtotal');
    $totalFinal = $pesanan->total_harga;
    $pembayaran = $pesanan->pembayaran;
    $tglBayar   = $pembayaran?->tgl_bayar
        ? \Carbon\Carbon::parse($pembayaran->tgl_bayar)->translatedFormat('d F Y')
        : \Carbon\Carbon::parse($pesanan->updated_at)->translatedFormat('d F Y');

    $metodeRaw  = $pembayaran?->metode_pembayaran;
    $metodeStr  = $metodeRaw instanceof \App\Enums\PaymentMethod
        ? $metodeRaw->value
        : (string) ($metodeRaw ?? 'transfer_bank');
    $metodeLabel = match($metodeStr) {
        'transfer_bank'     => 'Transfer Bank',
        'transfer_e_wallet' => 'E-Wallet / QRIS',
        'cash'              => 'Tunai (Cash)',
        default             => ucfirst(str_replace('_', ' ', $metodeStr)),
    };

    $thumbnail = $pesanan->details->first()?->layanan?->foto_contoh;
    $imageUrl  = $thumbnail ? \App\Helpers\StaticContent::fotoUrl($thumbnail) : null;
@endphp

    @include('dashboard.customer.pesanan.partials._invoice-styles')
    @include('dashboard.customer.pesanan.partials._invoice-print-styles')
</head>
<body>

{{-- Action Bar --}}
<div class="action-bar">
    <a href="{{ url()->previous() }}">
        <i class="ph-bold ph-arrow-left"></i> {{ $profil->cta_kembali ?? 'Kembali' }}
    </a>
    <div style="text-align:center; flex:1;">
        <span style="font-size:11px; color:#555;">Invoice #WAP-{{ $pesanan->kode_pesanan }}</span>
    </div>
    <button class="btn-print" onclick="window.print()">
        <i class="ph-bold ph-printer"></i> Download / Print
    </button>
</div>

<div class="invoice-wrapper">

    {{-- HEADER --}}
    <div class="invoice-header">
        <div class="header-top">
            <div>
                <div class="brand-name">{{ $profil?->nama_perusahaan ?? 'WAPPING STUDIO' }}</div>
                <div class="brand-sub">Premium Vehicle Wrapping</div>
                @if($profil)
                <div class="brand-contact">
                    {{ $profil->alamat ?? '' }}<br>
                    {{ $profil->nomor_telepon ?? '' }} &nbsp;·&nbsp; {{ $profil->email ?? '' }}
                </div>
                @endif
            </div>
            <div class="invoice-meta">
                <div class="invoice-label">Invoice</div>
                <div class="invoice-number">#WAP-{{ $pesanan->kode_pesanan }}</div>
                <div class="badge-lunas">✓ {{ $profil->status_lunas ?? 'LUNAS' }}</div>
            </div>
        </div>
        <div class="header-stats">
            <div class="stat-item">
                <div class="stat-label">Tanggal Bayar</div>
                <div class="stat-value">{{ $tglBayar }}</div>
            </div>
            <div class="stat-item">
                <div class="stat-label">{{ $profil->label_metode_pembayaran ?? 'Metode Pembayaran' }}</div>
                <div class="stat-value">{{ $metodeLabel }}</div>
            </div>
            <div class="stat-item">
                <div class="stat-label">Jadwal Pengerjaan</div>
                <div class="stat-value">
                    {{ $pesanan->form?->jadwal_pengerjaan
                        ? \Carbon\Carbon::parse($pesanan->form->jadwal_pengerjaan)->translatedFormat('d F Y')
                        : '-' }}
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-label">No. Order</div>
                <div class="stat-value">{{ $pesanan->kode_pesanan }}</div>
            </div>
        </div>
    </div>

    {{-- BILLED TO + VEHICLE INFO --}}
    <div class="info-row">
        <div class="info-card">
            <div class="info-section-label">{{ $profil->invoice_billed_to ?? 'Billed To' }}</div>
            <div class="info-name">{{ $pesanan->form?->nama_pemesan ?? $pesanan->user->name }}</div>
            <div class="info-detail">
                {{ $pesanan->user->email }}<br>
                {{ $pesanan->form?->no_hp ?? '-' }}<br>
                @if($pesanan->form?->alamat_pengiriman)
                {{ $pesanan->form->alamat_pengiriman }}
                @endif
            </div>
        </div>
        <div class="info-card">
            <div class="info-section-label">{{ $profil->invoice_spesifikasi ?? 'Spesifikasi Kendaraan' }}</div>
            <div class="info-name">{{ $pesanan->form?->model_kendaraan ?? '-' }}</div>
            <div class="info-detail">
                Warna: {{ $pesanan->form?->warna_kendaraan ?? '-' }}<br>
                No. Polisi: {{ $pesanan->form?->nomor_polisi ?? '-' }}<br>
                Lokasi: {{ ucfirst($pesanan->form?->lokasi_pengerjaan ?? '-') }}
            </div>
        </div>
    </div>

    {{-- LINE ITEMS TABLE --}}
    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th style="text-align:left;">{{ $profil->section_pilih_layanan ?? 'Layanan' }}</th>
                    <th>Qty</th>
                    <th>Harga Satuan</th>
                    <th>{{ $profil->label_subtotal ?? 'Subtotal' }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pesanan->details as $item)
                <tr>
                    <td>
                        <div class="td-name">{{ $item->layanan?->nama_layanan ?? $item->layanan?->nama_paket ?? '-' }}</div>
                        @if($item->catatan_custom ?? null)
                        <div class="td-note">{{ $item->catatan_custom }}</div>
                        @endif
                    </td>
                    <td style="color:#888; font-weight:600;">{{ $item->jumlah }}x</td>
                    <td class="td-price">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                    <td class="td-subtotal">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- TOTALS --}}
    <div class="totals-section">
        <div class="totals-box">
            <div class="total-row">
                <span>{{ $profil->label_subtotal ?? 'Subtotal Layanan' }}</span>
                <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
            </div>
            <div class="total-row">
                <span>{{ $profil->label_biaya_admin ?? 'Biaya Administrasi' }}</span>
                <span>Rp 0</span>
            </div>
            <div class="total-grand">
                <div class="total-grand-label">{{ $profil->label_total_tagihan ?? 'Total Tagihan' }}</div>
                <div class="total-grand-amount">Rp {{ number_format($totalFinal, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    {{-- FOOTER --}}
    <div class="invoice-footer">
        <div>
            <div class="footer-printed">{{ $profil->label_dicetak ?? 'Dicetak' }}: {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB</div>
        </div>
        <div class="footer-note">
            {{ $profil->invoice_legal ?? 'Dokumen ini berlaku sah tanpa tanda tangan basah' }}<br>
            Invoice #WAP-{{ $pesanan->kode_pesanan }}
        </div>
        <div>
            <div class="footer-brand">{{ $profil?->nama_perusahaan ?? 'WAPPING' }}</div>
            <div class="footer-brand-sub">{{ $profil->invoice_thankyou ?? 'Thank you for your trust' }}</div>
        </div>
    </div>

</div>
</body>
</html>
