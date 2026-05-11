<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - {{ $profil->nama_perusahaan ?? 'Dantie Sticker' }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .glass {
            background: rgba(24, 24, 27, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        .orange-glow {
            box-shadow: 0 0 40px -10px rgba(234, 88, 12, 0.3);
        }
    </style>
</head>
<body class="bg-zinc-950 text-zinc-300 antialiased overflow-x-hidden">

    <!-- Hero / Background Glow -->
    <div class="fixed top-0 left-0 w-full h-full pointer-events-none z-0">
        <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] bg-orange-600/10 blur-[120px] rounded-full"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] bg-orange-900/5 blur-[120px] rounded-full"></div>
    </div>

    <!-- Navigation -->
    <nav class="sticky top-0 z-50 glass border-b border-white/5">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <a href="/" class="flex items-center gap-3 group">
                <div class="w-10 h-10 bg-orange-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-orange-900/20 group-hover:rotate-12 transition-transform">
                    <i class="fas fa-layer-group"></i>
                </div>
                <span class="text-white font-black text-xl tracking-tighter uppercase">Dantie Sticker</span>
            </a>
            <div class="flex items-center gap-6">
                <a href="/" class="text-zinc-400 hover:text-orange-500 transition-colors font-medium">Beranda</a>
                <a href="{{ route('login') }}" class="bg-white/5 hover:bg-white/10 text-white px-5 py-2.5 rounded-xl font-bold transition-all border border-white/10">Masuk</a>
            </div>
        </div>
    </nav>

    <main class="relative z-10 max-w-7xl mx-auto px-6 py-16 lg:py-24">
        
        <!-- Profile Header Section -->
        <div class="grid lg:grid-cols-12 gap-12 items-center mb-24">
            <div class="lg:col-span-7 space-y-8">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-orange-500/10 border border-orange-500/20 text-orange-500 text-sm font-bold uppercase tracking-widest">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-orange-500"></span>
                    </span>
                    Identity & Excellence
                </div>
                
                <h1 class="text-5xl lg:text-8xl font-black text-white leading-[0.9] tracking-tighter">
                    {{ $profil->nama_perusahaan ?? 'Premium Sticker' }}
                </h1>
                
                <p class="text-xl lg:text-2xl text-zinc-400 leading-relaxed max-w-2xl font-light">
                    {{ $profil->deskripsi ?? 'Dedikasi kami adalah memberikan kualitas stiker terbaik dengan presisi tinggi dan desain inovatif.' }}
                </p>

                <div class="flex flex-wrap gap-4">
                    <div class="px-6 py-4 rounded-2xl glass border border-orange-500/20 flex flex-col gap-1">
                        <span class="text-orange-500 text-xs font-bold uppercase tracking-widest">Terpercaya Sejak</span>
                        <span class="text-white text-xl font-black italic">2018</span>
                    </div>
                    <div class="px-6 py-4 rounded-2xl glass border border-orange-500/20 flex flex-col gap-1">
                        <span class="text-orange-500 text-xs font-bold uppercase tracking-widest">Kepuasan Pelanggan</span>
                        <span class="text-white text-xl font-black italic">99.9%</span>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-5 flex justify-center lg:justify-end">
                <div class="relative w-72 h-72 lg:w-96 lg:h-96">
                    <div class="absolute inset-0 bg-orange-600/20 rounded-[60px] blur-3xl animate-pulse"></div>
                    <div class="relative w-full h-full glass rounded-[60px] p-12 border border-white/10 flex items-center justify-center shadow-2xl orange-glow overflow-hidden">
                        @if($profil && $profil->logo)
                            <img src="{{ asset('storage/' . $profil->logo) }}" alt="Logo" class="w-full h-full object-contain">
                        @else
                            <i class="fas fa-gem text-[120px] text-zinc-800"></i>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Cards Section -->
        <div class="grid md:grid-cols-3 gap-8 mb-24">
            <!-- Address -->
            <div class="group glass p-8 rounded-[40px] hover:bg-zinc-900 transition-all duration-500 border border-white/5 hover:border-orange-500/30">
                <div class="w-14 h-14 bg-orange-600/20 rounded-2xl flex items-center justify-center text-orange-500 mb-6 group-hover:scale-110 group-hover:bg-orange-600 group-hover:text-white transition-all">
                    <i class="fas fa-map-marked-alt text-2xl"></i>
                </div>
                <h3 class="text-white font-bold text-xl mb-3">Lokasi Fisik</h3>
                <p class="text-zinc-500 leading-relaxed">{{ $profil->alamat ?? 'Alamat belum diatur' }}</p>
            </div>

            <!-- Contact -->
            <div class="group glass p-8 rounded-[40px] hover:bg-zinc-900 transition-all duration-500 border border-white/5 hover:border-orange-500/30">
                <div class="w-14 h-14 bg-orange-600/20 rounded-2xl flex items-center justify-center text-orange-500 mb-6 group-hover:scale-110 group-hover:bg-orange-600 group-hover:text-white transition-all">
                    <i class="fas fa-headset text-2xl"></i>
                </div>
                <h3 class="text-white font-bold text-xl mb-3">Hubungi Kami</h3>
                <div class="space-y-2">
                    <p class="text-zinc-500 flex items-center gap-2">
                        <i class="fas fa-envelope text-orange-600"></i>
                        {{ $profil->email ?? '-' }}
                    </p>
                    <p class="text-zinc-500 flex items-center gap-2">
                        <i class="fab fa-whatsapp text-green-500"></i>
                        {{ $profil->nomor_telepon ?? '-' }}
                    </p>
                </div>
            </div>

            <!-- Map URL -->
            @if($profil && $profil->maps_url)
            <div class="group glass p-8 rounded-[40px] hover:bg-zinc-900 transition-all duration-500 border border-white/5 hover:border-orange-500/30 flex flex-col justify-between">
                <div>
                    <div class="w-14 h-14 bg-orange-600/20 rounded-2xl flex items-center justify-center text-orange-500 mb-6 group-hover:scale-110 group-hover:bg-orange-600 group-hover:text-white transition-all">
                        <i class="fas fa-directions text-2xl"></i>
                    </div>
                    <h3 class="text-white font-bold text-xl mb-3">Petunjuk Arah</h3>
                    <p class="text-zinc-500 mb-6">Temukan kami dengan mudah melalui navigasi instan.</p>
                </div>
                <a href="{{ $profil->maps_url }}" target="_blank" class="w-full bg-orange-600 hover:bg-orange-700 text-white font-bold py-4 rounded-2xl flex items-center justify-center gap-3 transition-all shadow-lg shadow-orange-900/20">
                    <i class="fas fa-external-link-alt"></i>
                    Buka Google Maps
                </a>
            </div>
            @endif
        </div>

        <!-- Vision/Mission Style Section -->
        <div class="glass rounded-[60px] p-12 lg:p-20 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-orange-600/5 blur-[100px] rounded-full pointer-events-none"></div>
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div class="space-y-6">
                    <h2 class="text-4xl font-black text-white italic tracking-tighter">"Kreativitas Tanpa Batas, Kualitas Tanpa Kompromi."</h2>
                    <div class="w-24 h-1 bg-orange-600 rounded-full"></div>
                    <p class="text-zinc-400 text-lg leading-relaxed">
                        Sejak awal berdiri, kami percaya bahwa setiap kendaraan dan bisnis memiliki cerita unik. Melalui stiker berkualitas tinggi, kami membantu Anda menceritakan kisah tersebut dengan visual yang memukau.
                    </p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="aspect-square glass rounded-[40px] border border-white/5 p-8 flex flex-col justify-center items-center text-center gap-4 group hover:bg-orange-600 transition-all duration-500">
                        <i class="fas fa-shield-alt text-3xl text-orange-500 group-hover:text-white"></i>
                        <span class="text-white font-bold uppercase text-xs tracking-widest">Bahan Premium</span>
                    </div>
                    <div class="aspect-square glass rounded-[40px] border border-white/5 p-8 flex flex-col justify-center items-center text-center gap-4 group hover:bg-orange-600 transition-all duration-500">
                        <i class="fas fa-clock text-3xl text-orange-500 group-hover:text-white"></i>
                        <span class="text-white font-bold uppercase text-xs tracking-widest">Pengerjaan Cepat</span>
                    </div>
                    <div class="aspect-square glass rounded-[40px] border border-white/5 p-8 flex flex-col justify-center items-center text-center gap-4 group hover:bg-orange-600 transition-all duration-500">
                        <i class="fas fa-users text-3xl text-orange-500 group-hover:text-white"></i>
                        <span class="text-white font-bold uppercase text-xs tracking-widest">Tim Ahli</span>
                    </div>
                    <div class="aspect-square glass rounded-[40px] border border-white/5 p-8 flex flex-col justify-center items-center text-center gap-4 group hover:bg-orange-600 transition-all duration-500">
                        <i class="fas fa-hand-holding-usd text-3xl text-orange-500 group-hover:text-white"></i>
                        <span class="text-white font-bold uppercase text-xs tracking-widest">Harga Terbaik</span>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <footer class="py-12 border-t border-white/5 mt-24">
        <div class="max-w-7xl mx-auto px-6 text-center text-zinc-500 text-sm font-medium">
            &copy; 2026 {{ $profil->nama_perusahaan ?? 'Dantie Sticker' }}. Crafted for Perfection.
        </div>
    </footer>

</body>
</html>
