<!-- Paket Layanan Section dengan Carousel -->
<div class="space-y-4">
    <div class="flex justify-between items-center">
        <h3 class="text-lg font-bold text-white tracking-tight">{{ $profil->section_paket_kami ?? 'Paket Layanan Kami' }}</h3>
        <a href="{{ route('katalog.user') }}" class="text-[10px] font-bold text-[#f2994a] uppercase tracking-widest hover:underline flex items-center gap-1">
            Lihat Semua <i class="ph-bold ph-caret-right text-xs"></i>
        </a>
    </div>

    <!-- Carousel Container -->
    <div class="relative group">
        <!-- Slider Wrapper -->
        <div class="overflow-hidden rounded-2xl">
            <div class="flex gap-6 pb-2 scroll-smooth snap-x snap-mandatory overflow-x-auto [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden"
                 style="scroll-behavior: smooth;">

                @forelse($layanans as $package)
                @php
                    $fiturList = [];
                    if ($package->fitur && is_array($package->fitur)) {
                        foreach ($package->fitur as $f) {
                            $item = is_array($f) ? ($f['nama_fitur'] ?? '') : $f;
                            if (!empty($item)) $fiturList[] = $item;
                        }
                    }
                    $imgSrc = $package->foto_contoh
                        ? asset('storage/' . $package->foto_contoh)
                        : null;
                @endphp
                <div class="shrink-0 w-72 sm:w-80 snap-start" data-package-id="{{ $package->id_layanan }}">
                    <form action="{{ route('keranjang.tambah') }}" method="POST" class="contents" id="cart-form-{{ $package->id_layanan }}">
                        @csrf
                        <input type="hidden" name="id_paket" value="{{ $package->id_layanan }}">
                        <input type="hidden" name="jumlah" value="1">
                    </form>
                    <!-- Card Package -->
                    <div class="h-full bg-gradient-to-b from-[#161616] to-[#0f0f0f] border border-white/[0.06] rounded-2xl overflow-hidden flex flex-col transition-all duration-[400ms] ease-[cubic-bezier(.22,.61,.36,1)] hover:-translate-y-1.5 hover:shadow-[0_20px_50px_-12px_rgba(242,153,74,0.15)] hover:border-[rgba(242,153,74,0.25)]">

                        <!-- Image Section -->
                        <a href="{{ route('pesanan.direct-order', ['package_id' => $package->id_layanan]) }}" class="relative h-48 sm:h-52 overflow-hidden bg-gray-950 flex-shrink-0 block">
                            @if($imgSrc)
                            <img src="{{ $imgSrc }}"
                                 alt="{{ $package->nama_layanan }}"
                                 class="w-full h-full object-cover object-center transition-transform duration-[600ms] ease-[cubic-bezier(.22,.61,.36,1)] hover:scale-110"
                                 loading="lazy">
                            @else
                            <div class="w-full h-full bg-gradient-to-br from-[#f2994a]/30 to-[#f2994a]/10 flex items-center justify-center">
                                <i class="ph-bold ph-package text-5xl text-[#f2994a]/40"></i>
                            </div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-[#0f0f0f] to-transparent pointer-events-none"></div>

                            @if($package->tipe_paket)
                            <div class="absolute top-3 right-3 z-10">
                                <span class="text-[0.6rem] font-bold tracking-[0.12em] uppercase px-3 py-1.5 rounded-full backdrop-blur-sm border"
                                      style="background:rgba(242,153,74,0.12);color:#f2994a;border-color:rgba(242,153,74,0.25)">
                                    {{ $package->tipe_paket }}
                                </span>
                            </div>
                            @endif
                        </a>

                        <!-- Content Section -->
                        <div class="flex flex-col flex-1 p-5 sm:p-6 gap-4">
                            <h3 class="text-base font-extrabold text-white leading-tight">
                                {{ $package->nama_layanan }}
                            </h3>

                            <div class="flex items-baseline gap-1">
                                <span class="text-base font-black text-[#f2994a]">
                                    Rp {{ number_format($package->harga, 0, ',', '.') }}
                                </span>
                                @if($package->estimasi_waktu)
                                <span class="text-[10px] text-gray-400 flex items-center gap-1 ml-auto">
                                    <i class="ph-bold ph-clock-fill"></i>
                                    {{ $package->estimasi_waktu }}
                                </span>
                                @endif
                            </div>

                            @if($package->deskripsi)
                            <p class="text-gray-500 text-xs leading-relaxed">
                                {{ is_array($package->deskripsi) ? implode(', ', $package->deskripsi) : $package->deskripsi }}
                            </p>
                            @endif

                            <div class="border-t border-white/[0.06]"></div>

                            @if(!empty($fiturList))
                            <ul class="space-y-2.5 flex-1">
                                @foreach(array_slice($fiturList, 0, 3) as $fitur)
                                <li class="flex items-start gap-2.5 text-[0.78rem] text-white/55">
                                    <svg class="w-3.5 h-3.5 text-[#f2994a] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>{{ $fitur }}</span>
                                </li>
                                @endforeach
                                @if(count($fiturList) > 3)
                                <li class="text-[9px] text-gray-500 italic">+{{ count($fiturList) - 3 }} fitur lainnya</li>
                                @endif
                            </ul>
                            @else
                            <div class="flex-1"></div>
                            @endif

                            <div class="flex gap-2 pt-2">
                                <button type="button" onclick="addToCart({{ $package->id_layanan }}, '{{ addslashes($package->nama_layanan) }}')"
                                        class="flex-1 text-center font-extrabold text-[0.6rem] tracking-[0.1em] uppercase py-3 px-3 rounded-xl transition-all duration-200 hover:opacity-85 cursor-pointer bg-[#f2994a] text-black">
                                    <i class="ph-bold ph-shopping-cart-simple mr-1"></i> Keranjang
                                </button>
                                <a href="{{ route('pesanan.direct-order', ['package_id' => $package->id_layanan]) }}"
                                   class="flex-1 text-center font-extrabold text-[0.6rem] tracking-[0.1em] uppercase py-3 px-3 rounded-xl transition-all duration-200 hover:opacity-85 block bg-[#f2994a] text-black">
                                    <i class="ph-bold ph-lightning-fill mr-1"></i> Pesan
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="w-full py-16 text-center border border-dashed border-white/10 rounded-[32px] bg-white/[0.02]">
                    <div class="w-16 h-16 rounded-full bg-white/5 flex items-center justify-center text-gray-500 mx-auto mb-4">
                        <i class="ph-bold ph-package text-2xl text-[#f2994a]"></i>
                    </div>
                    <h4 class="text-base font-bold text-white mb-1">admin belum menambahkan</h4>
                    <p class="text-xs text-gray-500 font-light">Admin belum menambahkan paket layanan saat ini.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Navigation Buttons -->
        @if(count($layanans) > 0)
        <button class="carousel-prev absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 z-10 bg-[#f2994a] hover:bg-[#f2994a]/90 text-white rounded-full p-2 transition-all duration-200 opacity-0 group-hover:opacity-100"
                aria-label="Scroll left">
            <i class="ph-bold ph-caret-left text-lg"></i>
        </button>
        <button class="carousel-next absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 z-10 bg-[#f2994a] hover:bg-[#f2994a]/90 text-white rounded-full p-2 transition-all duration-200 opacity-0 group-hover:opacity-100"
                aria-label="Scroll right">
            <i class="ph-bold ph-caret-right text-lg"></i>
        </button>
        @endif
    </div>
</div>

<!-- Toast Container -->
<div id="toast-container" class="fixed top-4 right-4 z-50"></div>

<script>
async function addToCart(packageId, packageName) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
    try {
        const res = await fetch('/keranjang/tambah', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ id_paket: packageId, jumlah: 1 })
        });
        const data = await res.json();

        if (res.ok && data.status === 'success') {
            const swalResult = await Swal.fire({
                icon: 'success',
                title: 'Paket Ditambahkan!',
                text: `Paket ${packageName} berhasil dimasukkan ke keranjang`,
                confirmButtonText: 'Lihat Keranjang',
                confirmButtonColor: '#f2994a',
                showCancelButton: true,
                cancelButtonText: 'Lanjut Belanja',
                cancelButtonColor: '#6b7280',
                timer: 3000,
                timerProgressBar: true,
                customClass: { popup: 'swal-dark', title: 'swal-title' }
            });
            if (swalResult.isConfirmed) window.location.href = '/keranjang';
            updateCartBadgeGlobal();
        } else {
            let cartHtml = '';
            try {
                const r2 = await fetch('/keranjang', {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                });
                const d2 = await r2.json();
                if (d2.data?.details) {
                    cartHtml = d2.data.details.map((item, idx) =>
                        `<div style="display:flex;align-items:center;justify-content:space-between;padding:8px 12px;background:rgba(255,255,255,0.05);border-radius:8px;margin-bottom:6px;">
                            <span style="font-size:12px;color:#fff;">${idx+1}. ${item.layanan?.nama_layanan||'Paket'}</span>
                        </div>`
                    ).join('');
                }
            } catch(e) {}
            Swal.fire({
                icon: 'warning',
                title: 'Batas Keranjang Tercapai!',
                html: `<div style="text-align:left;color:#d1d5db;font-size:13px;">
                    <p style="margin-bottom:12px;">Keranjang Anda sudah penuh (maksimal 3 paket). Hapus salah satu paket untuk menambahkan yang baru.</p>
                    <div style="margin-top:12px;border-top:1px solid rgba(255,255,255,0.1);padding-top:12px;">
                        <p style="font-size:12px;font-weight:bold;color:#f2994a;margin-bottom:8px;">Paket di Keranjang:</p>
                        ${cartHtml||'<p style="color:#6b7280;font-size:12px;">Muat ulang halaman</p>'}
                    </div>
                </div>`,
                confirmButtonText: 'Kelola Keranjang',
                confirmButtonColor: '#f2994a',
                showCancelButton: true,
                cancelButtonText: 'Batal',
                cancelButtonColor: '#6b7280',
                customClass: { popup: 'swal-dark', title: 'swal-title' }
            }).then(r => { if (r.isConfirmed) window.location.href='/keranjang'; });
        }
    } catch(err) {
        Swal.fire({ icon:'error', title:'Gagal', text:'Terjadi kesalahan.', confirmButtonColor:'#f2994a', customClass:{popup:'swal-dark',title:'swal-title'} });
    }
}
async function updateCartBadgeGlobal() {
    try {
        const r = await fetch('/api/keranjang/count', { headers:{'Accept':'application/json'} });
        const j = await r.json();
        const c = j.data?.count ?? 0;
        document.querySelectorAll('[data-cart-badge],[data-cart-badge-mobile]').forEach(b => {
            if(c>0){b.textContent=c>9?'9+':c;b.classList.remove('hidden')}else{b.classList.add('hidden')}
        });
    }catch(e){}
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.packages-carousel-wrapper').forEach(wrapper => {
        const parentGroup = wrapper.closest('.relative.group');
        if (!parentGroup) return;
        const prevBtn = parentGroup.querySelector('.carousel-prev');
        const nextBtn = parentGroup.querySelector('.carousel-next');
        if (!prevBtn || !nextBtn) return;
        const scroll = (direction) => {
            wrapper.scrollBy({ left: direction === 'next' ? 350 : -350, behavior: 'smooth' });
        };
        prevBtn.addEventListener('click', () => scroll('prev'));
        nextBtn.addEventListener('click', () => scroll('next'));
    });
});
</script>

