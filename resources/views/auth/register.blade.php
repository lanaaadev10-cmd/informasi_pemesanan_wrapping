<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - {{ config('app.name') }}</title>
    <!-- Premium Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: #070707;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 sm:p-6 md:p-12 relative overflow-hidden">
    <!-- Ambient Gold Glowing Backdrop -->
    <div class="absolute -top-40 -left-40 w-96 h-96 bg-[#f2994a]/10 rounded-full blur-[120px] pointer-events-none z-0"></div>
    <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-[#f2994a]/10 rounded-full blur-[120px] pointer-events-none z-0"></div>

    <!-- Main Container -->
    <div class="max-w-5xl w-full bg-[#111111] rounded-[32px] overflow-hidden border border-white/5 flex flex-col md:flex-row shadow-2xl relative z-10">
        <!-- 1. Left Visual Showcase Column -->
        <div class="w-full md:w-1/2 p-8 lg:p-12 flex flex-col justify-between text-white relative min-h-[400px] md:min-h-[620px] bg-cover bg-center" style="background-image: url('{{ asset('images/hero_car.png') }}');">
            <!-- Heavy Dark Gradient Overlays -->
            <div class="absolute inset-0 bg-gradient-to-b from-[#0a0a0a]/75 via-[#0a0a0a]/85 to-[#0a0a0a] z-0"></div>

            <!-- Header logo -->
            <div class="z-10">
                <a href="/" class="inline-flex items-center gap-2">
                    <span class="font-extrabold text-lg tracking-widest text-[#f2994a] uppercase">PREMIUM WRAP</span>
                </a>
            </div>

            <!-- Central Content & Testimonial -->
            <div class="z-10 space-y-8 mt-auto">
                <div class="space-y-3">
                    <p class="text-gray-300 text-xs sm:text-sm font-light leading-relaxed max-w-sm">
                        Precision in every layer. Transform your assets with the world's finest automotive films.
                    </p>
                    
                    <!-- Circular Avatars -->
                    <div class="flex items-center gap-3 pt-2">
                        <div class="flex -space-x-3">
                            <img class="w-7 h-7 rounded-full border border-black object-cover" src="https://images.unsplash.com/photo-1560250097-0b93528c311a?q=80&w=100&auto=format&fit=crop" alt="Client 1">
                            <img class="w-7 h-7 rounded-full border border-black object-cover" src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=100&auto=format&fit=crop" alt="Client 2">
                            <img class="w-7 h-7 rounded-full border border-black object-cover" src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?q=80&w=100&auto=format&fit=crop" alt="Client 3">
                        </div>
                        <span class="text-[10px] text-gray-400 font-medium">Bergabung dengan 500+ pemilik aset premium</span>
                    </div>
                </div>

                <!-- Gold Bordered Testimonial Block -->
                <div class="border border-[#f2994a]/30 rounded-2xl p-5 bg-[#0a0a0a]/40 backdrop-blur-sm max-w-md">
                    <p class="text-xs text-gray-200 leading-relaxed font-light italic">
                        "Hasil pengerjaan sangat presisi dan detail. Benar-benar standar kelas dunia untuk mobil koleksi saya."
                    </p>
                    <span class="text-[10px] text-[#f2994a] font-bold block mt-3 uppercase tracking-wider">— Robert O., Automotive Enthusiast</span>
                </div>
            </div>
        </div>

        <!-- 2. Right Form Column -->
        <div class="w-full md:w-1/2 p-8 lg:p-12 bg-[#0c0c0c] border-t md:border-t-0 md:border-l border-white/5 flex flex-col justify-center">
            <div class="space-y-8">
                <!-- Back to Home -->
                <a href="{{ url('/') }}" class="inline-flex items-center gap-1 text-[10px] text-gray-500 hover:text-[#f2994a] transition-colors font-medium">
                    <i class="ph ph-arrow-left text-sm"></i> {{ $profil->cta_kembali ?? 'Kembali ke Beranda' }}
                </a>
                <!-- Title Block -->
                <div class="space-y-2">
                    <h2 class="text-2xl font-bold text-white tracking-tight">Buat Akun Baru</h2>
                    <p class="text-xs text-gray-400 font-light leading-relaxed">
                        Lengkapi detail di bawah untuk memulai pemesanan layanan eksklusif kami.
                    </p>
                </div>

                <!-- Form Block -->
                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <!-- Full Name -->
                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $profil->form_nama_lengkap ?? 'Nama Lengkap' }}</label>
                        <div class="relative">
                            <i class="ph ph-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-lg"></i>
                            <input type="text" name="name" :value="old('name')" required autofocus 
                                   class="w-full pl-12 pr-4 py-3 bg-[#161616] border border-white/5 rounded-xl focus:ring-1 focus:ring-[#f2994a] focus:border-[#f2994a] text-white text-xs placeholder-gray-600 transition-all outline-none"
                                   placeholder="John Doe">
                        </div>
                        <x-input-error :messages="$errors->get('name')" class="mt-1.5 text-xs text-red-500 font-medium" />
                    </div>

                    <!-- Email Address -->
                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $profil->form_email ?? 'Email' }}</label>
                        <div class="relative">
                            <i class="ph ph-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-lg"></i>
                            <input type="email" name="email" :value="old('email')" required 
                                   class="w-full pl-12 pr-4 py-3 bg-[#161616] border border-white/5 rounded-xl focus:ring-1 focus:ring-[#f2994a] focus:border-[#f2994a] text-white text-xs placeholder-gray-600 transition-all outline-none"
                                   placeholder="email@premiumwrap.id">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-xs text-red-500 font-medium" />
                    </div>

                    <!-- Nomor Telepon (Visual Mockup Matching - Optional / Safe) -->
                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $profil->form_nomor_telepon ?? 'Nomor Telepon' }}</label>
                        <div class="relative">
                            <i class="ph ph-phone absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-lg"></i>
                            <input type="text" class="w-full pl-12 pr-4 py-3 bg-[#161616] border border-white/5 rounded-xl focus:ring-1 focus:ring-[#f2994a] focus:border-[#f2994a] text-white text-xs placeholder-gray-600 transition-all outline-none"
                                   placeholder="+62 812-3456-7890">
                        </div>
                    </div>

                    <!-- Password Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Password -->
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest">Kata Sandi</label>
                            <div class="relative">
                                <i class="ph ph-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-lg"></i>
                                <input type="password" name="password" required 
                                       class="w-full pl-12 pr-4 py-3 bg-[#161616] border border-white/5 rounded-xl focus:ring-1 focus:ring-[#f2994a] focus:border-[#f2994a] text-white text-xs placeholder-gray-600 transition-all outline-none"
                                       placeholder="••••••••">
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-xs text-red-500 font-medium" />
                        </div>

                        <!-- Confirm Password -->
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $profil->form_konfirmasi_password ?? 'Konfirmasi' }}</label>
                            <div class="relative">
                                <i class="ph ph-shield-check absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-lg"></i>
                                <input type="password" name="password_confirmation" required 
                                       class="w-full pl-12 pr-4 py-3 bg-[#161616] border border-white/5 rounded-xl focus:ring-1 focus:ring-[#f2994a] focus:border-[#f2994a] text-white text-xs placeholder-gray-600 transition-all outline-none"
                                       placeholder="••••••••">
                            </div>
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5 text-xs text-red-500 font-medium" />
                        </div>
                    </div>

                    <!-- Terms Checkbox -->
                    <div class="flex items-start gap-3 pt-2">
                        <input type="checkbox" id="terms" required class="mt-0.5 rounded bg-[#161616] border-white/5 text-[#f2994a] focus:ring-[#f2994a]">
                        <label for="terms" class="text-[10px] text-gray-400 leading-normal font-light">
                            {{ $profil->form_setuju_syarat ?? 'Saya menyetujui Syarat & Ketentuan serta Kebijakan Privasi Premium Wrap Management.' }}
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full py-3.5 bg-[#f2994a] text-black font-extrabold text-xs uppercase tracking-widest rounded-xl hover:bg-[#e28a44] transition-all flex items-center justify-center gap-2 active:scale-95 shadow-[0_4px_20px_rgba(242,153,74,0.3)]">
                        {{ $profil->cta_daftar_sekarang ?? 'Daftar Sekarang' }} <i class="ph-bold ph-arrow-right text-sm"></i>
                    </button>

                    <!-- Bottom Switch Link -->
                    <p class="text-center text-[10px] text-gray-400 font-medium pt-2">
                        Sudah memiliki akun? 
                        <a href="{{ route('login') }}" class="text-[#f2994a] font-bold hover:underline ml-1">Masuk di sini</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
