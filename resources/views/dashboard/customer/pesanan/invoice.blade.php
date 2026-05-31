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

    $profil    = \App\Models\ProfilPerusahaan::first();
    $thumbnail = $pesanan->details->first()?->layanan?->foto_contoh;
    $imageUrl  = $thumbnail ? (str_starts_with($thumbnail, 'http') ? $thumbnail : asset('storage/' . $thumbnail)) : null;
@endphp

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', Arial, sans-serif;
            background: #0a0a0a;
            color: #fff;
            min-height: 100vh;
        }

        /* ── Floating Action Bar (screen only) ──────────── */
        .action-bar {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 999;
            background: rgba(10,10,10,0.95);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255,255,255,0.06);
            padding: 14px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }
        .action-bar a {
            color: #888;
            text-decoration: none;
            font-size: 12px;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: color .2s;
        }
        .action-bar a:hover { color: #fff; }
        .btn-print {
            background: #f2994a;
            color: #000;
            border: none;
            font-family: 'Inter', sans-serif;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 10px 22px;
            border-radius: 10px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background .2s, transform .1s;
            box-shadow: 0 4px 20px rgba(242,153,74,0.35);
        }
        .btn-print:hover { background: #e28a44; }
        .btn-print:active { transform: scale(0.97); }

        /* ── Invoice Wrapper ────────────────────────────── */
        .invoice-wrapper {
            max-width: 860px;
            margin: 80px auto 60px;
            padding: 0 16px;
        }

        /* ── CARD base ──────────────────────────────────── */
        .card {
            background: #111;
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 20px;
            overflow: hidden;
        }

        /* ── HEADER CARD ────────────────────────────────── */
        .invoice-header {
            background: linear-gradient(135deg, #0f0f0f 0%, #1a1a1a 100%);
            border-radius: 20px;
            padding: 40px;
            margin-bottom: 16px;
            border: 1px solid rgba(242,153,74,0.15);
            position: relative;
            overflow: hidden;
        }
        .invoice-header::before {
            content: '';
            position: absolute;
            top: -80px; right: -80px;
            width: 300px; height: 300px;
            background: rgba(242,153,74,0.06);
            border-radius: 50%;
        }
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 32px;
        }
        .brand-name {
            font-size: 28px;
            font-weight: 900;
            color: #f2994a;
            letter-spacing: -1px;
            line-height: 1;
        }
        .brand-sub {
            font-size: 10px;
            color: #555;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            margin-top: 5px;
        }
        .brand-contact {
            font-size: 10px;
            color: #555;
            margin-top: 10px;
            line-height: 1.8;
        }
        .invoice-meta { text-align: right; }
        .invoice-label {
            font-size: 9px;
            color: #555;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 6px;
        }
        .invoice-number {
            font-size: 20px;
            font-weight: 900;
            color: #fff;
            letter-spacing: -0.5px;
        }
        .badge-lunas {
            display: inline-block;
            background: #f2994a;
            color: #000;
            font-size: 9px;
            font-weight: 900;
            letter-spacing: 2px;
            text-transform: uppercase;
            padding: 5px 14px;
            border-radius: 30px;
            margin-top: 10px;
        }
        .header-stats {
            display: flex;
            gap: 32px;
            flex-wrap: wrap;
            padding-top: 24px;
            border-top: 1px solid rgba(255,255,255,0.06);
        }
        .stat-item {}
        .stat-label {
            font-size: 9px;
            color: #555;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 4px;
        }
        .stat-value {
            font-size: 13px;
            font-weight: 700;
            color: #fff;
        }

        /* ── TWO COLUMN INFO ────────────────────────────── */
        .info-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 16px;
        }
        @media (max-width: 600px) {
            .info-row { grid-template-columns: 1fr; }
        }
        .info-card {
            background: #111;
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 16px;
            padding: 22px 24px;
        }
        .info-section-label {
            font-size: 9px;
            color: #555;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 12px;
        }
        .info-name {
            font-size: 15px;
            font-weight: 800;
            color: #fff;
            margin-bottom: 6px;
        }
        .info-detail {
            font-size: 11px;
            color: #666;
            line-height: 1.9;
        }

        /* ── VEHICLE CARD ───────────────────────────────── */
        .vehicle-card {
            background: #111;
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 16px;
            padding: 22px 24px;
            margin-bottom: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
        }
        .vehicle-img {
            width: 120px;
            height: 76px;
            border-radius: 12px;
            object-fit: cover;
            border: 1px solid rgba(255,255,255,0.06);
            flex-shrink: 0;
        }

        /* ── TABLE ──────────────────────────────────────── */
        .table-card {
            background: #111;
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 16px;
            overflow: hidden;
            margin-bottom: 16px;
        }
        .table-card table { width: 100%; border-collapse: collapse; }
        .table-card thead tr {
            background: #0a0a0a;
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }
        .table-card th {
            padding: 14px 20px;
            font-size: 9px;
            font-weight: 800;
            color: #f2994a;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        .table-card th:not(:first-child) { text-align: right; }
        .table-card td {
            padding: 18px 20px;
            font-size: 12px;
            vertical-align: top;
            border-bottom: 1px solid rgba(255,255,255,0.04);
        }
        .table-card tbody tr:last-child td { border-bottom: none; }
        .table-card td:not(:first-child) { text-align: right; }
        .td-name { font-weight: 700; color: #fff; font-size: 13px; }
        .td-note { font-size: 10px; color: #555; margin-top: 3px; }
        .td-price { color: #888; }
        .td-subtotal { font-weight: 800; color: #fff; }

        /* ── TOTAL SECTION ──────────────────────────────── */
        .totals-section {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 16px;
        }
        .totals-box {
            background: #111;
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 16px;
            padding: 24px;
            width: 320px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            padding: 8px 0;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        .total-row:last-of-type { border-bottom: none; }
        .total-row span:first-child { color: #666; }
        .total-row span:last-child { color: #ccc; font-weight: 600; }
        .total-grand {
            background: linear-gradient(135deg, #0f0f0f, #1a1a1a);
            border: 1px solid rgba(242,153,74,0.2);
            border-radius: 12px;
            padding: 16px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 14px;
        }
        .total-grand-label {
            font-size: 9px;
            font-weight: 800;
            color: #555;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        .total-grand-amount {
            font-size: 22px;
            font-weight: 900;
            color: #f2994a;
        }

        /* ── FOOTER ─────────────────────────────────────── */
        .invoice-footer {
            background: #0a0a0a;
            border: 1px solid rgba(255,255,255,0.05);
            border-radius: 16px;
            padding: 20px 28px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }
        .footer-printed { font-size: 10px; color: #444; }
        .footer-note { font-size: 9px; color: #333; text-align: center; }
        .footer-brand { font-size: 16px; font-weight: 900; color: #f2994a; text-align: right; }
        .footer-brand-sub { font-size: 9px; color: #444; letter-spacing: 2px; text-transform: uppercase; }

        /* ── PRINT STYLES ───────────────────────────────── */
        @media print {
            @page { size: A4; margin: 10mm 12mm; }
            body { background: #0a0a0a !important; color: #fff !important; print-color-adjust: exact !important; -webkit-print-color-adjust: exact !important; }
            .action-bar { display: none !important; }
            .invoice-wrapper { margin: 0 auto; padding: 0; max-width: 100%; }
        }
    </style>
</head>
<body>

{{-- Action Bar --}}
<div class="action-bar">
    <a href="{{ url()->previous() }}">
        <i class="ph-bold ph-arrow-left"></i> Kembali
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
                <div class="badge-lunas">✓ LUNAS</div>
            </div>
        </div>
        <div class="header-stats">
            <div class="stat-item">
                <div class="stat-label">Tanggal Bayar</div>
                <div class="stat-value">{{ $tglBayar }}</div>
            </div>
            <div class="stat-item">
                <div class="stat-label">Metode Pembayaran</div>
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
            <div class="info-section-label">Billed To</div>
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
            <div class="info-section-label">Spesifikasi Kendaraan</div>
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
                    <th style="text-align:left;">Layanan</th>
                    <th>Qty</th>
                    <th>Harga Satuan</th>
                    <th>Subtotal</th>
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
                <span>Subtotal Layanan</span>
                <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
            </div>
            <div class="total-row">
                <span>Biaya Administrasi</span>
                <span>Rp 0</span>
            </div>
            <div class="total-grand">
                <div class="total-grand-label">Total<br>Tagihan</div>
                <div class="total-grand-amount">Rp {{ number_format($totalFinal, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    {{-- FOOTER --}}
    <div class="invoice-footer">
        <div>
            <div class="footer-printed">Dicetak: {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB</div>
        </div>
        <div class="footer-note">
            Dokumen ini berlaku sah tanpa tanda tangan basah<br>
            Invoice #WAP-{{ $pesanan->kode_pesanan }}
        </div>
        <div>
            <div class="footer-brand">{{ $profil?->nama_perusahaan ?? 'WAPPING' }}</div>
            <div class="footer-brand-sub">Thank you for your trust</div>
        </div>
    </div>

</div>
</body>
</html>
