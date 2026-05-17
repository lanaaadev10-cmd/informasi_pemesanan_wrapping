<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - {{ config('app.name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script defer src="https://unpkg.com/@phosphor-icons/web"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .bg-auth-reg {
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('{{ ($profil?->register_image) ? \Illuminate\Support\Facades\Storage::url($profil->register_image) : "https://images.unsplash.com/photo-1550009158-9ebf69173e03?q=80&w=2101&auto=format&fit=crop" }}');
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body class="bg-white">
    <div class="flex flex-col md:flex-row min-h-screen">
        <!-- Sisi Kiri: Visual & Branding (Sesuai Konsep Gambar) -->
        <div class="hidden md:flex md:w-1/2 bg-auth-reg p-16 flex-col justify-between text-white relative">
            <div class="z-10">
                <a href="/" class="flex items-center gap-3 text-2xl font-bold tracking-tighter uppercase">
                    <div class="w-10 h-10 bg-orange-600 rounded-xl flex items-center justify-center">
                        <i class="ph-bold ph-sketch-logo"></i>
                    </div>
                    {{ $profil->nama_perusahaan ?? 'ALTRA' }}
                </a>
            </div>
            
            <div class="z-10">
                <h1 class="text-6xl font-black leading-tight mb-6">
                    {!! ($profil?->register_title) ? nl2br(e($profil->register_title)) : 'Create Your <br> <span class="text-orange-500">Journey.</span>' !!}
                </h1>
                <p class="text-xl text-gray-300 max-w-md leading-relaxed">
                    {{ $profil->register_subtitle ?? 'Bergabunglah dengan ribuan pelanggan puas kami dan dapatkan layanan wrapping terbaik untuk aset berharga Anda.' }}
                </p>
            </div>

            <div class="z-10 flex items-center gap-4 text-sm font-medium text-gray-400">
                <span>{!! $profil->footer_copyright ? e($profil->footer_copyright) : '&copy; ' . date('Y') . ' ' . ($profil->nama_perusahaan ?? 'Dantie Sticker') !!}</span>
                <span class="w-1 h-1 bg-gray-600 rounded-full"></span>
                <span>{{ $profil->auth_badge ?? 'Join the Community' }}</span>
            </div>
        </div>

        <!-- Sisi Kanan: Form Register -->
        <div class="w-full md:w-1/2 flex items-center justify-center p-8 md:px-16 py-12 bg-gray-50 overflow-y-auto">
            <div class="w-full max-w-md">
                <div class="mb-10 md:hidden flex justify-center">
                    <a href="/" class="text-2xl font-black text-orange-600 uppercase tracking-tighter italic">{{ $profil->nama_perusahaan ?? 'ALTRA' }}</a>
                </div>

                <div class="mb-8">
                    <h2 class="text-4xl font-extrabold text-gray-900 mb-2 tracking-tight">{{ $profil?->register_form_title ?? 'Get Started!' }}</h2>
                    <p class="text-gray-500 font-medium italic">{{ $profil?->register_form_subtitle ?? '"Mulai pengalaman premium Anda bersama kami."' }}</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Full Name</label>
                        <div class="relative">
                            <i class="ph ph-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xl"></i>
                            <input type="text" name="name" :value="old('name')" required autofocus 
                                   class="w-full pl-12 pr-4 py-3.5 bg-white border border-gray-200 rounded-2xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all outline-none"
                                   placeholder="Your full name">
                        </div>
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Email Address</label>
                        <div class="relative">
                            <i class="ph ph-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xl"></i>
                            <input type="email" name="email" :value="old('email')" required 
                                   class="w-full pl-12 pr-4 py-3.5 bg-white border border-gray-200 rounded-2xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all outline-none"
                                   placeholder="name@company.com">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Password</label>
                        <div class="relative">
                            <i class="ph ph-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xl"></i>
                            <input type="password" name="password" required 
                                   class="w-full pl-12 pr-4 py-3.5 bg-white border border-gray-200 rounded-2xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all outline-none"
                                   placeholder="Create a strong password">
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Confirm Password</label>
                        <div class="relative">
                            <i class="ph ph-shield-check absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xl"></i>
                            <input type="password" name="password_confirmation" required 
                                   class="w-full pl-12 pr-4 py-3.5 bg-white border border-gray-200 rounded-2xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all outline-none"
                                   placeholder="Repeat your password">
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <button type="submit" class="w-full py-4 bg-orange-600 text-white rounded-2xl font-bold text-lg hover:bg-orange-700 transition-all shadow-xl shadow-orange-200">
                        Create Account
                    </button>

                    <p class="text-center text-gray-500 font-medium">
                        Already have an account? 
                        <a href="{{ route('login') }}" class="text-orange-600 font-bold hover:underline">Sign In</a>
                    </p>
                </form>

                <div class="mt-8 pt-8 border-t border-gray-200 flex justify-center">
                    <a href="/" class="text-sm font-bold text-gray-400 hover:text-gray-600 flex items-center gap-2">
                        <i class="ph ph-arrow-left"></i>
                        Back to Home
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
