<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $booking->booking_code }}</title>
    <style>
        @font-face {
            font-family: 'Inter';
            font-style: normal;
            font-weight: 400;
            src: url("{{ storage_path('fonts/Inter-Regular.ttf') }}") format('truetype');
        }
        @font-face {
            font-family: 'Inter';
            font-style: normal;
            font-weight: 600;
            src: url("{{ storage_path('fonts/Inter-SemiBold.ttf') }}") format('truetype');
        }
        @font-face {
            font-family: 'Inter';
            font-style: normal;
            font-weight: 700;
            src: url("{{ storage_path('fonts/Inter-Bold.ttf') }}") format('truetype');
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            font-weight: 400;
            color: #1a1a2e;
            background: #ffffff;
            padding: 36px 40px;
            line-height: 1.5;
        }

        /* ─────────── WATERMARK ─────────── */
        .watermark {
            position: fixed;
            top: 38%;
            left: 15%;
            font-size: 90px;
            font-weight: 700;
            color: rgba(34, 197, 94, 0.08);
            transform: rotate(-35deg);
            letter-spacing: 8px;
            z-index: -1;
            white-space: nowrap;
        }
        .watermark-pending {
            position: fixed;
            top: 38%;
            left: 5%;
            font-size: 90px;
            font-weight: 700;
            color: rgba(234, 88, 12, 0.08);
            transform: rotate(-35deg);
            letter-spacing: 6px;
            z-index: -1;
            white-space: nowrap;
        }

        /* ─────────── HEADER ─────────── */
        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .logo-area { vertical-align: middle; }
        .logo-img { max-height: 52px; max-width: 160px; }
        .logo-text-box {
            display: inline-block;
            background: #1a1a2e;
            color: #ffffff;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 2px;
            padding: 8px 16px;
            border-radius: 4px;
        }
        .logo-sub {
            font-size: 9px;
            color: #888;
            margin-top: 5px;
            letter-spacing: 0.5px;
        }
        .company-contact {
            font-size: 9.5px;
            color: #555;
            margin-top: 6px;
            line-height: 1.7;
        }
        .invoice-label-area { text-align: right; vertical-align: top; }
        .invoice-word {
            font-size: 32px;
            font-weight: 700;
            color: #ff6b00;
            letter-spacing: 2px;
            line-height: 1;
        }
        .invoice-code {
            font-size: 12px;
            font-weight: 600;
            color: #333;
            margin-top: 6px;
        }
        .invoice-date {
            font-size: 10px;
            color: #888;
            margin-top: 3px;
        }

        /* ─────────── DIVIDER ─────────── */
        .divider {
            border: none;
            border-top: 2px solid #1a1a2e;
            margin: 16px 0 20px 0;
        }
        .divider-thin {
            border: none;
            border-top: 1px solid #e5e5e5;
            margin: 14px 0;
        }

        /* ─────────── STATUS BADGE ─────────── */
        .status-row { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .status-badge-lunas {
            display: inline-block;
            background: #dcfce7;
            color: #15803d;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1px;
            padding: 5px 14px;
            border-radius: 20px;
            border: 1.5px solid #86efac;
        }
        .status-badge-pending {
            display: inline-block;
            background: #fff7ed;
            color: #c2410c;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1px;
            padding: 5px 14px;
            border-radius: 20px;
            border: 1.5px solid #fdba74;
        }

        /* ─────────── INFO CARDS ─────────── */
        .info-cards-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .info-card-cell {
            width: 33.33%;
            vertical-align: top;
            padding: 0 6px 0 0;
        }
        .info-card-cell:last-child { padding-right: 0; }
        .info-card-inner {
            border: 1px solid #e5e5e5;
            border-radius: 6px;
            padding: 12px 14px;
            height: 100%;
        }
        .info-card-label {
            font-size: 8.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #ff6b00;
            margin-bottom: 8px;
            padding-bottom: 6px;
            border-bottom: 1px solid #f0f0f0;
        }
        .info-card-name {
            font-size: 12px;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 5px;
        }
        .info-card-detail {
            font-size: 10px;
            color: #555;
            line-height: 1.7;
        }

        /* ─────────── ITEMS TABLE ─────────── */
        .section-title {
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #888;
            margin-bottom: 8px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            border: 1px solid #e5e5e5;
            border-radius: 6px;
        }
        .items-table th {
            background: #f8f8f8;
            padding: 9px 12px;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #666;
            border-bottom: 1px solid #e5e5e5;
        }
        .items-table td {
            padding: 13px 12px;
            font-size: 11px;
            color: #333;
            border-bottom: 1px solid #f5f5f5;
        }
        .items-table tr:last-child td { border-bottom: none; }
        .td-name { font-weight: 700; color: #1a1a2e; font-size: 12px; }
        .td-note { font-size: 9.5px; color: #999; margin-top: 3px; }

        /* ─────────── TOTALS ─────────── */
        .totals-outer { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        .totals-spacer { width: 55%; }
        .totals-box {
            border: 1.5px solid #1a1a2e;
            border-radius: 6px;
            padding: 14px 16px;
        }
        .total-row-table { width: 100%; border-collapse: collapse; }
        .total-row-table td { padding: 4px 0; font-size: 11px; color: #555; }
        .total-row-table td:last-child { text-align: right; font-weight: 500; color: #333; }
        .total-grand-row td {
            padding-top: 10px;
            font-size: 14px;
            font-weight: 700;
            color: #1a1a2e;
            border-top: 1.5px solid #e0e0e0;
        }
        .total-grand-row td:last-child { color: #ff6b00; }

        /* ─────────── FOOTER ─────────── */
        .footer-table { width: 100%; border-collapse: collapse; }
        .footer-note {
            font-size: 9px;
            color: #aaa;
            line-height: 1.7;
        }
        .footer-right {
            text-align: right;
            font-size: 9px;
            color: #bbb;
        }
        .footer-brand {
            font-size: 11px;
            font-weight: 700;
            color: #888;
            text-align: right;
        }

        /* ─────────── LUNAS STAMP ─────────── */
        .lunas-stamp {
            display: inline-block;
            border: 3px solid #15803d;
            border-radius: 6px;
            padding: 3px 12px;
            color: #15803d;
            font-size: 18px;
            font-weight: 700;
            letter-spacing: 4px;
            transform: rotate(-15deg);
            opacity: 0.35;
            margin-top: 4px;
        }
    </style>
</head>
<body>

@php
    $layanan      = $booking->layanan;
    $harga        = $layanan?->harga ?? 0;
    $isDp         = $booking->payment_type === 'dp';
    $nominalBayar = $isDp ? 100000 : $harga;

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
                <div class="td-name">{{ $layanan?->nama_layanan ?? 'Paket Wrapping' }}</div>
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
                    @if($isDp)
                    <tr>
                        <td style="color:#c2410c;">Sisa Tagihan</td>
                        <td style="color:#c2410c;">Rp {{ number_format($harga - 100000, 0, ',', '.') }}</td>
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
