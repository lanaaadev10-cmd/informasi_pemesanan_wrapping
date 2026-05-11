<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $profil->nama_perusahaan ?? 'Profil Perusahaan' }} - Official Website</title>
    
    <!-- Tipografi Premium -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icon & Animasi -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #ffffff;
            color: #1a1a1a;
        }
        .text-gradient {
            background: linear-gradient(135deg, #ea580c 0%, #9a3412 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .btn-premium {
            background: linear-gradient(135deg, #ea580c 0%, #c2410c 100%);
            box-shadow: 0 10px 20px -5px rgba(234, 88, 12, 0.3);
        }
        .soft-card {
            background: #ffffff;
            border: 1px solid #f3f4f6;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.03);
            border-radius: 24px;
        }
        .footer-link:hover {
            color: #ea580c;
            padding-left: 4px;
        }
    </style>
</head>
<body class="antialiased overflow-x-hidden">

    <!-- Navbar -->
    <nav class="fixed top-0 w-full z-50 bg-white/80 backdrop-blur-md border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-6 h-20 flex justify-between items-center">
            <a href="/" class="flex items-center gap-3">
                @if($profil && $profil->logo)
                    <img src="{{ asset('storage/' . $profil->logo) }}" alt="Logo" class="h-10 w-auto">
                @else
                    <div class="w-10 h-10 bg-orange-600 rounded-xl flex items-center justify-center text-white">
                        <i class="ph-bold ph-sketch-logo text-2xl"></i>
                    </div>
                @endif
                <span class="font-bold text-xl tracking-tight">{{ $profil->nama_perusahaan ?? 'Dantie Sticker' }}</span>
            </a>
            
            <div class="hidden md:flex items-center gap-10">
                <a href="/" class="text-sm font-medium text-gray-600 hover:text-orange-600 transition-colors">Beranda</a>
                <a href="#tentang" class="text-sm font-medium text-gray-600 hover:text-orange-600 transition-colors">Tentang</a>
                <a href="#kontak" class="text-sm font-medium text-gray-600 hover:text-orange-600 transition-colors">Kontak</a>
                <a href="{{ route('login') }}" class="btn-premium text-white px-7 py-2.5 rounded-full text-sm font-bold transition-all hover:scale-105 active:scale-95">
                    Member Area
                </a>
            </div>
        </div>
    </nav>

    <main class="pt-32">
        
        <!-- Hero Section -->
        <section class="max-w-7xl mx-auto px-6 text-center mb-32" data-aos="fade-up">
            <span class="text-orange-600 font-bold text-xs uppercase tracking-[0.3em] mb-6 block">Premium Quality Assurance</span>
            <h1 class="text-5xl md:text-7xl font-extrabold text-gray-900 leading-[1.1] tracking-tight mb-8">
                Bring everyone together with <br> 
                <span class="text-gradient">solutions that scale.</span>
            </h1>
            <p class="max-w-2xl mx-auto text-gray-500 text-lg md:text-xl leading-relaxed mb-10">
                {{ $profil->deskripsi ?? 'Kami hadir dengan dedikasi penuh untuk memberikan hasil terbaik bagi bisnis dan kendaraan Anda melalui inovasi stiker yang tak tertandingi.' }}
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="#kontak" class="btn-premium text-white px-10 py-4 rounded-2xl font-bold text-lg hover:shadow-orange-600/40 transition-all">
                    Hubungi Sekarang
                </a>
                <a href="#tentang" class="bg-gray-50 hover:bg-gray-100 text-gray-700 px-10 py-4 rounded-2xl font-bold text-lg transition-all border border-gray-200">
                    Pelajari Lebih Lanjut
                </a>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="max-w-5xl mx-auto px-6 mb-32 grid grid-cols-2 md:grid-cols-4 gap-8" data-aos="fade-up" data-aos-delay="200">
            <div class="text-center">
                <h4 class="text-4xl font-bold text-gray-900 mb-2">5+</h4>
                <p class="text-gray-500 text-sm font-medium">Tahun Pengalaman</p>
            </div>
            <div class="text-center border-l border-gray-100">
                <h4 class="text-4xl font-bold text-gray-900 mb-2">1.2k</h4>
                <p class="text-gray-500 text-sm font-medium">Project Selesai</p>
            </div>
            <div class="text-center border-l border-gray-100">
                <h4 class="text-4xl font-bold text-gray-900 mb-2">99%</h4>
                <p class="text-gray-500 text-sm font-medium">Kepuasan Pelanggan</p>
            </div>
            <div class="text-center border-l border-gray-100">
                <h4 class="text-4xl font-bold text-gray-900 mb-2">24h</h4>
                <p class="text-gray-500 text-sm font-medium">Support Standby</p>
            </div>
        </section>

        <!-- Features (Like Image Cards) -->
        <section id="tentang" class="max-w-7xl mx-auto px-6 mb-32">
            <div class="grid md:grid-cols-2 gap-10 items-center">
                <div class="soft-card p-10 md:p-16 flex flex-col justify-center gap-8" data-aos="fade-right">
                    <div class="w-16 h-16 bg-orange-100 rounded-3xl flex items-center justify-center text-orange-600">
                        <i class="ph-bold ph-lightning text-3xl"></i>
                    </div>
                    <div>
                        <h2 class="text-3xl font-extrabold text-gray-900 mb-5">Pengerjaan Cepat & Presisi</h2>
                        <p class="text-gray-500 leading-relaxed text-lg">
                            Setiap pengerjaan kami melewati proses kontrol kualitas yang ketat untuk memastikan hasil akhir yang sempurna bagi Anda.
                        </p>
                    </div>
                    <ul class="space-y-4">
                        <li class="flex items-center gap-3 font-semibold text-gray-700">
                            <i class="ph-bold ph-check-circle text-green-500 text-xl"></i>
                            Bahan Vinyl Kualitas Dunia
                        </li>
                        <li class="flex items-center gap-3 font-semibold text-gray-700">
                            <i class="ph-bold ph-check-circle text-green-500 text-xl"></i>
                            Mesin Cetak Berteknologi Tinggi
                        </li>
                    </ul>
                </div>
                
                <div class="relative group" data-aos="fade-left">
                    <div class="absolute -inset-4 bg-orange-600/5 rounded-[40px] scale-95 group-hover:scale-100 transition-transform duration-700"></div>
                    <div class="relative rounded-[32px] overflow-hidden shadow-2xl">
                        <!-- Baru saya tambah: Placeholder gambar mockup premium -->
                        <img src="https://images.unsplash.com/photo-1614850523459-c2f4c699c52e?q=80&w=2070&auto=format&fit=crop" 
                             class="w-full h-[500px] object-cover group-hover:scale-110 transition-transform duration-1000" alt="Mockup">
                    </div>
                </div>
            </div>
        </section>

        <!-- Ready to streamline CTA Section (As in image) -->
        <section class="max-w-7xl mx-auto px-6 mb-32">
            <div class="btn-premium rounded-[48px] p-12 md:p-24 text-center text-white relative overflow-hidden" data-aos="zoom-in">
                <!-- Dekorasi -->
                <div class="absolute top-0 left-0 w-64 h-64 bg-white/10 blur-[80px] rounded-full"></div>
                <div class="absolute bottom-0 right-0 w-64 h-64 bg-orange-900/20 blur-[80px] rounded-full"></div>
                
                <div class="relative z-10">
                    <h2 class="text-4xl md:text-6xl font-extrabold mb-8">Ready to streamline <br> your workflow?</h2>
                    <p class="text-white/80 text-lg mb-12 max-w-xl mx-auto">
                        Mulai hari ini dengan solusi stiker terbaik kami. Hubungi tim kami untuk konsultasi gratis.
                    </p>
                    <div class="flex flex-col sm:flex-row justify-center gap-5">
                        <a href="https://wa.me/{{ $profil->nomor_telepon ?? '' }}" class="bg-white text-orange-600 px-10 py-4 rounded-2xl font-bold text-lg transition-all hover:bg-orange-50">
                            Get Started Now
                        </a>
                        <a href="#kontak" class="bg-white/10 text-white px-10 py-4 rounded-2xl font-bold text-lg transition-all border border-white/20 hover:bg-white/20">
                            Learn More
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer (Style as in image) -->
        <footer id="kontak" class="bg-gray-50 pt-24 pb-12 border-t border-gray-100">
            <div class="max-w-7xl mx-auto px-6">
                <div class="grid md:grid-cols-4 gap-16 mb-20">
                    <div class="col-span-1 md:col-span-1">
                        <a href="/" class="flex items-center gap-3 mb-8">
                            <span class="font-bold text-2xl tracking-tight text-gray-900">{{ $profil->nama_perusahaan ?? 'Dantie' }}</span>
                        </a>
                        <p class="text-gray-500 leading-relaxed text-sm">
                            Solusi branding dan wrapping terbaik dengan standar kualitas premium sejak 2018.
                        </p>
                    </div>
                    <div>
                        <h5 class="font-bold text-gray-900 mb-8">Halaman</h5>
                        <ul class="space-y-4 text-sm font-medium text-gray-500">
                            <li><a href="#" class="footer-link transition-all">Beranda</a></li>
                            <li><a href="#" class="footer-link transition-all">Katalog Layanan</a></li>
                            <li><a href="#" class="footer-link transition-all">Galeri Hasil Kerja</a></li>
                        </ul>
                    </div>
                    <div>
                        <h5 class="font-bold text-gray-900 mb-8">Perusahaan</h5>
                        <ul class="space-y-4 text-sm font-medium text-gray-500">
                            <li><a href="#" class="footer-link transition-all">Tentang Kami</a></li>
                            <li><a href="#" class="footer-link transition-all">Kebijakan Privasi</a></li>
                            <li><a href="#" class="footer-link transition-all">Syarat & Ketentuan</a></li>
                        </ul>
                    </div>
                    <div>
                        <h5 class="font-bold text-gray-900 mb-8">Hubungi Kami</h5>
                        <ul class="space-y-4 text-sm font-medium text-gray-500">
                            <li class="flex items-center gap-3">
                                <i class="ph ph-envelope-simple text-orange-600 text-lg"></i>
                                {{ $profil->email ?? 'email@anda.com' }}
                            </li>
                            <li class="flex items-center gap-3">
                                <i class="ph ph-phone text-orange-600 text-lg"></i>
                                {{ $profil->nomor_telepon ?? '-' }}
                            </li>
                            <li class="flex items-start gap-3 mt-4">
                                <i class="ph ph-map-pin text-orange-600 text-lg shrink-0"></i>
                                <span class="leading-relaxed">{{ $profil->alamat ?? 'Alamat Anda' }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <div class="pt-12 border-t border-gray-200 flex flex-col md:flex-row justify-between items-center gap-6 text-sm text-gray-400 font-medium">
                    <p>&copy; 2026 {{ $profil->nama_perusahaan ?? 'Dantie Sticker' }}. All rights reserved.</p>
                    <div class="flex gap-8">
                        <a href="#" class="hover:text-orange-600 transition-colors">Instagram</a>
                        <a href="#" class="hover:text-orange-600 transition-colors">TikTok</a>
                        <a href="#" class="hover:text-orange-600 transition-colors">Facebook</a>
                    </div>
                </div>
            </div>
        </footer>

    </main>

    <!-- AOS Script -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            once: true,
            easing: 'ease-out-cubic'
        });
    </script>
</body>
</html>
