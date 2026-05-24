<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - {{ config('app.name') }}</title>
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
    <div class="absolute -top-40 -right-40 w-96 h-96 bg-[#f2994a]/10 rounded-full blur-[120px] pointer-events-none z-0"></div>
    <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-[#f2994a]/10 rounded-full blur-[120px] pointer-events-none z-0"></div>

    <!-- Main Container (Inverted layout for distinction) -->
    <div class="max-w-5xl w-full bg-[#111111] rounded-[32px] overflow-hidden border border-white/5 flex flex-col md:flex-row shadow-2xl relative z-10">
        <!-- 1. Left Form Column -->
        <div class="w-full md:w-1/2 p-8 lg:p-12 bg-[#0c0c0c] flex flex-col justify-center order-2 md:order-1">
            <div class="space-y-8">
                <!-- Title Block -->
                <div class="space-y-2">
                    <h2 class="text-2xl font-bold text-white tracking-tight">Selamat Datang Kembali</h2>
                    <p class="text-xs text-gray-400 font-light leading-relaxed">
                        Silakan masuk ke akun Anda untuk mengelola pemesanan dan katalog layanan premium kami.
                    </p>
                </div>

                <!-- Session Status Indicator -->
                <x-auth-session-status class="mb-4 text-xs font-semibold text-green-500" :status="session('status')" />

                <!-- Form Block -->
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email Address -->
                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest">Email</label>
                        <div class="relative">
                            <i class="ph ph-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-lg"></i>
                            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                                   class="w-full pl-12 pr-4 py-3.5 bg-[#161616] border border-white/5 rounded-xl focus:ring-1 focus:ring-[#f2994a] focus:border-[#f2994a] text-white text-xs placeholder-gray-600 transition-all outline-none"
                                   placeholder="email@premiumwrap.id">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-xs text-red-500 font-medium" />
                    </div>

                    <!-- Password -->
                    <div class="space-y-1.5">
                        <div class="flex justify-between items-center">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest">Kata Sandi</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-[9px] font-bold text-[#f2994a] hover:underline uppercase tracking-wider">Lupa Sandi?</a>
                            @endif
                        </div>
                        <div class="relative">
                            <i class="ph ph-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-lg"></i>
                            <input type="password" name="password" required 
                                   class="w-full pl-12 pr-4 py-3.5 bg-[#161616] border border-white/5 rounded-xl focus:ring-1 focus:ring-[#f2994a] focus:border-[#f2994a] text-white text-xs placeholder-gray-600 transition-all outline-none"
                                   placeholder="••••••••">
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-xs text-red-500 font-medium" />
                    </div>

                    <!-- Remember Me checkbox & Remember Me label -->
                    <div class="flex items-center gap-3 pt-1">
                        <input type="checkbox" name="remember" id="remember_me" class="rounded bg-[#161616] border-white/5 text-[#f2994a] focus:ring-[#f2994a]">
                        <label for="remember_me" class="text-[10px] text-gray-400 font-light">
                            Ingat saya di perangkat ini
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full py-4 bg-[#f2994a] text-black font-extrabold text-xs uppercase tracking-widest rounded-xl hover:bg-[#e28a44] transition-all flex items-center justify-center gap-2 active:scale-95 shadow-[0_4px_20px_rgba(242,153,74,0.3)]">
                        Masuk Sekarang <i class="ph-bold ph-arrow-right text-sm"></i>
                    </button>

                    <!-- Bottom Switch Link -->
                    <p class="text-center text-[10px] text-gray-400 font-medium pt-2">
                        Belum memiliki akun? 
                        <a href="{{ route('register') }}" class="text-[#f2994a] font-bold hover:underline ml-1">Daftar di sini</a>
                    </p>
                </form>
            </div>
        </div>

        <!-- 2. Right Visual Column ( Tesla Model S or Ferrari Gold instead of lambo ) -->
        <div class="w-full md:w-1/2 p-8 lg:p-12 flex flex-col justify-between text-white relative min-h-[400px] md:min-h-[620px] bg-cover bg-center order-1 md:order-2" style="background-image: url('{{ asset('images/tesla_model_s.png') }}');">
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
                        Luxury is in the details. Protect your vehicle with the ultimate matte or glossy shield.
                    </p>
                    
                    <!-- Circular Avatars -->
                    <div class="flex items-center gap-3 pt-2">
                        <div class="flex -space-x-3">
                            <img class="w-7 h-7 rounded-full border border-black object-cover" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?q=80&w=100&auto=format&fit=crop" alt="Client 1">
                            <img class="w-7 h-7 rounded-full border border-black object-cover" src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=100&auto=format&fit=crop" alt="Client 2">
                            <img class="w-7 h-7 rounded-full border border-black object-cover" src="https://images.unsplash.com/photo-1560250097-0b93528c311a?q=80&w=100&auto=format&fit=crop" alt="Client 3">
                        </div>
                        <span class="text-[10px] text-gray-400 font-medium">Bergabung dengan 500+ pemilik aset premium</span>
                    </div>
                </div>

                <!-- Gold Bordered Testimonial Block -->
                <div class="border border-[#f2994a]/30 rounded-2xl p-5 bg-[#0a0a0a]/40 backdrop-blur-sm max-w-md">
                    <p class="text-xs text-gray-200 leading-relaxed font-light italic">
                        "Layanan car wrapping terbaik yang pernah saya temukan. Hasil kilapnya seperti cermin."
                    </p>
                    <span class="text-[10px] text-[#f2994a] font-bold block mt-3 uppercase tracking-wider">— Siska A., Luxury Sedan Owner</span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
