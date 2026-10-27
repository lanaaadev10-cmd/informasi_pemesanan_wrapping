{{-- ============================================
    BAGIAN: Filter Sidebar - Rentang Harga & Garansi Workshop
============================================ --}}
<!-- 2. FILTER RENTANG HARGA -->
<div class="space-y-2.5 pt-3.5 border-t border-slate-100">
    <span class="text-xs sm:text-[13px] font-montserrat font-bold text-slate-800 uppercase tracking-wider block">
        Rentang Harga:
    </span>

    <div class="space-y-1.5" id="filter-price-group">
        <button type="button"
                onclick="setPriceFilter('all')"
                data-filter-price="all"
                class="filter-price-btn w-full px-3 py-2.5 rounded-xl text-sm font-montserrat text-left transition-all
                       bg-orange-50 text-[#ff6b00] border border-orange-300 font-bold flex items-center justify-between">
            <span>Semua Harga</span>
            <i class="ph-bold ph-check text-sm"></i>
        </button>

        <button type="button"
                onclick="setPriceFilter('under-500k')"
                data-filter-price="under-500k"
                class="filter-price-btn w-full px-3 py-2.5 rounded-xl text-sm font-montserrat text-left transition-all
                       bg-white border border-slate-200/80 text-slate-600 hover:text-slate-900 hover:bg-slate-50 flex items-center justify-between">
            <span>&lt; Rp 500.000</span>
            <span class="text-xs text-slate-400 font-mono">Ekonomis</span>
        </button>

        <button type="button"
                onclick="setPriceFilter('500k-2m')"
                data-filter-price="500k-2m"
                class="filter-price-btn w-full px-3 py-2.5 rounded-xl text-sm font-montserrat text-left transition-all
                       bg-white border border-slate-200/80 text-slate-600 hover:text-slate-900 hover:bg-slate-50 flex items-center justify-between">
            <span>Rp 500rb - 2 Jt</span>
            <span class="text-xs text-slate-400 font-mono">Menengah</span>
        </button>

        <button type="button"
                onclick="setPriceFilter('above-2m')"
                data-filter-price="above-2m"
                class="filter-price-btn w-full px-3 py-2.5 rounded-xl text-sm font-montserrat text-left transition-all
                       bg-white border border-slate-200/80 text-slate-600 hover:text-slate-900 hover:bg-slate-50 flex items-center justify-between">
            <span>&gt; Rp 2.000.000</span>
            <span class="text-xs text-slate-400 font-mono">Premium</span>
        </button>
    </div>
</div>

<!-- 3. KONSULTASI & NEGOSIASI CUSTOM WA -->
@php
    $rawWaSidebar = !empty($profil->nomor_telepon) ? preg_replace('/[^0-9]/', '', $profil->nomor_telepon) : '6283117163605';
    if (str_starts_with($rawWaSidebar, '0')) {
        $rawWaSidebar = '62' . substr($rawWaSidebar, 1);
    }
@endphp
<div class="pt-3.5 border-t border-slate-100">
    <div class="p-3.5 rounded-2xl bg-orange-50/50 border border-orange-200/80 hover:border-[#ff6b00]/50 transition-all space-y-2.5 shadow-sm">
        <div class="flex items-center gap-2 text-xs font-montserrat font-bold text-slate-900">
            <i class="ph-bold ph-chats-circle text-[#ff6b00] text-base"></i>
            <span>Ingin Konsep Custom?</span>
        </div>
        <p class="text-[11px] font-questrial text-slate-600 leading-relaxed">
            Pengerjaan custom dibuat khusus sesuai konsep &amp; ukuran kendaraan Anda. Hubungi kami via WhatsApp untuk konsultasi desain dan estimasi biaya!
        </p>
        <a href="{{ \App\Helpers\WhatsAppHelper::getCustomUrl() }}"
           target="_blank" rel="noopener noreferrer"
           class="inline-flex items-center justify-center gap-2 w-full py-2.5 px-3 rounded-xl bg-[#ff6b00] hover:bg-[#ea580c] text-white text-xs font-montserrat font-bold uppercase tracking-wider shadow-sm transition-all active:scale-95 shadow-orange-500/20">
            <i class="ph-bold ph-whatsapp-logo text-base"></i>
            <span>Konsultasi via WhatsApp</span>
        </a>
    </div>
</div>

<!-- 4. JAMINAN WORKSHOP -->
<div class="pt-3.5 border-t border-slate-100">
    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80 space-y-2">
        <div class="flex items-center gap-2 text-xs sm:text-sm font-montserrat font-bold text-slate-800">
            <i class="ph-fill ph-seal-check text-[#ff6b00] text-base"></i>
            <span>Garansi Workshop</span>
        </div>
        <div class="text-xs sm:text-[13px] font-questrial text-slate-600 space-y-1">
            <div>✓ 100% Bebas Gelembung</div>
            <div>✓ Garansi Pemasangan Resmi</div>
        </div>
    </div>
</div>
