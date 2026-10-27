<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sertifikat Garansi - {{ $warranty->warranty_code }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            color: #1e293b;
            background: #ffffff;
            padding: 24px;
        }

        .certificate-border {
            border: 6px double #ea580c;
            padding: 28px 36px;
            background: #fffdfa;
            position: relative;
            min-height: 500px;
        }

        .watermark {
            position: absolute;
            top: 35%;
            left: 20%;
            font-size: 80px;
            font-weight: bold;
            color: rgba(234, 88, 12, 0.04);
            transform: rotate(-25deg);
            z-index: 0;
            white-space: nowrap;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #fdba74;
            padding-bottom: 14px;
            margin-bottom: 18px;
            position: relative;
            z-index: 1;
        }

        .company-name {
            font-size: 22px;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .company-sub {
            font-size: 10px;
            color: #64748b;
            margin-top: 2px;
        }

        .cert-title {
            text-align: center;
            margin-bottom: 16px;
            position: relative;
            z-index: 1;
        }

        .cert-title h1 {
            font-size: 18px;
            font-weight: bold;
            color: #ea580c;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 4px;
        }

        .cert-code {
            display: inline-block;
            background: #ffedd5;
            color: #9a3412;
            padding: 3px 12px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 12px;
            border: 1px solid #fed7aa;
        }

        .content-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
            position: relative;
            z-index: 1;
        }

        .content-table td {
            vertical-align: top;
            padding: 6px 10px;
        }

        .card-box {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px 14px;
        }

        .card-title {
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            color: #ea580c;
            border-bottom: 1px dashed #cbd5e1;
            padding-bottom: 4px;
            margin-bottom: 8px;
        }

        .data-row {
            margin-bottom: 5px;
            font-size: 10.5px;
        }

        .data-label {
            color: #64748b;
            width: 110px;
            display: inline-block;
        }

        .data-val {
            font-weight: bold;
            color: #0f172a;
        }

        .terms-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 10px 14px;
            font-size: 9px;
            color: #475569;
            line-height: 1.4;
            margin-bottom: 18px;
            position: relative;
            z-index: 1;
        }

        .footer-table {
            width: 100%;
            margin-top: 10px;
            position: relative;
            z-index: 1;
        }

        .footer-table td {
            vertical-align: bottom;
            text-align: center;
            font-size: 10px;
        }

        .seal-box {
            border: 2px dashed #ea580c;
            padding: 6px 14px;
            display: inline-block;
            border-radius: 6px;
            color: #c2410c;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="certificate-border">
        <div class="watermark">OFFICIAL WARRANTY</div>

        {{-- Header --}}
        <div class="header">
            <div class="company-name">{{ $profil->nama_perusahaan ?? 'Dantie Auto Wrapping' }}</div>
            <div class="company-sub">
                {{ $profil->alamat ?? 'Workshop Pusat Pemasangan Wrapping Mobil & Motor Premium' }} | WhatsApp: {{ $profil->no_wa ?? '0812-3456-7890' }}
            </div>
        </div>

        {{-- Title --}}
        <div class="cert-title">
            <h1>SERTIFIKAT GARANSI DIGITAL (E-WARRANTY)</h1>
            <div class="cert-code">KODE GARANSI: {{ $warranty->warranty_code }}</div>
        </div>

        {{-- Content Table (2 Kolom) --}}
        <table class="content-table">
            <tr>
                <td style="width: 50%;">
                    <div class="card-box">
                        <div class="card-title">Informasi Kendaraan & Pelanggan</div>
                        <div class="data-row">
                            <span class="data-label">Nama Pemilik:</span>
                            <span class="data-val">{{ $booking->customer_name ?? ($booking->user?->name ?? 'Pelanggan') }}</span>
                        </div>
                        <div class="data-row">
                            <span class="data-label">Nomor WhatsApp:</span>
                            <span class="data-val">{{ $booking->customer_phone ?? ($booking->user?->phone ?? '-') }}</span>
                        </div>
                        <div class="data-row">
                            <span class="data-label">Kendaraan / Tipe:</span>
                            <span class="data-val">{{ $booking->vehicle_name ?? 'Mobil' }} ({{ strtoupper($booking->vehicle_size ?? 'Medium') }})</span>
                        </div>
                        <div class="data-row">
                            <span class="data-label">Plat Nomor Polisi:</span>
                            <span class="data-val">{{ $booking->vehicle_license ?: '-' }}</span>
                        </div>
                        <div class="data-row">
                            <span class="data-label">Warna Kendaraan:</span>
                            <span class="data-val">{{ $booking->vehicle_color ?: '-' }}</span>
                        </div>
                    </div>
                </td>
                <td style="width: 50%;">
                    <div class="card-box">
                        <div class="card-title">Cakupan Garansi & Masa Berlaku</div>
                        <div class="data-row">
                            <span class="data-label">Paket Layanan:</span>
                            <span class="data-val">{{ $booking->service_name_snapshot ?? ($booking->layanan?->nama_layanan ?? 'Wrapping Treatment') }}</span>
                        </div>
                        <div class="data-row">
                            <span class="data-label">Jenis Garansi:</span>
                            <span class="data-val">{{ $warranty->warranty_type }}</span>
                        </div>
                        <div class="data-row">
                            <span class="data-label">Durasi Garansi:</span>
                            <span class="data-val">{{ $warranty->duration_months }} Bulan (1 Tahun Penuh)</span>
                        </div>
                        <div class="data-row">
                            <span class="data-label">Tanggal Mulai:</span>
                            <span class="data-val">{{ $warranty->start_date?->translatedFormat('d F Y') }}</span>
                        </div>
                        <div class="data-row">
                            <span class="data-label">Berlaku Sampai:</span>
                            <span class="data-val" style="color: #ea580c;">{{ $warranty->end_date?->translatedFormat('d F Y') }}</span>
                        </div>
                    </div>
                </td>
            </tr>
        </table>

        {{-- Terms & Conditions --}}
        <div class="terms-box">
            <strong style="color: #0f172a; display: block; margin-bottom: 4px;">Ketentuan & Syarat Klaim Garansi:</strong>
            {!! nl2br(e($warranty->terms)) !!}
        </div>

        {{-- Signatures & QR Placeholder --}}
        <table class="footer-table">
            <tr>
                <td style="width: 35%;">
                    <div class="seal-box">
                        VERIFIED BY SYSTEM<br>
                        <span style="font-size: 8px; font-weight: normal; color: #475569;">Ref: {{ $booking->booking_code }}</span>
                    </div>
                </td>
                <td style="width: 30%;">
                    <div style="font-size: 8px; color: #94a3b8;">
                        Scan QR & Tunjukkan e-Warranty saat perbaikan di workshop
                    </div>
                </td>
                <td style="width: 35%;">
                    <div style="color: #64748b; font-size: 9px; margin-bottom: 35px;">
                        Authorized Workshop Manager
                    </div>
                    <div style="font-weight: bold; color: #0f172a; text-decoration: underline;">
                        {{ $profil->nama_perusahaan ?? 'Dantie Auto Wrapping' }}
                    </div>
                    <div style="font-size: 8px; color: #64748b;">Quality Assurance Certified</div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
