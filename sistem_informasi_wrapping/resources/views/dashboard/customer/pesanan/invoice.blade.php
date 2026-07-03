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
    @vite(['resources/css/app.css'])

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

    $thumbnail = $pesanan->details->first()?->layanan?->foto_contoh;
    $imageUrl  = $thumbnail ? asset('storage/' . $thumbnail) : null;
@endphp

    <style>
        @media print {
            @page { size: A4; margin: 10mm 12mm; }
        }
    </style>
</head>
<body class="bg-[#0a0a0a] text-white min-h-screen" style="font-family:'Inter',Arial,sans-serif;print-color-adjust:exact;-webkit-print-color-adjust:exact">

    {{-- Action Bar (hidden on print) --}}
    <div class="print:hidden fixed top-0 left-0 right-0 z-[999] bg-[#0a0a0a]/95 backdrop-blur-[12px] border-b border-white/[0.06] px-6 py-3.5 flex items-center justify-between gap-3">
        <a href="{{ url()->previous() }}" class="text-[#888] hover:text-white text-xs flex items-center gap-1.5 transition-colors no-underline">
            <i class="ph-bold ph-arrow-left"></i>
            <span>{{ $profil->cta_kembali ?? 'Kembali' }}</span>
        </a>
        <div class="text-center flex-1">
            <span class="text-[11px] text-[#555]">Invoice #WAP-{{ $pesanan->kode_pesanan }}</span>
        </div>
        <button onclick="window.print()"
                class="bg-[#f2994a] hover:bg-[#e28a44] active:scale-[0.97] text-black font-extrabold text-xs uppercase tracking-wider px-5 py-2.5 rounded-xl cursor-pointer flex items-center gap-2 transition-all shadow-[0_4px_20px_rgba(242,153,74,0.35)] border-none font-['Inter',sans-serif]">
            <i class="ph-bold ph-printer"></i>
            <span>Download / Print</span>
        </button>
    </div>

    {{-- Invoice Content --}}
    <div class="max-w-[860px] mx-auto mt-20 mb-16 px-4 print:mt-0 print:mb-0 print:max-w-full print:px-0">

        {{-- HEADER --}}
        <div class="relative overflow-hidden bg-gradient-to-br from-[#0f0f0f] to-[#1a1a1a] rounded-[20px] p-10 mb-4 border border-[#f2994a]/15">
            <div class="absolute -top-20 -right-20 w-[300px] h-[300px] bg-[rgba(242,153,74,0.06)] rounded-full pointer-events-none"></div>

            <div class="flex justify-between items-start flex-wrap gap-5 mb-8 relative z-10">
                <div>
                    <div class="text-[28px] font-black text-[#f2994a] tracking-tight leading-none">{{ $profil?->nama_perusahaan ?? 'DANTIE WRAPPING' }}</div>
                    <div class="text-[10px] text-[#555] tracking-[2.5px] uppercase mt-1">Premium Vehicle Wrapping</div>
                    @if($profil)
                    <div class="text-[10px] text-[#555] mt-2.5 leading-relaxed">
                        {{ $profil->alamat ?? '' }}<br>
                        {{ $profil->nomor_telepon ?? '' }} &nbsp;·&nbsp; {{ $profil->email ?? '' }}
                    </div>
                    @endif
                </div>
                <div class="text-right">
                    <div class="text-[9px] text-[#555] tracking-[2px] uppercase mb-1.5">Invoice</div>
                    <div class="text-xl font-black text-white tracking-tight">#WAP-{{ $pesanan->kode_pesanan }}</div>
                    <div class="inline-block bg-[#f2994a] text-black text-[9px] font-black tracking-wider uppercase px-3.5 py-1 rounded-full mt-2.5">✓ {{ $profil->status_lunas ?? 'LUNAS' }}</div>
                </div>
            </div>

            <div class="flex gap-8 flex-wrap pt-6 border-t border-white/[0.06] relative z-10">
                <div>
                    <div class="text-[9px] text-[#555] tracking-[1.5px] uppercase mb-1">Tanggal Bayar</div>
                    <div class="text-[13px] font-bold text-white">{{ $tglBayar }}</div>
                </div>
                <div>
                    <div class="text-[9px] text-[#555] tracking-[1.5px] uppercase mb-1">{{ $profil->label_metode_pembayaran ?? 'Metode Pembayaran' }}</div>
                    <div class="text-[13px] font-bold text-white">{{ $metodeLabel }}</div>
                </div>
                <div>
                    <div class="text-[9px] text-[#555] tracking-[1.5px] uppercase mb-1">Jadwal Pengerjaan</div>
                    <div class="text-[13px] font-bold text-white">
                        {{ $pesanan->form?->jadwal_pengerjaan
                            ? \Carbon\Carbon::parse($pesanan->form->jadwal_pengerjaan)->translatedFormat('d F Y')
                            : '-' }}
                    </div>
                </div>
                <div>
                    <div class="text-[9px] text-[#555] tracking-[1.5px] uppercase mb-1">No. Order</div>
                    <div class="text-[13px] font-bold text-white">{{ $pesanan->kode_pesanan }}</div>
                </div>
            </div>
        </div>

        {{-- BILLED TO + VEHICLE INFO --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
            <div class="bg-[#111] border border-white/[0.06] rounded-2xl px-6 py-5">
                <div class="text-[9px] text-[#555] tracking-wider uppercase mb-3">{{ $profil->invoice_billed_to ?? 'Billed To' }}</div>
                <div class="text-[15px] font-extrabold text-white mb-1.5">{{ $pesanan->form?->nama_pemesan ?? $pesanan->user->name }}</div>
                <div class="text-[11px] text-[#666] leading-relaxed">
                    {{ $pesanan->user->email }}<br>
                    {{ $pesanan->form?->no_hp ?? '-' }}<br>
                    @if($pesanan->form?->alamat_pengiriman)
                    {{ $pesanan->form->alamat_pengiriman }}
                    @endif
                </div>
            </div>
            <div class="bg-[#111] border border-white/[0.06] rounded-2xl px-6 py-5">
                <div class="text-[9px] text-[#555] tracking-wider uppercase mb-3">{{ $profil->invoice_spesifikasi ?? 'Spesifikasi Kendaraan' }}</div>
                <div class="text-[15px] font-extrabold text-white mb-1.5">{{ $pesanan->form?->model_kendaraan ?? '-' }}</div>
                <div class="text-[11px] text-[#666] leading-relaxed">
                    Warna: {{ $pesanan->form?->warna_kendaraan ?? '-' }}<br>
                    No. Polisi: {{ $pesanan->form?->nomor_polisi ?? '-' }}<br>
                    Lokasi: {{ ucfirst($pesanan->form?->lokasi_pengerjaan ?? '-') }}
                </div>
            </div>
        </div>

        {{-- LINE ITEMS TABLE --}}
        <div class="bg-[#111] border border-white/[0.06] rounded-2xl overflow-hidden mb-4">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-[#0a0a0a] border-b border-white/[0.06]">
                        <th class="px-5 py-3.5 text-[9px] font-extrabold text-[#f2994a] tracking-wider uppercase text-left">{{ $profil->section_pilih_layanan ?? 'Layanan' }}</th>
                        <th class="px-5 py-3.5 text-[9px] font-extrabold text-[#f2994a] tracking-wider uppercase text-right">Qty</th>
                        <th class="px-5 py-3.5 text-[9px] font-extrabold text-[#f2994a] tracking-wider uppercase text-right">Harga Satuan</th>
                        <th class="px-5 py-3.5 text-[9px] font-extrabold text-[#f2994a] tracking-wider uppercase text-right">{{ $profil->label_subtotal ?? 'Subtotal' }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pesanan->details as $item)
                    <tr class="border-b border-white/[0.04] last:border-b-0">
                        <td class="px-5 py-[18px] text-left align-top">
                            <div class="text-[13px] font-bold text-white">{{ $item->layanan?->nama_layanan ?? $item->layanan?->nama_paket ?? '-' }}</div>
                            @if($item->catatan_custom ?? null)
                            <div class="text-[10px] text-[#555] mt-0.5">{{ $item->catatan_custom }}</div>
                            @endif
                        </td>
                        <td class="px-5 py-[18px] text-right align-top text-[#888] font-semibold">{{ $item->jumlah }}x</td>
                        <td class="px-5 py-[18px] text-right align-top text-[#888]">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                        <td class="px-5 py-[18px] text-right align-top font-extrabold text-white">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- TOTALS --}}
        <div class="flex justify-end mb-4">
            <div class="bg-[#111] border border-white/[0.06] rounded-2xl p-6 w-[320px] max-w-full">
                <div class="flex justify-between text-xs py-2 border-b border-white/[0.05]">
                    <span class="text-[#666]">{{ $profil->label_subtotal ?? 'Subtotal Layanan' }}</span>
                    <span class="text-[#ccc] font-semibold">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-xs py-2 border-b border-white/[0.05]">
                    <span class="text-[#666]">{{ $profil->label_biaya_admin ?? 'Biaya Administrasi' }}</span>
                    <span class="text-[#ccc] font-semibold">Rp 0</span>
                </div>
                <div class="bg-gradient-to-br from-[#0f0f0f] to-[#1a1a1a] border border-[#f2994a]/20 rounded-xl px-5 py-4 flex justify-between items-center mt-3.5">
                    <span class="text-[9px] font-extrabold text-[#555] tracking-wider uppercase">{{ $profil->label_total_tagihan ?? 'Total Tagihan' }}</span>
                    <span class="text-[22px] font-black text-[#f2994a]">Rp {{ number_format($totalFinal, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- FOOTER --}}
        <div class="bg-[#0a0a0a] border border-white/[0.05] rounded-2xl px-7 py-5 flex justify-between items-center flex-wrap gap-3">
            <div>
                <div class="text-[10px] text-[#444]">{{ $profil->label_dicetak ?? 'Dicetak' }}: {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB</div>
            </div>
            <div class="text-[9px] text-[#333] text-center">
                {{ $profil->invoice_legal ?? 'Dokumen ini berlaku sah tanpa tanda tangan basah' }}<br>
                Invoice #WAP-{{ $pesanan->kode_pesanan }}
            </div>
            <div class="text-right">
                <div class="text-base font-black text-[#f2994a]">{{ $profil?->nama_perusahaan ?? 'WAPPING' }}</div>
                <div class="text-[9px] text-[#444] tracking-wider uppercase">{{ $profil->invoice_thankyou ?? 'Thank you for your trust' }}</div>
            </div>
        </div>

    </div>

</body>
</html>
