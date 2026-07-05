<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan {{ $title }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
        .title { font-size: 18px; font-weight: bold; text-transform: uppercase; margin: 0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { background: #f2f2f2; border: 1px solid #ddd; padding: 10px; text-align: left; }
        td { border: 1px solid #ddd; padding: 10px; vertical-align: top; }
        .text-right { text-align: right; }
        .footer { margin-top: 50px; display: flex; justify-content: space-between; }
        .summary-box { float: right; width: 300px; }
        .total-row { font-size: 14px; font-weight: bold; background: #eee; }
        @media print {
            .no-print { display: none; }
            body { padding: 20px; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px; text-align: right;">
        <button onclick="window.print()" style="padding: 8px 16px; background: #000; color: #fff; border: none; cursor: pointer; border-radius: 4px; font-weight: bold;">Cetak Laporan</button>
    </div>

    <div class="header">
        <h1 class="title">Laporan Penjualan {{ $company->nama_perusahaan ?? 'Perusahaan' }}</h1>
        <p>Periode: {{ $title }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="50">No</th>
                <th width="120">Tgl Pesan</th>
                <th width="120">Kode Pesanan</th>
                <th>Pelanggan</th>
                <th>Detail Layanan</th>
                <th width="150" class="text-right">Total Bayar</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pesanans as $index => $pesanan)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $pesanan->created_at->format('d/m/Y H:i') }}</td>
                <td><strong>{{ $pesanan->kode_pesanan }}</strong></td>
                <td>{{ $pesanan->user->name }}</td>
                <td>
                    <ul style="margin: 0; padding-left: 15px;">
                        @foreach($pesanan->details as $detail)
                            <li>{{ $detail->layanan->nama_layanan }} ({{ $detail->jumlah }}x)</li>
                        @endforeach
                    </ul>
                </td>
                <td class="text-right">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; padding: 20px;">Tidak ada data transaksi pada periode ini.</td>
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

    <div style="margin-top: 40px;">
        <p>Dicetak pada: {{ date('d/m/Y H:i:s') }}</p>
        <div style="float: right; text-align: center; width: 200px; margin-top: 20px;">
            <p>Admin {{ $company->nama_perusahaan ?? 'Perusahaan' }}</p>
            <br><br><br>
            <p>( ____________________ )</p>
        </div>
    </div>
</body>
</html>
