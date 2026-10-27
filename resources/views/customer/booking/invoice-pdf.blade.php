<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $booking->booking_code }}</title>
    @include('customer.booking.partials._invoice_pdf_styles')
</head>
<body>

@php
    $layanan      = $booking->layanan;
    $harga        = (float) ($booking->base_price_snapshot ?? ($booking->quotation_amount ?? ($layanan?->harga ?? 0)));
    $payment      = $booking->payment;
    $isDp         = $booking->payment_type === 'dp';
    $nominalBayar = (float) ($payment?->amount ?? ($booking->dp_amount_snapshot ?? ($isDp ? round($harga * 0.5) : $harga)));
    $sisaTagihan  = (float) ($booking->remaining_balance_snapshot ?? max(0, $harga - $nominalBayar));

    $statusVal = $booking->status instanceof \App\Enums\BookingStatus
        ? $booking->status->value
        : (string) $booking->status;

    $isLunas = in_array($statusVal, ['approved', 'in_progress', 'completed', 'confirmed']);

    $customerName  = $booking->customer_name  ?: ($booking->user?->name  ?? 'Pelanggan');
    $customerEmail = $booking->customer_email ?: ($booking->user?->email ?? '-');
    $customerPhone = $booking->customer_phone ?: ($booking->user?->no_hp ?? '-');

    $logoPath = $profil->logo ?? null;
    $logoAbsPath = $logoPath ? storage_path('app/public/' . $logoPath) : null;
    $logoExists  = $logoAbsPath && file_exists($logoAbsPath);
@endphp

{{-- ── WATERMARK ── --}}
@if($isLunas)
<div class="watermark">LUNAS</div>
@else
<div class="watermark-pending">MENUNGGU VERIFIKASI</div>
@endif

{{-- ══════════════════════════════════════
     HEADER: Logo (kiri) | INVOICE (kanan)
     ══════════════════════════════════════ --}}
<table class="header-table">
    <tr>
        <td class="logo-area" style="width:55%;">
            @if($logoExists)
                <img src="{{ $logoAbsPath }}" class="logo-img" alt="Logo">
            @else
                <div class="logo-text-box">{{ strtoupper($profil?->nama_perusahaan ?? 'DANTIE STIKER') }}</div>
            @endif
            <div class="logo-sub">Premium Vehicle Wrapping &amp; Variasi Kendaraan</div>
            @if($profil)
            <div class="company-contact">
                {{ $profil->alamat ?? 'Workshop Dantie Stiker' }}<br>
                Telp: {{ $profil->nomor_telepon ?? '-' }}&nbsp;&nbsp;|&nbsp;&nbsp;Email: {{ $profil->email ?? '-' }}
            </div>
            @endif
        </td>
        <td class="invoice-label-area" style="width:45%;">
            <div class="invoice-word">INVOICE</div>
            <div class="invoice-code">#{{ $booking->booking_code }}</div>
            <div class="invoice-date">Diterbitkan: {{ $booking->created_at?->translatedFormat('d F Y') ?? now()->translatedFormat('d F Y') }}</div>
            <div style="margin-top:10px;">
                @if($isLunas)
                    <span class="status-badge-lunas">&#10003; LUNAS / TERKONFIRMASI</span>
                @else
                    <span class="status-badge-pending">&#8987; MENUNGGU VERIFIKASI</span>
                @endif
            </div>
        </td>
    </tr>
</table>

<hr class="divider">

{{-- ══════════════════════════════════════
     INFO CARDS: 3 kolom dalam kotak
     ══════════════════════════════════════ --}}
<table class="info-cards-table">
    <tr>
        {{-- Card 1: Pelanggan --}}
        <td class="info-card-cell">
            <div class="info-card-inner">
                <div class="info-card-label">Ditagihkan Kepada</div>
                <div class="info-card-name">{{ $customerName }}</div>
                <div class="info-card-detail">
                    {{ $customerEmail }}<br>
                    {{ $customerPhone }}
                    @if($booking->notes)
                    <br><span style="color:#aaa; font-size:9px;">Catatan: {{ $booking->notes }}</span>
                    @endif
                </div>
            </div>
        </td>
        {{-- Card 2: Kendaraan --}}
        <td class="info-card-cell">
            <div class="info-card-inner">
                <div class="info-card-label">Detail Kendaraan</div>
                <div class="info-card-name">{{ $booking->vehicle_name ?: '-' }}</div>
                <div class="info-card-detail">
                    Warna&nbsp;&nbsp;: {{ $booking->vehicle_color ?: '-' }}<br>
                    Plat No : {{ $booking->vehicle_license ?: '-' }}<br>
                    Lokasi&nbsp;&nbsp;: Workshop Dantie Stiker
                </div>
            </div>
        </td>
        {{-- Card 3: Info Booking --}}
        <td class="info-card-cell">
            <div class="info-card-inner">
                <div class="info-card-label">Info Booking</div>
                <div class="info-card-name">{{ $booking->booking_code }}</div>
                <div class="info-card-detail">
                    <strong style="font-weight:600;">Tgl Reservasi</strong><br>
                    {{ $booking->created_at?->translatedFormat('d F Y') ?? '-' }}<br><br>
                    <strong style="font-weight:600;">Jadwal Pengerjaan</strong><br>
                    {{ $booking->booking_date
                        ? \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('d F Y')
                        : '-' }}
                    {{ $booking->booking_time ? '· ' . $booking->booking_time . ' WIB' : '' }}
                </div>
            </div>
        </td>
    </tr>
</table>

{{-- ══════════════════════════════════════
     ITEMS TABLE
     ══════════════════════════════════════ --}}
<div class="section-title">Rincian Layanan</div>
<table class="items-table">
    <thead>
        <tr>
            <th style="text-align:left; width:48%;">Deskripsi Layanan</th>
            <th style="text-align:center; width:14%;">Skema</th>
            <th style="text-align:right; width:18%;">Harga Layanan</th>
            <th style="text-align:right; width:20%;">Jumlah Dibayar</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                <div class="td-name">{{ $booking->service_name_snapshot ?? ($layanan?->nama_layanan ?? 'Proyek Custom Wrapping') }}</div>
                <div class="td-note">Kendaraan: {{ $booking->vehicle_name ?? '-' }} &nbsp;|&nbsp; Workshop Dantie Stiker</div>
            </td>
            <td style="text-align:center; color:#777; font-weight:600; font-size:10px; text-transform:uppercase;">
                {{ $isDp ? 'DP' : 'Penuh' }}
            </td>
            <td style="text-align:right;">Rp {{ number_format($harga, 0, ',', '.') }}</td>
            <td style="text-align:right; font-weight:700; color:#1a1a2e; font-size:12px;">
                Rp {{ number_format($nominalBayar, 0, ',', '.') }}
            </td>
        </tr>
    </tbody>
</table>

{{-- ══════════════════════════════════════
     TOTALS
     ══════════════════════════════════════ --}}
<table class="totals-outer">
    <tr>
        <td class="totals-spacer">
            {{-- Ruang kosong kiri --}}
        </td>
        <td style="width:45%; vertical-align:top;">
            <div class="totals-box">
                <table class="total-row-table">
                    <tr>
                        <td>Subtotal Layanan</td>
                        <td>Rp {{ number_format($harga, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Skema Pembayaran</td>
                        <td>{{ $isDp ? 'Uang Muka (DP)' : 'Pelunasan Penuh' }}</td>
                    </tr>
                    @if($isDp && $sisaTagihan > 0)
                    <tr>
                        <td style="color:#c2410c;">Sisa Tagihan di Workshop</td>
                        <td style="color:#c2410c;">Rp {{ number_format($sisaTagihan, 0, ',', '.') }}</td>
                    </tr>
                    @endif
                    <tr class="total-grand-row">
                        <td>Total Dibayarkan</td>
                        <td>Rp {{ number_format($nominalBayar, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
</table>

{{-- ══════════════════════════════════════
     FOOTER
     ══════════════════════════════════════ --}}
<hr class="divider-thin">
<table class="footer-table">
    <tr>
        <td style="vertical-align:bottom; width:65%;">
            <div class="footer-note">
                Dokumen ini diterbitkan secara elektronik dan sah tanpa tanda tangan basah.<br>
                Harap simpan sebagai bukti transaksi yang sah bersama {{ $profil?->nama_perusahaan ?? 'Dantie Stiker' }}.
            </div>
        </td>
        <td style="vertical-align:bottom; text-align:right; width:35%;">
            <div class="footer-right">Dicetak: {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB</div>
            <div class="footer-brand">{{ strtoupper($profil?->nama_perusahaan ?? 'DANTIE STIKER') }}</div>
        </td>
    </tr>
</table>

</body>
</html>
