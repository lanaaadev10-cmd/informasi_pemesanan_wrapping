<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - {{ config('app.name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .bg-auth {
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('{{ ($profil?->login_image) ? \Illuminate\Support\Facades\Storage::url($profil->login_image) : "https://images.unsplash.com/photo-1614850523459-c2f4c699c52e?q=80&w=2070&auto=format&fit=crop" }}');
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body class="bg-white">
    <div class="flex flex-col md:flex-row min-h-screen">
        <!-- Sisi Kiri: Visual & Branding -->
        <div class="hidden md:flex md:w-1/2 bg-auth p-16 flex-col justify-between text-white relative">
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
                    {!! ($profil?->login_title) ? nl2br(e($profil->login_title)) : 'Level Up Your <br> <span class="text-orange-500">Business.</span>' !!}
                </h1>
                <p class="text-xl text-gray-300 max-w-md leading-relaxed">
                    {{ $profil->login_subtitle ?? 'Masuk ke member area untuk mengelola pesanan dan melihat katalog wrapping terbaik kami.' }}
                </p>
            </div>

            <div class="z-10 flex items-center gap-4 text-sm font-medium text-gray-400">
                <span>{!! $profil->footer_copyright ? e($profil->footer_copyright) : '&copy; ' . date('Y') . ' ' . ($profil->nama_perusahaan ?? 'Dantie Sticker') !!}</span>
                <span class="w-1 h-1 bg-gray-600 rounded-full"></span>
                <span>{{ $profil->auth_badge ?? 'Premium Quality' }}</span>
            </div>
        </div>

        <!-- Sisi Kanan: Form Login -->
        <div class="w-full md:w-1/2 flex items-center justify-center p-8 md:p-16 bg-gray-50">
            <div class="w-full max-w-md">
                <div class="mb-10 md:hidden flex justify-center">
                    <a href="/" class="text-2xl font-black text-orange-600 uppercase tracking-tighter italic">{{ $profil->nama_perusahaan ?? 'ALTRA' }}</a>
                </div>

                <div class="mb-12">
                    <h2 class="text-4xl font-extrabold text-gray-900 mb-2 tracking-tight">{{ $profil?->login_form_title ?? 'Welcome Back!' }}</h2>
                    <p class="text-gray-500 font-medium">{{ $profil?->login_form_subtitle ?? 'Silakan masuk untuk melanjutkan.' }}</p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Email Address</label>
                        <div class="relative">
                            <i class="ph ph-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xl"></i>
                            <input type="email" name="email" :value="old('email')" required autofocus 
                                   class="w-full pl-12 pr-4 py-4 bg-white border border-gray-200 rounded-2xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all outline-none"
                                   placeholder="name@company.com">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div>
                        <div class="flex justify-between mb-2">
                            <label class="block text-sm font-bold text-gray-700">Password</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-sm font-bold text-orange-600 hover:text-orange-700">Forgot?</a>
                            @endif
                        </div>
                        <div class="relative">
                            <i class="ph ph-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xl"></i>
                            <input type="password" name="password" required 
                                   class="w-full pl-12 pr-4 py-4 bg-white border border-gray-200 rounded-2xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all outline-none"
                                   placeholder="Min. 8 characters">
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <input type="checkbox" name="remember" id="remember_me" class="rounded border-gray-300 text-orange-600 focus:ring-orange-500">
                        <label for="remember_me" class="ml-2 text-sm font-medium text-gray-600">Remember this device</label>
                    </div>

                    <button type="submit" class="w-full py-4 bg-gray-900 text-white rounded-2xl font-bold text-lg hover:bg-black transition-all shadow-xl shadow-gray-200">
                        Sign In
                    </button>

                    <p class="text-center text-gray-500 font-medium">
                        Don't have an account? 
                        <a href="{{ route('register') }}" class="text-orange-600 font-bold hover:underline">Create account</a>
                    </p>
                </form>

                <div class="mt-12 pt-8 border-t border-gray-200 flex justify-center gap-6">
                    <a href="/" class="text-sm font-bold text-gray-400 hover:text-gray-600 flex items-center gap-2">
                        <i class="ph ph-arrow-left"></i>
                        Back to Website
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
