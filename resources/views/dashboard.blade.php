<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - {{ $profil->nama_perusahaan ?? 'Dantie Sticker' }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-zinc-950 text-zinc-300 font-sans antialiased">

<nav class="flex justify-between items-center p-6 bg-zinc-900/80 backdrop-blur-md border-b border-zinc-800 sticky top-0 z-50">
    <div class="text-orange-500 font-black text-2xl tracking-tighter">DANTIE STICKER</div>
    
    <div class="flex items-center gap-6">
        <a href="{{ route('profil.perusahaan') }}" class="text-zinc-400 hover:text-white transition-colors text-sm">Profil Perusahaan</a>
        @guest
            <a href="{{ route('login') }}" class="text-white hover:text-orange-500 transition-colors">Login</a>
            <a href="{{ route('register') }}" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg font-bold transition-all">Register</a>
        @endguest

        @auth
            <div class="flex items-center gap-4">
                <span class="text-zinc-400 hidden sm:inline">Selamat Datang,</span>
                <span class="text-white font-bold">{{ Auth::user()->name }}</span>
                <a href="/profile" class="text-zinc-400 hover:text-white bg-zinc-800 px-3 py-1.5 rounded-lg text-sm transition-all border border-zinc-700">Profil saya</a>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="text-red-500 font-bold hover:text-red-400 text-sm">Logout</button>
                </form>
            </div>
        @endauth
    </div>
</nav>

<header class="max-w-6xl mx-auto px-6 py-16">
    <div class="bg-zinc-900 border border-zinc-800 rounded-[32px] p-8 md:p-12 relative overflow-hidden shadow-2xl">
        <div class="absolute top-0 right-0 w-64 h-64 bg-orange-600/10 blur-[100px] rounded-full"></div>

        <div class="flex flex-col md:flex-row items-center gap-10 relative z-10">
            <div class="flex-shrink-0">
                @if($profil && $profil->logo)
                    <div class="w-40 h-40 bg-white rounded-3xl p-4 shadow-xl flex items-center justify-center overflow-hidden border-4 border-zinc-800">
                        <img src="{{ asset('storage/' . $profil->logo) }}" alt="Logo" class="max-w-full max-h-full object-contain">
                    </div>
                @else
                    <div class="w-40 h-40 bg-zinc-800 rounded-3xl flex items-center justify-center">
                        <i class="fas fa-store text-5xl text-zinc-600"></i>
                    </div>
                @endif
            </div>

            <div class="text-center md:text-left flex-grow">
                <h1 class="text-4xl md:text-6xl font-black text-white mb-4 tracking-tight">
                    {{ $profil->nama_perusahaan ?? 'Nama Toko' }}
                </h1>
                <p class="text-lg text-zinc-400 leading-relaxed italic border-l-4 border-orange-600 pl-4 md:pl-6 max-w-2xl">
                    {{ $profil->deskripsi ?? 'Deskripsi perusahaan belum diatur.' }}
                </p>

                @if($profil)
                <ul class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <li class="flex items-center gap-3 text-sm text-zinc-300 bg-zinc-800/50 p-3 rounded-xl border border-zinc-700">
                        <i class="fas fa-map-marker-alt text-orange-500"></i>
                        {{ $profil->alamat }}
                    </li>
                    <li class="flex items-center gap-3 text-sm text-zinc-300 bg-zinc-800/50 p-3 rounded-xl border border-zinc-700">
                        <i class="fas fa-envelope text-orange-500"></i>
                        {{ $profil->email }}
                    </li>
                    <li class="flex items-center gap-3 text-sm text-zinc-300 bg-zinc-800/50 p-3 rounded-xl border border-zinc-700">
                        <i class="fab fa-whatsapp text-green-500 text-lg"></i>
                        {{ $profil->nomor_telepon }}
                    </li>
                </ul>
                @endif
            </div>
        </div>
    </div>
</header>

<div class="max-w-6xl mx-auto px-6 mb-12">
    <hr class="border-zinc-800">
</div>

<section class="max-w-6xl mx-auto px-6 pb-24">
    <div class="flex items-center gap-4 mb-12">
        <h2 class="text-3xl font-black text-white uppercase tracking-wider">Katalog Layanan Kami</h2>
        <div class="h-1 flex-grow bg-zinc-900 rounded-full overflow-hidden">
            <div class="w-24 h-full bg-orange-600"></div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($layanans as $layanan)
            <div class="group bg-zinc-900 border border-zinc-800 p-8 rounded-[24px] hover:border-orange-600/50 transition-all duration-300 shadow-lg relative overflow-hidden">
                <div class="absolute -right-4 -top-4 text-zinc-800 text-8xl opacity-10 rotate-12 group-hover:text-orange-600/20 transition-colors">
                    <i class="fas fa-tags"></i>
                </div>

                <div class="relative z-10">
                    <div class="mb-4">
                        <span class="text-[10px] font-black uppercase tracking-[0.2em] text-orange-500 bg-orange-500/10 px-2 py-1 rounded">
                            {{ $layanan->tipe_layanan }}
                        </span>
                        <h3 class="text-2xl font-bold text-white mt-2">{{ $layanan->nama_layanan }}</h3>
                    </div>
                    
                    <p class="text-zinc-500 text-sm leading-relaxed mb-8 line-clamp-3">
                        {{ $layanan->deskripsi }}
                    </p>

                    <div class="flex items-center justify-between pt-6 border-t border-zinc-800">
                        <div class="flex flex-col">
                            <span class="text-[10px] text-zinc-500 uppercase font-bold">Harga Mulai</span>
                            <span class="text-xl font-black text-white italic">
                                Rp {{ number_format($layanan->harga, 0, ',', '.') }}
                            </span>
                        </div>
                        
                        <button class="bg-zinc-800 hover:bg-orange-600 text-white w-12 h-12 rounded-xl flex items-center justify-center transition-all group-hover:shadow-lg group-hover:shadow-orange-600/20">
                            <i class="fas fa-shopping-cart"></i>
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
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

<footer class="bg-zinc-900 text-zinc-500 py-12 text-center border-t border-zinc-800">
    <div class="max-w-6xl mx-auto px-6">
        <div class="text-orange-500 font-black text-xl mb-4 italic">DANTIE STICKER.</div>
        <p class="text-xs uppercase tracking-[0.3em]">&copy; 2026 {{ $profil->nama_perusahaan ?? 'Perusahaan' }}. All Rights Reserved.</p>
    </div>
</footer>

</body>
</html>