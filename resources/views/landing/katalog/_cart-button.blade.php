{{-- ============================================
    BAGIAN: Floating Cart Action Button
    Deskripsi: Tombol melayang keranjang belanja (hanya muncul saat login dan ada item)
============================================ --}}
@auth
    @php
        $cartCount = \App\Models\Keranjang::where('id_user', auth()->id())
            ->where('status', 'active')
            ->first()?->details?->count() ?? 0;
    @endphp
    @if($cartCount > 0)
        <a href="{{ route('keranjang.index') }}" 
           class="fixed bottom-8 right-8 z-[9999] w-14 h-14 bg-[#f2994a] hover:bg-[#e28a44] text-black rounded-full hidden md:flex items-center justify-center shadow-[0_6px_20px_rgba(242,153,74,0.4)] transition-all hover:scale-110 active:scale-95 group">
            <span class="absolute -top-1 -right-1 bg-red-600 text-white text-[9px] font-black w-5 h-5 rounded-full flex items-center justify-center border border-black shadow">
                {{ $cartCount }}
            </span>
            <i class="ph-bold ph-shopping-cart text-xl"></i>
        </a>
    @endif
@endauth
