<!-- Promotional / Secondary Banner (Matching Image 1 Bottom Banner) -->
<div class="relative overflow-hidden rounded-[26px] border border-white/10 bg-gradient-to-r from-black via-[#0d0d0d] to-black shadow-2xl group">
    
    <!-- Background Image with Moody Showroom Lighting -->
    <div class="relative min-h-[200px] sm:min-h-[220px] flex items-center overflow-hidden">
        <img src="{{ asset('images/banner_exclusive_car.jpg') }}" 
             alt="Exclusive Collection" 
             class="absolute inset-0 w-full h-full object-cover object-right sm:object-center group-hover:scale-105 transition-transform duration-700 brightness-[0.75]">

        <!-- Gradient Contrast Filter -->
        <div class="absolute inset-0 bg-gradient-to-r from-black/95 via-black/70 to-transparent"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent"></div>

        <!-- Banner Content -->
        <div class="relative z-10 p-6 sm:p-8 max-w-md space-y-2">
            <!-- Orange Tag -->
            <span class="inline-block text-[11px] font-montserrat font-bold text-[#ff6b00] tracking-wider uppercase">
                Exclusive Collection
            </span>

            <!-- Bold Title in Audiowide Font -->
            <h3 class="text-xl sm:text-2xl font-audiowide font-bold text-white tracking-wide leading-tight drop-shadow-md">
                Built for Speed<br>
                <span class="text-white">Driven by Passion</span>
            </h3>

            <!-- Subtitle in Questrial -->
            <p class="text-xs font-questrial text-gray-300 leading-relaxed drop-shadow">
                Limited cars. Unlimited adrenaline. Custom wraps tailored for high-performance excellence.
            </p>

            <!-- Pill Button: "Discover More ->" -->
            <div class="pt-3">
                <a href="{{ route('katalog.user') }}" 
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-black hover:bg-[#ff6b00] hover:text-white font-montserrat font-bold text-xs uppercase tracking-wider rounded-full shadow-lg transition-all active:scale-95 group/btn">
                    <span>Discover More</span>
                    <div class="w-5 h-5 rounded-full bg-black text-white group-hover/btn:bg-white group-hover/btn:text-black flex items-center justify-center text-xs transition-colors">
                        <i class="ph-bold ph-arrow-right"></i>
                    </div>
                </a>
            </div>
        </div>

    </div>
</div>
