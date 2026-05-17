<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $pesanan->kode_pesanan }}</title>
    <style>
        body { font-family: 'Helvetica Neue', 'Helvetica', sans-serif; color: #1c1917; line-height: 1.6; background-color: #ffffff; }
        .invoice-box { max-width: 800px; margin: auto; padding: 40px; border: 1px solid #e7e5e4; border-radius: 20px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #f2541b; padding-bottom: 20px; margin-bottom: 20px; }
        .logo { font-size: 28px; font-weight: 900; color: #151413; }
        .logo span { color: #f2541b; }
        .details { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-bottom: 40px; }
        .details h4 { margin-bottom: 8px; text-transform: uppercase; font-size: 10px; tracking-widest: 0.1em; color: #78716c; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 45px; }
        th { text-align: left; background: #fafaf9; padding: 14px; font-size: 11px; text-transform: uppercase; color: #57534e; font-weight: 700; border-bottom: 1px solid #e7e5e4; }
        td { padding: 14px; border-bottom: 1px solid #f5f5f4; font-size: 13px; color: #292524; }
        .total-box { text-align: right; border-top: 1px solid #e7e5e4; padding-top: 15px; }
        .total-row { font-size: 22px; font-weight: 900; color: #151413; margin: 0; }
        .status-badge { display: inline-block; padding: 4px 12px; border-radius: 12px; font-size: 9px; font-weight: bold; text-transform: uppercase; background: #f2541b; color: #ffffff; }
        @media print {
            .no-print { display: none; }
            body { padding: 0; background-color: #ffffff; }
            .invoice-box { border: none; box-shadow: none; padding: 0; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: center; margin: 30px 0;">
        <button onclick="window.print()" style="padding: 10px 24px; background: #f2541b; color: white; border: none; border-radius: 12px; cursor: pointer; font-weight: bold; font-size: 13px; transition: all 0.2s;">Cetak / Download PDF</button>
        <button onclick="window.history.back()" style="padding: 10px 24px; background: #f5f5f4; color: #44403c; border: 1px solid #e7e5e4; border-radius: 12px; cursor: pointer; font-weight: bold; font-size: 13px; margin-left: 12px; transition: all 0.2s;">Kembali</button>
    </div>

    <div class="invoice-box">
        <div class="header">
            <div class="logo">dantiestiker<span>.</span></div>
            <div style="text-align: right">
                <h2 style="margin: 0; font-size: 20px; font-weight: 800; tracking-tight: -0.025em;">INVOICE</h2>
                <p style="margin: 3px 0 0 0; color: #78716c; font-size: 13px; font-family: monospace;">#{{ $pesanan->kode_pesanan }}</p>
            </div>
        </div>

        <div class="details">
            <div>
                <h4>Pelanggan</h4>
                <p style="margin: 0; font-size: 14px;"><strong>{{ $pesanan->user->name }}</strong><br>
                <span style="color: #57534e;">{{ $pesanan->user->email }}<br>
                {{ $pesanan->form->no_hp }}</span></p>
            </div>
            <div style="text-align: right">
                <h4>Tanggal</h4>
                <p style="margin: 0; font-size: 14px; color: #57534e;">{{ date('d F Y', strtotime($pesanan->created_at)) }}<br>
                <span style="display: inline-block; margin-top: 6px;" class="status-badge">{{ str_replace('_', ' ', $pesanan->status) }}</span></p>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Deskripsi Layanan</th>
                    <th style="text-align: center;">Qty</th>
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
                    <td style="text-align: center;">{{ $item->jumlah }}</td>
                    <td style="text-align: right">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                    <td style="text-align: right; font-weight: bold;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total-box">
            <p style="color: #78716c; margin: 0 0 4px 0; font-size: 11px; text-transform: uppercase; font-weight: bold; tracking-widest: 0.05em;">Total Tagihan</p>
            <p class="total-row">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</p>
        </div>

        <div style="margin-top: 60px; border-top: 1px solid #e7e5e4; padding-top: 20px; font-size: 11px; color: #78716c; text-align: center; font-style: italic;">
            <p>Terima kasih atas kepercayaan Anda menggunakan layanan dantiestiker. Segala bentuk komplain dapat disampaikan maksimal 2x24 jam setelah pengerjaan selesai.</p>
        </div>
    </div>
</body>
</html>

