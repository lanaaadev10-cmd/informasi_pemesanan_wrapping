{{-- ============================================
    BAGIAN: Hero Section
    Deskripsi: Banner utama halaman beranda
============================================ --}}
<section class="relative min-h-screen pt-32 pb-20 px-6 sm:px-10 lg:px-16 flex items-center overflow-hidden bg-cover bg-center lg:bg-[right_center] bg-no-repeat" style="background-image: url('{{ $profil?->home_hero_image ? asset('storage/' . $profil->home_hero_image) : asset('images/hero_car.png') }}');">
    <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/85 to-black lg:bg-gradient-to-r lg:from-[#0a0a0a] lg:via-[#0a0a0a]/75 lg:to-transparent z-0"></div>
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-[#f2994a]/10 rounded-full blur-[120px] pointer-events-none z-10"></div>
    <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-[#e28a44]/5 rounded-full blur-[100px] pointer-events-none z-10"></div>
    <div class="max-w-7xl mx-auto w-full relative z-20">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <div class="lg:col-span-8 space-y-8" data-aos="fade-right" data-aos-duration="1200">
                <!-- Badge -->
                <div class="inline-flex items-center gap-2 bg-[#f2994a]/10 border border-[#f2994a]/20 px-4 py-2 rounded-full">
                    <span class="w-2 h-2 rounded-full bg-[#f2994a] animate-pulse"></span>
                    <span class="text-xs font-bold text-[#f2994a] tracking-wider uppercase">{{ $badge }}</span>
                </div>
                <!-- Heading -->
                <div class="space-y-4">
                    <h1 class="text-4xl sm:text-5xl lg:text-6.5xl font-extrabold text-white leading-[1.1] tracking-tight">
                        {{ $title1 }} <br>
                        <span style="background:linear-gradient(135deg,#f2994a,#e28a44);-webkit-background-clip:text;-webkit-text-fill-color:transparent">{{ $title2 }}</span>
                    </h1>
                    <p class="text-gray-300 text-sm sm:text-base md:text-lg leading-relaxed max-w-xl">
                        {{ $subtitle }}
                    </p>
                </div>
                {{-- CTAs --}}
                <div class="flex flex-wrap items-center gap-2 sm:gap-3 pt-2">
                    <a href="javascript:void(0)" onclick="showPesanPopup()"
                       class="inline-flex items-center gap-1.5 sm:gap-2 px-3.5 py-2 sm:px-6 sm:py-3.5 rounded-lg sm:rounded-xl font-bold text-[11px] sm:text-sm uppercase tracking-wider text-white border-2 border-white bg-transparent hover:bg-white hover:text-black transition-all duration-300 active:scale-95">
                        {{ !empty($profil->home_hero_cta_primary) ? $profil->home_hero_cta_primary : 'Pesan Sekarang' }}
                    </a>
                    <a href="{{ route('layanan') }}"
                       class="inline-flex items-center gap-1.5 sm:gap-2 px-3.5 py-2 sm:px-6 sm:py-3.5 rounded-lg sm:rounded-xl font-bold text-[11px] sm:text-sm uppercase tracking-wider text-white bg-[#f97316] hover:bg-[#ea6c0a] transition-all duration-300 active:scale-95 shadow-[0_4px_20px_rgba(249,115,22,0.35)]">
                        {{ !empty($profil->home_hero_cta_secondary) ? $profil->home_hero_cta_secondary : 'Lihat Layanan' }}
                        <i class="ph-bold ph-arrow-right text-[10px] sm:text-sm"></i>
                    </a>

                </div>
                <!-- Micro Stats -->
                <div class="flex items-center gap-12 pt-8 border-t border-white/5 max-w-md">
                    <div>
                        <h3 class="text-3xl font-extrabold text-white">{{ $stat1v }}</h3>
                        <p class="text-gray-400 text-xs mt-1 uppercase tracking-wider">{{ $stat1l }}</p>
                    </div>
                    <div class="w-px h-8 bg-white/10"></div>
                    <div>
                        <h3 class="text-3xl font-extrabold text-[#f2994a]">{{ $stat2v }}</h3>
                        <p class="text-gray-400 text-xs mt-1 uppercase tracking-wider">{{ $stat2l }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function showPesanPopup() {
    Swal.fire({
        icon: 'success',
        title: 'Pesan Sekarang',
        html: `
            <div style="text-align:left;color:#d1d5db;font-size:13px;line-height:1.6;">
                <p style="margin-bottom:8px;">Kami siap membantu Anda! Silakan pilih layanan yang diinginkan:</p>
                <div style="margin-top:12px;display:flex;flex-direction:column;gap:6px;">
                    <div style="display:flex;align-items:center;gap:10px;background:rgba(255,255,255,0.05);padding:8px 12px;border-radius:8px;">
                        <i class="ph-bold ph-car" style="font-size:16px;color:#f97316;"></i>
                        <span style="font-size:12px;color:#fff;">Wrapping Mobil</span>
                    </div>
                    <div style="display:flex;align-items:center;gap:10px;background:rgba(255,255,255,0.05);padding:8px 12px;border-radius:8px;">
                        <i class="ph-bold ph-motorcycle" style="font-size:16px;color:#f97316;"></i>
                        <span style="font-size:12px;color:#fff;">Wrapping Motor</span>
                    </div>
                    <div style="display:flex;align-items:center;gap:10px;background:rgba(255,255,255,0.05);padding:8px 12px;border-radius:8px;">
                        <i class="ph-bold ph-ship" style="font-size:16px;color:#f97316;"></i>
                        <span style="font-size:12px;color:#fff;">Wrapping Kapal Laut</span>
                    </div>
                </div>
                <p style="margin-top:12px;font-size:11px;color:#6b7280;">Klik tombol di bawah untuk melihat semua layanan.</p>
            </div>
        `,
        confirmButtonText: 'Lihat Semua Layanan',
        confirmButtonColor: '#f97316',
        showCancelButton: true,
        cancelButtonText: 'Tutup',
        cancelButtonColor: '#6b7280',
        timer: 5000,
        timerProgressBar: true,
        customClass: { popup: 'swal-dark', title: 'swal-title' }
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '{{ route("layanan") }}';
        }
    });
}
</script>
