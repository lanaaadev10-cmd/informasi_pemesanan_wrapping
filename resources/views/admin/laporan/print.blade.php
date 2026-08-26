<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan - {{ $title }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 20px;
            font-size: 13px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 22px;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0 0;
            color: #666;
        }
        .meta-info {
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px 10px;
            text-align: left;
        }
        table th {
            background-color: #f5f5f5;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 11px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .total-row {
            background-color: #f9f9f9;
            font-weight: bold;
            font-size: 14px;
        }
        .footer-sign {
            margin-top: 40px;
            float: right;
            text-align: center;
            width: 200px;
        }
        .footer-sign .space {
            height: 60px;
        }
        @media print {
            body { padding: 0; }
            @page { margin: 1.5cm; }
        }
        @media print {
        @page {
        margin: 1cm;
        size: auto;
    }
    /* Sembunyikan header & footer bawaan browser secara otomatis */
    body {
        margin: 0;
    }
}
    </style>
</head>
<body>

    <!-- Header / Kop Laporan -->
    <div class="header">
        <h1>{{ $company->name ?? 'DANTIE WRAPPING' }}</h1>
        <p>Laporan Penjualan Transaksi {{ $title }}</p>
    </div>

    <!-- Meta Informasi -->
    <div class="meta-info">
        <div><strong>Tipe Laporan:</strong> {{ strtoupper($type) }}</div>
        <div><strong>Tanggal Cetak:</strong> {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }}</div>
    </div>

    <!-- Tabel Data Pesanan -->
    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 5%;">No</th>
                <th>Kode Pesanan</th>
                <th>Nama Pelanggan</th>
                <th>Tanggal Transaksi</th>
                <th class="text-center">Status</th>
                <th class="text-right">Total Harga</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pesanans as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td><strong>{{ $item->kode_pesanan }}</strong></td>
                    <td>{{ $item->customer_name ?? ($item->user->name ?? 'Pelanggan Umum') }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d/m/Y H:i') }}</td>
                    <td class="text-center">
                        <span style="text-transform: capitalize;">{{ $item->status }}</span>
                    </td>
                    <td class="text-right">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 20px; color: #888;">
                        Tidak ada data transaksi penjualan pada periode ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="5" class="text-right">TOTAL PENDAPATAN</td>
                <td class="text-right">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <!-- Tanda Tangan Admin / Penanggung Jawab -->
    <div class="footer-sign">
        <p>Admin Operasional,</p>
        <div class="space"></div>
        <p><strong>({{ auth()->user()->name ?? 'Administrator' }})</strong></p>
    </div>

    <!-- Auto Print Script -->
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>