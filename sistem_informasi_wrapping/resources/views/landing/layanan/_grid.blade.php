{{-- ============================================
    BAGIAN: Service Cards Grid
    Deskripsi: Grid berisi daftar paket layanan yang dinamis dari DB/fallback
============================================ --}}
@if(count($services) > 0)
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 items-stretch">
    @foreach($services as $idx => $svc)
        @php
            $fiturArr = [];
            if (!empty($svc['fitur'])) {
                if (is_array($svc['fitur'])) {
                    foreach ($svc['fitur'] as $item) {
                        if (is_array($item)) {
                            $fiturArr[] = $item['nama_fitur'] ?? $item[0] ?? '';
                        } else {
                            $fiturArr[] = $item;
                        }
                    }
                } elseif (is_string($svc['fitur'])) {
                    $decoded = json_decode($svc['fitur'], true);
                    $fiturArr = $decoded ?? [];
                }
            }

            $imgSrc = !empty($svc['gambar'])
                ? asset('storage/' . $svc['gambar'])
                : ($fallbackImages[$idx % count($fallbackImages)]);

            $badge       = $badgeLabels[$idx % count($badgeLabels)] ?? '';
            $badgeBg     = $badgeColors[$idx % count($badgeColors)];
            $badgeClr    = $badgeTextColors[$idx % count($badgeTextColors)];
            $isFeatured  = ($idx === 1); // Best Seller card
            $plainDesc   = $svc['deskripsi'] ?? '';
        @endphp
        <div class="bg-gradient-to-b from-[#161616] to-[#0f0f0f] border border-white/[0.06] rounded-2xl overflow-hidden flex flex-col transition-all duration-[400ms] ease-[cubic-bezier(.22,.61,.36,1)] hover:-translate-y-1.5 hover:shadow-[0_20px_50px_-12px_rgba(242,153,74,0.15)] hover:border-[rgba(242,153,74,0.25)]{{ $isFeatured ? ' ring-1 ring-[var(--accent)]/20' : '' }}">

            <div class="relative h-48 sm:h-52 overflow-hidden bg-gray-950 flex-shrink-0">
                <img src="{{ $imgSrc }}"
                     alt="{{ $svc['nama'] }}"
                     class="w-full h-full object-cover object-center transition-transform duration-[600ms] ease-[cubic-bezier(.22,.61,.36,1)] group-hover:scale-106"
                     loading="lazy">
                <div class="absolute inset-0 bg-gradient-to-t from-[#0f0f0f] to-transparent"></div>
                <div class="absolute top-4 right-4 z-10">
                    <span class="text-[0.6rem] font-bold tracking-[0.12em] uppercase px-3 py-1.5 rounded-full backdrop-blur-sm border"
                          style="background:{{ $badgeBg }};color:{{ $badgeClr }};border-color:rgba(242,153,74,0.25)">
                        {{ $badge }}
                    </span>
                </div>
            </div>

            <div class="flex flex-col flex-1 p-5 sm:p-6 gap-4">
                <h3 class="text-lg font-extrabold text-white leading-tight">
                    {{ $svc['nama'] }}
                </h3>

                @if(!empty($svc['harga']))
                    <div class="flex items-baseline gap-1">
                        <span class="text-base font-black text-[var(--accent)]">{{ $svc['harga'] }}</span>
                        <span class="text-xs text-gray-500 font-medium">{{ $profil->layanan_harga_satuan_label ?? '/unit' }}</span>
                    </div>
                @endif

                {{-- Deskripsi dengan "Baca Selengkapnya" --}}
                @if(!empty($plainDesc))
                    <div>
                        <div class="prose text-gray-500 text-xs leading-relaxed transition-all duration-300" id="desc-{{ $idx }}">
                            {!! $plainDesc !!}
                        </div>
                        @if(strlen($plainDesc) > 100)
                            <button type="button" onclick="toggleDesc('{{ $idx }}')" id="btn-desc-{{ $idx }}" class="text-[var(--accent)] text-[10px] font-bold mt-1 uppercase tracking-wider hover:underline focus:outline-none">Baca Selengkapnya</button>
                        @endif
                    </div>
                @endif

                <div class="border-t border-white/[0.06] my-1"></div>

                @if(!empty($fiturArr))
                    <ul class="space-y-2.5 flex-1">
                        @foreach($fiturArr as $item)
                            @if(!empty($item))
                                <li class="flex items-start gap-2.5 text-[0.78rem] text-white/55">
                                    <svg class="w-3.5 h-3.5 text-[var(--accent)] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>{{ $item }}</span>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                @else
                    <div class="flex-1"></div>
                @endif

                {{-- Tombol: auth → keranjang/direct-order, guest → popup register --}}
                @auth
                    <div class="flex gap-2 pt-2">
                        <button type="button" onclick="addToCart({{ $services[$idx]['id'] ?? '0' }}, '{{ addslashes($services[$idx]['nama'] ?? '') }}')"
                                class="flex-1 text-center font-extrabold text-[0.6rem] tracking-[0.1em] uppercase py-3 px-3 rounded-xl transition-all duration-200 hover:opacity-85 cursor-pointer"
                                style="background:var(--accent);color:#0a0a0a">
                            <i class="ph-bold ph-shopping-cart-simple mr-1"></i> Keranjang
                        </button>
                        <a href="{{ route('pesanan.direct-order', ['package_id' => $services[$idx]['id'] ?? '']) }}"
                           class="flex-1 text-center font-extrabold text-[0.6rem] tracking-[0.1em] uppercase py-3 px-3 rounded-xl transition-all duration-200 hover:opacity-85 block"
                           style="background:var(--accent);color:#0a0a0a">
                            <i class="ph-bold ph-lightning-fill mr-1"></i> Pesan
                        </a>
                    </div>
                @else
                    <button type="button" onclick="showRegisterModal('{{ $svc['nama'] }}')" class="block w-full text-center font-extrabold text-[0.72rem] tracking-[0.1em] uppercase py-3.5 px-5 rounded-xl transition-opacity duration-200 hover:opacity-88 hover:scale-102 mt-2 focus:outline-none cursor-pointer"
                       style="background:var(--accent);color:#0a0a0a">
                        {{ $profil->layanan_card_pesan_button ?? 'Pesan Sekarang' }}
                    </button>
                @endauth
            </div>
        </div>
    @endforeach
</div>
@else
<div class="py-16 text-center border border-dashed border-white/10 rounded-[32px] bg-[#121212]/40 w-full col-span-full" data-aos="fade-up" data-aos-duration="800">
    <div class="w-16 h-16 rounded-full bg-white/5 flex items-center justify-center text-gray-500 mx-auto mb-4">
        <i class="ph-bold ph-package text-2xl text-[var(--accent)]"></i>
    </div>
    <h4 class="text-base font-bold text-white mb-1">{{ $profil->layanan_empty_state_title ?? 'admin belum menambahkan' }}</h4>
    <p class="text-xs text-gray-500 font-light">{{ $profil->layanan_empty_state_desc ?? 'Admin belum menambahkan paket layanan saat ini.' }}</p>
</div>
@endif

<!-- Modal Auth Ajakan Membuat Akun -->
<div id="register-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden p-4">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closeRegisterModal()"></div>
    
    <!-- Modal Box -->
    <div class="bg-[#121212] border border-white/10 rounded-3xl max-w-md w-full p-8 space-y-6 relative z-10 transform scale-95 opacity-0 transition-all duration-300 shadow-[0_20px_50px_rgba(0,0,0,0.5)]" id="modal-box">
        <!-- Close Button -->
        <button onclick="closeRegisterModal()" class="absolute top-4 right-4 text-gray-400 hover:text-white text-xl">&times;</button>
        
        <!-- Icon -->
        <div class="w-16 h-16 rounded-2xl bg-[#f2994a]/10 border border-[#f2994a]/20 flex items-center justify-center mx-auto text-[#f2994a]">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
            </svg>
        </div>
        
        <!-- Content -->
        <div class="text-center space-y-2">
            <h3 class="text-xl font-bold text-white tracking-tight">Buat Akun Sekarang</h3>
            <p class="text-gray-400 text-xs sm:text-sm leading-relaxed">
                Anda perlu membuat akun terlebih dahulu untuk melakukan pemesanan paket <span id="modal-package-name" class="text-[#f2994a] font-bold"></span>. Dapatkan akses ke dashboard customer dan pantau pengerjaan secara real-time!
            </p>
        </div>
        
        <!-- Actions -->
        <div class="space-y-3 pt-2">
            <a href="{{ route('register') }}" class="block w-full text-center py-3.5 bg-[#f2994a] hover:bg-[#e28a44] text-black font-extrabold text-xs tracking-wider uppercase rounded-xl transition-all shadow-[0_4px_15px_rgba(242,153,74,0.3)] hover:scale-102 active:scale-98">
                Daftar Akun Baru
            </a>
            <div class="text-center pt-2">
                <span class="text-gray-500 text-xs">Sudah punya akun? </span>
                <a href="{{ route('login') }}" class="text-[#f2994a] hover:text-[#e28a44] text-xs font-bold transition-all hover:underline">
                    Masuk di sini
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleDesc(idx) {
        const descEl = document.getElementById('desc-' + idx);
        const btnEl = document.getElementById('btn-desc-' + idx);
        if (descEl.classList.contains('line-clamp-3')) {
            descEl.classList.remove('line-clamp-3');
            btnEl.innerText = 'Sembunyikan';
        } else {
            descEl.classList.add('line-clamp-3');
            btnEl.innerText = 'Baca Selengkapnya';
        }
    }

    function showRegisterModal(packageName) {
        const modal = document.getElementById('register-modal');
        const modalBox = document.getElementById('modal-box');
        const nameSpan = document.getElementById('modal-package-name');
        
        nameSpan.innerText = packageName;
        modal.classList.remove('hidden');
        
        // Trigger reflow to animate
        void modalBox.offsetWidth;
        
        modalBox.classList.remove('scale-95', 'opacity-0');
        modalBox.classList.add('scale-100', 'opacity-100');
    }
    
    function closeRegisterModal() {
        const modal = document.getElementById('register-modal');
        const modalBox = document.getElementById('modal-box');
        
        modalBox.classList.remove('scale-100', 'opacity-100');
        modalBox.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

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
                if (swalResult.isConfirmed) {
                    window.location.href = '/keranjang';
                }
                updateCartBadgeGlobally();
            } else {
                showCartLimitWarning();
            }
        } catch (err) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Terjadi kesalahan. Silakan coba lagi.',
                confirmButtonColor: '#f2994a',
                customClass: { popup: 'swal-dark', title: 'swal-title' }
            });
        }
    }

    async function showCartLimitWarning() {
        let cartItemsHtml = '';
        try {
            const res = await fetch('/api/keranjang/count', {
                headers: { 'Accept': 'application/json' }
            });
            const countData = await res.json();
        } catch(e) {}

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
        try {
            const cartRes = await fetch('/keranjang', {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            const cartData = await cartRes.json();
            if (cartData.data?.details) {
                cartItemsHtml = cartData.data.details.map((item, idx) => `
                    <div style="display:flex;align-items:center;justify-content:space-between;padding:8px 12px;background:rgba(255,255,255,0.05);border-radius:8px;margin-bottom:6px;">
                        <span style="font-size:12px;color:#fff;">${idx + 1}. ${item.layanan?.nama_layanan || 'Paket'}</span>
                    </div>
                `).join('');
            }
        } catch(e) {}

        Swal.fire({
            icon: 'warning',
            title: 'Batas Keranjang Tercapai!',
            html: `
                <div style="text-align:left;color:#d1d5db;font-size:13px;">
                    <p style="margin-bottom:12px;">Keranjang Anda sudah penuh (maksimal 3 paket). Hapus salah satu paket untuk menambahkan yang baru.</p>
                    <div style="margin-top:12px;border-top:1px solid rgba(255,255,255,0.1);padding-top:12px;">
                        <p style="font-size:12px;font-weight:bold;color:#f2994a;margin-bottom:8px;">Paket di Keranjang:</p>
                        ${cartItemsHtml || '<p style="color:#6b7280;font-size:12px;">Muat ulang halaman untuk melihat daftar</p>'}
                    </div>
                </div>
            `,
            confirmButtonText: 'Kelola Keranjang',
            confirmButtonColor: '#f2994a',
            showCancelButton: true,
            cancelButtonText: 'Batal',
            cancelButtonColor: '#6b7280',
            customClass: { popup: 'swal-dark', title: 'swal-title' }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '/keranjang';
            }
        });
    }

    async function updateCartBadgeGlobally() {
        try {
            const res = await fetch('/api/keranjang/count', {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            const json = await res.json();
            const count = json.data?.count ?? 0;
            document.querySelectorAll('[data-cart-badge], [data-cart-badge-mobile]').forEach(b => {
                if (count > 0) { b.textContent = count > 9 ? '9+' : count; b.classList.remove('hidden'); }
                else { b.classList.add('hidden'); }
            });
        } catch(e) {}
    }
</script>

<style>
.swal-dark {
  background-color: #121212 !important;
  color: #ffffff !important;
  border: 1px solid rgba(255,255,255,0.1) !important;
  border-radius: 24px !important;
}
.swal-title {
  color: #f2994a !important;
}
.swal-dark .swal2-html-container {
  color: #d1d5db !important;
}
</style>
