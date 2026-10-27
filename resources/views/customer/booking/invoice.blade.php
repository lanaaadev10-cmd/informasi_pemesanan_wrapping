<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #BKG-{{ $booking->booking_code }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/regular/style.css" />
    <link rel="stylesheet" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/fill/style.css" />
    <link rel="stylesheet" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/bold/style.css" />

@php
    $layanan = $booking->layanan;
    $harga = (float) ($booking->base_price_snapshot ?? ($booking->quotation_amount ?? ($layanan?->harga ?? 0)));
    $payment = $booking->payment;
    $paymentType = $booking->payment_type;
    $isDp = $paymentType === 'dp';
    $nominalBayar = (float) ($payment?->amount ?? ($booking->dp_amount_snapshot ?? ($isDp ? round($harga * 0.5) : $harga)));
    $sisaTagihan = (float) ($booking->remaining_balance_snapshot ?? max(0, $harga - $nominalBayar));

    $tglBayar = $booking->updated_at
        ? \Carbon\Carbon::parse($booking->updated_at)->translatedFormat('d F Y')
        : \Carbon\Carbon::now()->translatedFormat('d F Y');

    $statusVal = $booking->status instanceof \App\Enums\BookingStatus ? $booking->status->value : (string) $booking->status;
    $isLunas = in_array($statusVal, ['approved', 'in_progress', 'completed']);
@endphp

    @include('dashboard.customer.pesanan.partials._gaya_invoice')
    @include('dashboard.customer.pesanan.partials._gaya_cetak_invoice')
</head>
<body>

{{-- Action Bar --}}
<div class="action-bar">
    <a href="{{ url()->previous() }}">
        <i class="ph-bold ph-arrow-left"></i> {{ $profil->cta_kembali ?? 'Kembali' }}
    </a>
    <div style="text-align:center; flex:1;">
        <span style="font-size:11px; color:#555;">Bukti Reservasi &amp; Invoice #{{ $booking->booking_code }}</span>
    </div>
    <button class="btn-print" onclick="window.print()">
        <i class="ph-bold ph-printer"></i> Download / Print PDF
    </button>
</div>

<div class="invoice-wrapper">

    {{-- HEADER --}}
    <div class="invoice-header">
        <div class="header-top">
            <div>
                <div class="brand-name">{{ $profil?->nama_perusahaan ?? 'DANTIE STIKER' }}</div>
                <div class="brand-sub">Premium Vehicle Wrapping &amp; Variasi Kendaraan</div>
                @if($profil)
                <div class="brand-contact">
                    {{ $profil->alamat ?? 'Workshop Dantie Stiker' }}<br>
                    {{ $profil->no_wa ?? $profil->nomor_telepon ?? '-' }} &nbsp;&middot;&nbsp; {{ $profil->email ?? '-' }}
                </div>
                @endif
            </div>
            <div class="invoice-meta">
                <div class="invoice-label">Invoice Booking</div>
                <div class="invoice-number">#{{ $booking->booking_code }}</div>
                <div class="badge-lunas" style="{{ $isLunas ? '' : 'background:#ff6b00; color:#000;' }}">
                    ✓ {{ $isLunas ? 'TERKONFIRMASI LUNAS' : 'BUKTI TERVERIFIKASI' }}
                </div>
            </div>
        </div>
        <div class="header-stats">
            <div class="stat-item">
                <div class="stat-label">Tanggal Reservasi</div>
                <div class="stat-value">{{ $booking->created_at?->translatedFormat('d F Y') ?? '-' }}</div>
            </div>
            <div class="stat-item">
                <div class="stat-label">Skema Pembayaran</div>
                <div class="stat-value">{{ $isDp ? 'Uang Muka (DP 50%)' : 'Pelunasan Penuh' }}</div>
            </div>
            <div class="stat-item">
                <div class="stat-label">Jadwal Pengerjaan</div>
                <div class="stat-value">
                    {{ $booking->booking_date ? \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('d F Y') : '-' }} ({{ $booking->booking_time ?: '09:00' }} WIB)
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-label">No. Booking</div>
                <div class="stat-value">{{ $booking->booking_code }}</div>
            </div>
        </div>
    </div>

    {{-- BILLED TO + VEHICLE INFO --}}
    <div class="info-row">
        <div class="info-card">
            <div class="info-section-label">Data Pelanggan (Billed To)</div>
            <div class="info-name">{{ $booking->customer_name ?: ($booking->user?->name ?? 'Pelanggan') }}</div>
            <div class="info-detail">
                Email: {{ $booking->customer_email ?: ($booking->user?->email ?? '-') }}<br>
                No. WhatsApp: {{ $booking->customer_phone ?: ($booking->user?->no_hp ?? '-') }}<br>
                Catatan: {{ $booking->notes ?: 'Tidak ada catatan tambahan' }}
            </div>
        </div>
        <div class="info-card">
            <div class="info-section-label">Spesifikasi Kendaraan</div>
            <div class="info-name">{{ $booking->vehicle_name ?: 'Kendaraan' }}</div>
            <div class="info-detail">
                Warna Asli: {{ $booking->vehicle_color ?: '-' }}<br>
                Plat Nomor Polisi: {{ $booking->vehicle_license ?: '-' }}<br>
                Lokasi Servis: Workshop Dantie Stiker
            </div>
        </div>
    </div>

    {{-- LINE ITEMS TABLE --}}
    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th style="text-align:left;">Paket Layanan Wrapping</th>
                    <th>Tipe</th>
                    <th>Jadwal Slot</th>
                    <th>Total Biaya</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="td-name">{{ $booking->service_name_snapshot ?? ($layanan?->nama_layanan ?? 'Proyek Custom Wrapping') }}</div>
                        <div class="td-note">Pengerjaan variasi &amp; wrapping kendaraan {{ $booking->vehicle_name }}</div>
                    </td>
                    <td style="color:#888; font-weight:600; text-transform:capitalize;">{{ $booking->payment_type }}</td>
                    <td class="td-price" style="text-align:center;">{{ $booking->booking_date?->translatedFormat('d M Y') }} - {{ $booking->booking_time ?: '09:00' }}</td>
                    <td class="td-subtotal">Rp {{ number_format($harga, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- TOTALS --}}
    <div class="totals-section">
        <div class="totals-box">
            <div class="total-row">
                <span>Total Biaya Layanan</span>
                <span>Rp {{ number_format($harga, 0, ',', '.') }}</span>
            </div>
            <div class="total-row">
                <span>Skema Transaksi</span>
                <span>{{ $isDp ? 'Uang Muka (DP 50%)' : 'Pelunasan Penuh' }}</span>
            </div>
            @if($isDp && $sisaTagihan > 0)
            <div class="total-row" style="color:#c2410c;">
                <span>Sisa Tagihan di Workshop</span>
                <span>Rp {{ number_format($sisaTagihan, 0, ',', '.') }}</span>
            </div>
            @endif
            <div class="total-grand">
                <div class="total-grand-label">Total Dibayarkan</div>
                <div class="total-grand-amount">Rp {{ number_format($nominalBayar, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    {{-- FOOTER --}}
    <div class="invoice-footer">
        <div>
            <div class="footer-printed">Dicetak: {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB</div>
        </div>
        <div class="footer-note">
            Dokumen resmi ini diterbitkan secara elektronik oleh sistem reservasi online.<br>
            Invoice #BKG-{{ $booking->booking_code }}
        </div>
        <div>
            <div class="footer-brand">{{ $profil?->nama_perusahaan ?? 'DANTIE STIKER' }}</div>
        </div>
    </div>

</div>

</body>
</html>
