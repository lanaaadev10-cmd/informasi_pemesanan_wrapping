{{-- BOTTOM NAVIGATION — Fitur asli sistem --}}
<nav id="bottom-nav" class="lg:hidden">
    <div class="flex items-center justify-around h-16 px-2 bg-black/95 backdrop-blur-xl border-t border-white/10">

        {{-- 1. Beranda --}}
        <a href="{{ route('dashboard') }}"
           class="flex flex-col items-center justify-center gap-1 flex-1 py-1 transition-all {{ Request::routeIs('dashboard') ? 'text-racing-orange' : 'text-gray-400 hover:text-white' }}">
            <i class="ph-bold ph-house text-2xl"></i>
            <span class="text-[10px] font-montserrat font-semibold tracking-wide">Beranda</span>
        </a>

        {{-- 2. Katalog Layanan --}}
        <a href="{{ route('katalog.user') }}"
           class="flex flex-col items-center justify-center gap-1 flex-1 py-1 transition-all {{ Request::routeIs('katalog.*') ? 'text-racing-orange' : 'text-gray-400 hover:text-white' }}">
            <i class="ph-bold ph-storefront text-2xl"></i>
            <span class="text-[10px] font-montserrat font-semibold tracking-wide">Katalog</span>
        </a>

        {{-- 3. Booking --}}
        <a href="{{ route('booking.index') }}"
           class="flex flex-col items-center justify-center gap-1 flex-1 py-1 transition-all {{ Request::routeIs('booking.*') ? 'text-racing-orange' : 'text-gray-400 hover:text-white' }}">
            <i class="ph-bold ph-calendar-check text-2xl"></i>
            <span class="text-[10px] font-montserrat font-semibold tracking-wide">Booking</span>
        </a>

        {{-- 4. Keranjang Belanja --}}
        <a href="{{ route('keranjang.index') }}"
           class="flex flex-col items-center justify-center gap-1 flex-1 py-1 transition-all {{ Request::routeIs('keranjang.*') ? 'text-racing-orange' : 'text-gray-400 hover:text-white' }} relative">
            <div class="relative">
                <i class="ph-bold ph-shopping-cart text-2xl"></i>
                @if(isset($cartCount) && $cartCount > 0)
                <span class="absolute -top-1.5 -right-2 min-w-[17px] h-[17px] px-1 bg-racing-orange text-white text-[9px] font-montserrat font-bold rounded-full flex items-center justify-center shadow-lg">
                    {{ $cartCount > 9 ? '9+' : $cartCount }}
                </span>
                @endif
            </div>
            <span class="text-[10px] font-montserrat font-semibold tracking-wide">Keranjang</span>
        </a>

        {{-- 5. Profil Saya --}}
        <a href="{{ route('profile.edit') }}"
           class="flex flex-col items-center justify-center gap-1 flex-1 py-1 transition-all {{ Request::routeIs('profile.*') ? 'text-racing-orange' : 'text-gray-400 hover:text-white' }}">
            <i class="ph-bold ph-user-circle text-2xl"></i>
            <span class="text-[10px] font-montserrat font-semibold tracking-wide">Profil</span>
        </a>

    </div>
</nav>
