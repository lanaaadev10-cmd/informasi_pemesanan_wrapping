<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $pesanan->kode_pesanan }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; line-height: 1.6; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #ea580c; padding-bottom: 20px; margin-bottom: 20px; }
        .logo { font-size: 28px; font-weight: 900; color: #ea580c; font-style: italic; }
        .details { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-bottom: 40px; }
        .details h4 { margin-bottom: 10px; text-transform: uppercase; font-size: 12px; color: #999; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 40px; }
        th { text-align: left; background: #f9fafb; padding: 12px; font-size: 12px; text-transform: uppercase; color: #666; }
        td { padding: 12px; border-bottom: 1px solid #eee; }
        .total-box { text-align: right; }
        .total-row { font-size: 18px; font-weight: 900; }
        .status-badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 10px; font-weight: bold; text-transform: uppercase; background: #ecfdf5; color: #065f46; }
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
            .invoice-box { border: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #ea580c; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: bold;">Cetak / Download PDF</button>
        <button onclick="window.history.back()" style="padding: 10px 20px; background: #f3f4f6; color: #333; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; margin-left: 10px;">Kembali</button>
    </div>

    <div class="invoice-box">
        <div class="header">
            <div class="logo">Dantie Stiker.</div>
            <div style="text-align: right">
                <h2 style="margin: 0; font-size: 20px;">INVOICE</h2>
                <p style="margin: 0; color: #666; font-size: 14px;">{{ $pesanan->kode_pesanan }}</p>
            </div>
        </div>

        <div class="details">
            <div>
                <h4>Pelanggan</h4>
                <p><strong>{{ $pesanan->user->name }}</strong><br>
                {{ $pesanan->user->email }}<br>
                {{ $pesanan->form->no_hp }}</p>
            </div>
            <div style="text-align: right">
                <h4>Tanggal</h4>
                <p>{{ date('d F Y', strtotime($pesanan->created_at)) }}<br>
                Status: <span class="status-badge">{{ str_replace('_', ' ', $pesanan->status) }}</span></p>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Deskripsi Layanan</th>
                    <th>Qty</th>
                    <th style="text-align: right">Harga</th>
                    <th style="text-align: right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pesanan->details as $item)
                <tr>
                    <td>
                        <strong>{{ $item->layanan->nama_layanan }}</strong>
                    </td>
                    <td>{{ $item->jumlah }}</td>
                    <td style="text-align: right">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                    <td style="text-align: right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total-box">
            <p style="color: #666; margin-bottom: 5px;">Total Tagihan</p>
            <p class="total-row">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</p>
        </div>

        <div style="margin-top: 60px; border-top: 1px solid #eee; pt: 20px; font-size: 12px; color: #999;">
            <p>Terima kasih atas kepercayaan Anda menggunakan layanan Dantie Stiker. Segala bentuk komplain dapat disampaikan maksimal 2x24 jam setelah pengerjaan selesai.</p>
        </div>
    </div>
</body>
</html>
