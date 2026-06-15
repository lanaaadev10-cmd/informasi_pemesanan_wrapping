<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan {{ $title }}</title>
    @vite(['resources/css/app.css'])
    <style>
        @media print {
            @page { size: A4 landscape; margin: 10mm; }
            body { print-color-adjust: exact !important; -webkit-print-color-adjust: exact !important; }
        }
    </style>
</head>
<body class="font-sans text-[12px] text-[#333] p-5">
    <div class="no-print flex items-center justify-between mb-5">
        <form method="GET" class="flex items-center gap-3">
            <input type="hidden" name="type" value="{{ request('type', 'hari') }}">
            <select name="source" class="px-4 py-2 bg-white border border-gray-200 rounded-lg text-xs font-bold">
                <option value="semua" {{ $source === 'semua' ? 'selected' : '' }}>Semua Sumber</option>
                <option value="online" {{ $source === 'online' ? 'selected' : '' }}>Online</option>
                <option value="offline" {{ $source === 'offline' ? 'selected' : '' }}>Offline</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-black text-white rounded-lg text-xs font-bold border-none cursor-pointer">Filter</button>
        </form>
        <button onclick="window.print()" class="px-4 py-2 bg-black text-white border-none rounded cursor-pointer font-bold text-xs">Cetak Laporan</button>
    </div>

    <div class="text-center border-b-2 border-[#333] pb-2.5 mb-5">
        <h1 class="text-lg font-bold uppercase m-0">Laporan Penjualan {{ $company->nama_perusahaan ?? 'Perusahaan' }}</h1>
        <p>Periode: {{ $title }}</p>
    </div>

    <table class="w-full border-collapse mb-5">
        <thead>
            <tr>
                <th class="bg-gray-100 border border-[#ddd] p-2.5 text-left text-xs font-bold w-[50px]">No</th>
                <th class="bg-gray-100 border border-[#ddd] p-2.5 text-left text-xs font-bold w-[120px]">Tgl Pesan</th>
                <th class="bg-gray-100 border border-[#ddd] p-2.5 text-left text-xs font-bold w-[120px]">Kode Pesanan</th>
                <th class="bg-gray-100 border border-[#ddd] p-2.5 text-left text-xs font-bold">Pelanggan</th>
                <th class="bg-gray-100 border border-[#ddd] p-2.5 text-left text-xs font-bold w-[80px]">Sumber</th>
                <th class="bg-gray-100 border border-[#ddd] p-2.5 text-left text-xs font-bold">Detail Layanan</th>
                <th class="bg-gray-100 border border-[#ddd] p-2.5 text-right text-xs font-bold w-[150px]">Total Bayar</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pesanans as $index => $pesanan)
            <tr>
                <td class="border border-[#ddd] p-2.5 align-top">{{ $index + 1 }}</td>
                <td class="border border-[#ddd] p-2.5 align-top">{{ $pesanan->created_at->format('d/m/Y H:i') }}</td>
                <td class="border border-[#ddd] p-2.5 align-top font-bold">{{ $pesanan->kode_pesanan }}</td>
                <td class="border border-[#ddd] p-2.5 align-top">{{ $pesanan->customer_name ?? $pesanan->user->name }}</td>
                <td class="border border-[#ddd] p-2.5 align-top">
                    @if($pesanan->order_source === 'offline')
                        <span class="text-yellow-600 font-bold text-[10px]">OFFLINE</span>
                    @else
                        <span class="text-blue-600 font-bold text-[10px]">ONLINE</span>
                    @endif
                </td>
                <td class="border border-[#ddd] p-2.5 align-top">
                    <ul class="m-0 pl-3.5">
                        @foreach($pesanan->details as $detail)
                            <li>{{ $detail->layanan->nama_layanan }} ({{ $detail->jumlah }}x)</li>
                        @endforeach
                    </ul>
                </td>
                <td class="border border-[#ddd] p-2.5 text-right align-top">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="border border-[#ddd] p-5 text-center align-top">Tidak ada data transaksi pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="font-bold text-sm bg-gray-200">
                <td colspan="6" class="border border-[#ddd] p-2.5 text-right">TOTAL PENDAPATAN</td>
                <td class="border border-[#ddd] p-2.5 text-right">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="mt-10">
        <p class="text-xs">Dicetak pada: {{ date('d/m/Y H:i:s') }}</p>
        <div class="float-right text-center w-[200px] mt-5">
            <p class="text-xs">Admin {{ $company->nama_perusahaan ?? 'Perusahaan' }}</p>
            <br><br><br>
            <p class="text-xs">( ____________________ )</p>
        </div>
    </div>
</body>
</html>
