@extends('layouts.dashboard_customer')

@section('title', 'Pengaturan Akun')

@section('content')
<div class="max-w-5xl mx-auto py-12 px-6">
    <div class="mb-16" data-aos="fade-down">
        <h1 class="text-4xl md:text-5xl font-black text-white tracking-tight">Pengaturan <span class="text-[#f2994a] italic">Akun.</span></h1>
        <p class="text-white/70 mt-2 font-medium italic">Kelola identitas dan keamanan akses akun Anda secara terpusat.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
        <div class="lg:col-span-4 space-y-4" data-aos="fade-right">
            <div class="bg-[#121212] border border-[#1f2937] rounded-3xl p-6 border-none bg-gray-900 text-white shadow-2xl overflow-hidden relative">
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-orange-600/20 blur-3xl rounded-full"></div>
                <div class="relative z-10 flex flex-col items-center text-center py-6">
                    <div class="w-24 h-24 rounded-[2.5rem] bg-orange-600 flex items-center justify-center text-4xl mb-6 shadow-xl shadow-orange-900/40">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <h3 class="text-2xl font-black italic tracking-tight">{{ Auth::user()->name }}</h3>
                    <p class="text-gray-400 text-xs font-medium italic mt-1">{{ Auth::user()->email }}</p>
                    <span class="mt-6 px-4 py-1.5 bg-white/10 rounded-full text-[10px] font-black uppercase tracking-widest text-orange-500 border border-white/5">
                        Premium Member
                    </span>
                </div>
            </div>

            <div class="space-y-2">
                <a href="#profil" class="flex items-center gap-4 p-5 rounded-2xl bg-white text-gray-900 border border-gray-100 hover:border-orange-500/30 transition-all font-black text-sm group">
                    <i class="ph-bold ph-user-circle text-xl text-orange-600"></i> Informasi Profil
                    <i class="ph ph-caret-right ml-auto text-gray-300 group-hover:text-orange-600 transition-all"></i>
                </a>
                <a href="#password" class="flex items-center gap-4 p-5 rounded-2xl bg-white text-gray-900 border border-gray-100 hover:border-orange-500/30 transition-all font-black text-sm group">
                    <i class="ph-bold ph-lock-key text-xl text-orange-600"></i> Keamanan Sandi
                    <i class="ph ph-caret-right ml-auto text-gray-300 group-hover:text-orange-600 transition-all"></i>
                </a>
                <a href="#hapus" class="flex items-center gap-4 p-5 rounded-2xl bg-white text-red-600 border border-red-50 hover:bg-red-50 transition-all font-black text-sm group">
                    <i class="ph-bold ph-trash text-xl"></i> Hapus Akun
                </a>
            </div>
        </div>

        <div class="lg:col-span-8 space-y-12">
            <section id="profil" class="bg-[#121212] border border-[#1f2937] rounded-3xl shadow-[0_4px_30px_rgba(0,0,0,0.03)] p-10 md:p-12 relative overflow-hidden bg-[#151515] border border-white/5" data-aos="fade-up">
                <div class="absolute top-0 right-0 w-64 h-64 bg-orange-600/5 blur-[100px] rounded-full"></div>
                <h3 class="text-2xl font-black text-white mb-10 flex items-center gap-4">
                    <i class="ph-fill ph-user-circle text-orange-600 bg-white/10 p-4 rounded-2xl"></i> Informasi Profil
                </h3>
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </section>

            <section id="password" class="bg-[#121212] border border-[#1f2937] rounded-3xl shadow-[0_4px_30px_rgba(0,0,0,0.03)] p-10 md:p-12 relative overflow-hidden bg-[#151515] border border-white/5" data-aos="fade-up">
                <div class="absolute top-0 right-0 w-64 h-64 bg-orange-600/5 blur-[100px] rounded-full"></div>
                <h3 class="text-2xl font-black text-white mb-10 flex items-center gap-4">
                    <i class="ph-fill ph-lock-key text-orange-600 bg-white/10 p-4 rounded-2xl"></i> Keamanan Akun
                </h3>
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </section>

            <section id="hapus" class="p-10 md:p-12 rounded-[40px] border-2 border-dashed border-red-500/20 bg-red-950/20" data-aos="fade-up">
                <h3 class="text-2xl font-black text-red-400 mb-10 flex items-center gap-4">
                    <i class="ph-fill ph-warning-circle text-red-400 bg-red-500/10 p-4 rounded-2xl"></i> Zona Berbahaya
                </h3>
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </section>
        </div>
    </div>
</div>


@endsection
