<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $profil->nama_perusahaan ?? 'Dantie Sticker' }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-zinc-950 text-zinc-300 font-sans antialiased">

<nav class="flex justify-between items-center p-6 bg-zinc-900/50 backdrop-blur-md border-b border-zinc-800 sticky top-0 z-50">
    <div class="text-orange-500 font-black text-2xl tracking-tighter flex items-center gap-2">
        <i class="fas fa-layer-group text-orange-600"></i>
        DANTIE STICKER
    </div>
    
    <div class="flex items-center gap-6">
        <a href="{{ route('profil.perusahaan') }}" class="text-zinc-400 hover:text-white transition-colors font-medium">Profil Perusahaan</a>
        @guest
            <a href="{{ route('login') }}" class="text-white hover:text-orange-500 transition-colors">Login</a>
            <a href="{{ route('register') }}" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg font-bold transition-all">Register</a>
        @endguest

        @auth
            <div class="flex items-center gap-4 border-l border-zinc-700 pl-6">
                <span class="text-zinc-100 font-medium">Halo, <span class="text-orange-400">{{ Auth::user()->name }}</span></span>
                <a href="/profile" class="text-zinc-400 hover:text-white bg-zinc-800 px-3 py-1.5 rounded-lg text-sm">Profil</a>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="text-red-400 hover:text-red-300 text-sm font-bold ml-2">Logout</button>
                </form>
            </div>
        @endauth
    </div>
</nav>

<header class="relative py-24 px-6 overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-[300px] bg-orange-600/10 blur-[120px] rounded-full"></div>

    <div class="max-w-6xl mx-auto grid md:grid-cols-2 gap-12 items-center relative z-10">
        {{-- Sisi Kiri: Info & Deskripsi --}}
        <div class="space-y-8">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-orange-500/10 border border-orange-500/20 text-orange-500 text-sm font-bold uppercase tracking-widest">
                Professional Sticker Service
            </div>
            
            <div class="space-y-4">
                <h1 class="text-6xl font-black text-white leading-tight">
                    {{ $profil->nama_perusahaan ?? 'Nama Toko' }}
                </h1>
                <p class="text-xl text-zinc-400 leading-relaxed max-w-lg italic border-l-4 border-orange-600 pl-6">
                    "{{ $profil->deskripsi }}"
                </p>
            </div>

            {{-- Info Kontak Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="flex items-center gap-4 bg-zinc-900/50 p-4 rounded-2xl border border-zinc-800 hover:border-orange-500/50 transition-all">
                    <div class="w-12 h-12 bg-orange-600/20 rounded-xl flex items-center justify-center text-orange-500">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div>
                        <p class="text-xs text-zinc-500 uppercase">Alamat</p>
                        <p class="text-sm font-semibold text-zinc-200">{{ $profil->alamat }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 bg-zinc-900/50 p-4 rounded-2xl border border-zinc-800 hover:border-orange-500/50 transition-all">
                    <div class="w-12 h-12 bg-orange-600/20 rounded-xl flex items-center justify-center text-orange-500">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div>
                        <p class="text-xs text-zinc-500 uppercase">Email</p>
                        <p class="text-sm font-semibold text-zinc-200">{{ $profil->email }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 bg-zinc-900/50 p-4 rounded-2xl border border-zinc-800 hover:border-orange-500/50 transition-all col-span-1 sm:col-span-2">
                    <div class="w-10 h-10 bg-green-600/20 rounded-xl flex items-center justify-center text-green-500">
                        <i class="fab fa-whatsapp"></i>
                    </div>
                    <div>
                        <p class="text-xs text-zinc-500 uppercase">WhatsApp</p>
                        <p class="text-sm font-semibold text-zinc-200">{{ $profil->nomor_telepon }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sisi Kanan: Menampilkan Logo --}}
        <div class="flex justify-center md:justify-end">
            <div class="relative group">
                <div class="absolute -inset-4 bg-orange-600 rounded-full blur-3xl opacity-20 group-hover:opacity-40 transition-opacity"></div>
                <div class="relative w-72 h-72 md:w-80 md:h-80 bg-zinc-900 rounded-[40px] border-4 border-zinc-800 p-8 flex items-center justify-center overflow-hidden shadow-2xl">
                    @if($profil->logo)
                        <img src="{{ asset('storage/' . $profil->logo) }}" alt="Logo" class="w-full h-full object-contain">
                    @else
                        <i class="fas fa-store text-8xl text-zinc-700"></i>
                    @endif
                </div>
            </div>
        </div>
    </div>
</header>

<section class="py-24 px-6 bg-zinc-900/30">
    <div class="max-w-6xl mx-auto">
        <div class="flex items-center justify-between mb-12">
            <div>
                <h2 class="text-4xl font-black text-white">Katalog Layanan</h2>
                <div class="w-20 h-1.5 bg-orange-600 mt-3 rounded-full"></div>
            </div>
            <p class="text-zinc-500 font-medium">Pilih sesuai kebutuhan Anda</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($layanans as $layanan)
                <div class="group bg-zinc-900 border border-zinc-800 p-8 rounded-[32px] hover:bg-zinc-800/50 hover:border-orange-600/50 transition-all duration-500 relative overflow-hidden">
                    {{-- Badge Tipe --}}
                    <div class="absolute top-0 right-0 bg-orange-600/20 text-orange-500 px-4 py-2 rounded-bl-2xl text-xs font-black uppercase">
                        {{ $layanan->tipe_layanan }}
                    </div>

                    <div class="space-y-6">
                        <div class="w-14 h-14 bg-zinc-800 rounded-2xl flex items-center justify-center group-hover:scale-110 group-hover:bg-orange-600 transition-all duration-500">
                            <i class="fas fa-rocket text-2xl text-orange-500 group-hover:text-white"></i>
                        </div>

                        <div>
                            <h3 class="text-2xl font-bold text-zinc-100 mb-2">{{ $layanan->nama_layanan }}</h3>
                            <p class="text-zinc-500 text-sm leading-relaxed line-clamp-3">
                                {{ $layanan->deskripsi }}
                            </p>
                        </div>

                        <div class="pt-6 border-t border-zinc-800 flex items-center justify-between">
                            <div>
                                <p class="text-xs text-zinc-500 uppercase font-bold">Harga Mulai</p>
                                <span class="text-xl font-black text-white italic">
                                    Rp {{ number_format($layanan->harga, 0, ',', '.') }}
                                </span>
                            </div>
                            <button class="bg-zinc-800 hover:bg-orange-600 text-white w-12 h-12 rounded-xl flex items-center justify-center transition-all group-hover:shadow-lg group-hover:shadow-orange-600/20">
                                <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ================= GALERI ================= --}}
<section class="max-w-6xl mx-auto px-6 pb-24">
    <div class="flex items-center gap-4 mb-12">
        <h2 class="text-3xl font-black text-white uppercase tracking-wider">Galeri Hasil Pekerjaan</h2>
        <div class="h-1 flex-grow bg-zinc-900 rounded-full overflow-hidden">
            <div class="w-24 h-full bg-orange-600"></div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        @foreach($galeris as $item)
            <div class="group bg-zinc-900 border border-zinc-800 rounded-2xl overflow-hidden shadow-lg hover:border-orange-600/50 transition-all">

                <div class="overflow-hidden">
                    <img 
                        src="{{ asset('storage/' . $item->foto) }}" 
                        alt="{{ $item->judul }}"
                        class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-500"
                    >
                </div>

                <div class="p-4">
                    <h3 class="text-white font-bold text-lg mb-2">
                        {{ $item->judul }}
                    </h3>

                    <p class="text-zinc-500 text-sm line-clamp-2">
                        {{ $item->deskripsi }}
                    </p>

                    <span class="text-xs text-zinc-600 mt-3 block">
                        {{ $item->tanggal_upload }}
                    </span>
                </div>

            </div>
        @endforeach
    </div>
</section>

{{-- ✅ Google Maps Section — di luar footer, sebelum footer --}}
@if($profil->maps_url)
<section class="max-w-6xl mx-auto px-6 pb-24">
    <div class="flex items-center gap-4 mb-12">
        <h2 class="text-3xl font-black text-white uppercase tracking-wider">Lokasi Kami</h2>
        <div class="h-1 flex-grow bg-zinc-900 rounded-full overflow-hidden">
            <div class="w-24 h-full bg-orange-600"></div>
        </div>
    </div>

    <div class="bg-zinc-900 border border-zinc-800 rounded-[24px] overflow-hidden shadow-2xl">
        <iframe
            src="{{ $profil->maps_url }}"
            width="100%"
            height="450"
            style="border:0;"
            allowfullscreen=""
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade">
        </iframe>

        {{-- Info + Tombol --}}
        <div class="p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-t border-zinc-800">
            <div class="flex items-center gap-3 text-zinc-300">
                <i class="fas fa-map-marker-alt text-orange-500 text-lg"></i>
                <span class="text-sm">{{ $profil->alamat }}</span>
            </div>
            <a 
                href="https://maps.google.com/?q={{ urlencode($profil->alamat) }}"
                target="_blank"
                class="flex items-center gap-2 bg-orange-600 hover:bg-orange-700 text-white text-sm font-bold px-5 py-2.5 rounded-xl transition-all">
                <i class="fas fa-map-marker-alt"></i>
                Buka di Google Maps
            </a>
        </div>
    </div>
</section>
@endif

<footer class="bg-zinc-950 text-zinc-500 py-16 text-center border-t border-zinc-900">
    <div class="max-w-6xl mx-auto px-6">
        <div class="text-orange-500 font-black text-xl mb-6">DANTIE STICKER.</div>
        <p class="max-w-md mx-auto text-sm leading-relaxed mb-8">
            Solusi stiker terbaik untuk kendaraan dan bisnis Anda. Kualitas premium, hasil presisi, dan harga kompetitif.
        </p>
        <div class="flex justify-center gap-6 mb-8 text-xl">
            <a href="#" class="hover:text-orange-500 transition-colors"><i class="fab fa-instagram"></i></a>
            <a href="#" class="hover:text-orange-500 transition-colors"><i class="fab fa-facebook"></i></a>
            <a href="#" class="hover:text-orange-500 transition-colors"><i class="fab fa-tiktok"></i></a>
        </div>
        <p class="text-xs uppercase tracking-widest">&copy; 2026 {{ $profil->nama_perusahaan }}. All Rights Reserved.</p>
    </div>
</footer>

</body>
</html>