{{-- ============================================
    BAGIAN: CTA + Langkah Mudah Section
    Deskripsi: Call-to-action dan 3 langkah pemesanan
============================================ --}}
<section class="py-24 bg-[#080808] px-6 sm:px-10 lg:px-16 relative overflow-hidden" id="testimoni">
    <div class="absolute top-0 left-0 w-[400px] h-[400px] bg-[#f2994a]/5 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto relative z-10">
        <div class="bg-[#121212] border border-white/5 rounded-[40px] p-8 sm:p-12 lg:p-16" data-aos="zoom-in" data-aos-duration="1200">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">

                <!-- Left: CTA Text -->
                <div class="lg:col-span-7 space-y-6">
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white leading-[1.15] tracking-tight">
                        {{ $ctaTitle }}
                    </h2>
                    <p class="text-gray-400 text-sm sm:text-base leading-relaxed max-w-xl">
                        {{ $ctaSubtitle }}
                    </p>
                    <div class="flex flex-wrap items-center gap-4 sm:gap-6 pt-4">
                        <a href="https://wa.me/{{ $waNumber }}"
                           class="btn-premium px-8 py-4 rounded-xl font-bold text-xs sm:text-sm uppercase tracking-wider text-black flex items-center gap-3 transition-all hover:scale-105 active:scale-95 shadow-[0_4px_20px_rgba(242,153,74,0.3)]">
                            <i class="ph-bold ph-whatsapp-logo text-lg"></i> Hubungi WhatsApp
                        </a>
                        <a href="{{ route('katalog.user') }}"
                           class="px-8 py-4 rounded-xl border border-white/10 text-white font-bold text-xs sm:text-sm uppercase tracking-wider bg-white/5 hover:bg-white/10 hover:border-white/30 transition-all">
                            Pelajari Prosedur
                        </a>
                    </div>
                </div>

                <!-- Right: Steps -->
                <div class="lg:col-span-5">
                    <div class="bg-[#181818] border border-white/5 rounded-3xl p-8 space-y-6 shadow-xl relative overflow-hidden group hover:border-[#f2994a]/25 transition-all">
                        <div class="flex justify-between items-center pb-4 border-b border-white/5">
                            <h4 class="text-xs font-bold text-[#f2994a] tracking-widest uppercase">Langkah Mudah</h4>
                            <span class="text-[10px] text-gray-500 font-semibold uppercase tracking-wider">Fast Process</span>
                        </div>
                        <div class="space-y-6">
                            @foreach([
                                ['no'=>1, 'title'=>$s1t, 'desc'=>$s1d],
                                ['no'=>2, 'title'=>$s2t, 'desc'=>$s2d],
                                ['no'=>3, 'title'=>$s3t, 'desc'=>$s3d],
                            ] as $step)
                            <div class="flex items-start gap-4">
                                <div class="w-8 h-8 rounded-full bg-[#f2994a]/10 border border-[#f2994a]/30 flex items-center justify-center text-xs font-extrabold text-[#f2994a] flex-shrink-0 group-hover:bg-[#f2994a] group-hover:text-black transition-all duration-300">
                                    {{ $step['no'] }}
                                </div>
                                <div>
                                    <h5 class="text-sm font-bold text-white">{{ $step['title'] }}</h5>
                                    <p class="text-gray-500 text-xs mt-1 leading-relaxed">{{ $step['desc'] }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
