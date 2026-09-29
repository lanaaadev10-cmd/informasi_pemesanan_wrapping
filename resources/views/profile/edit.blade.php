@extends('layouts.dashboard-customer')

@section('title', 'Pengaturan Akun')

@section('content')
<div class="max-w-5xl mx-auto py-12 px-6">
    <div class="mb-16" data-aos="fade-down">
        <h1 class="text-4xl md:text-5xl font-black text-white tracking-tight">Pengaturan <span class="text-racing-orangeLight italic">Akun.</span></h1>
        <p class="text-white/70 mt-2 font-medium italic">Kelola identitas dan keamanan akses akun Anda secara terpusat.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
        <div class="lg:col-span-4 space-y-4" data-aos="fade-right">
            <div class="soft-card p-6 border border-white/5 bg-racing-card text-white shadow-2xl overflow-hidden relative rounded-3xl">
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-racing-orange/20 blur-3xl rounded-full"></div>
                <div class="relative z-10 flex flex-col items-center text-center py-6">
                    <div class="w-24 h-24 rounded-3xl bg-racing-orange flex items-center justify-center text-4xl mb-6 shadow-xl shadow-racing-orange/30 font-black">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <h3 class="text-2xl font-black italic tracking-tight">{{ Auth::user()->name }}</h3>
                    <p class="text-gray-400 text-xs font-medium italic mt-1">{{ Auth::user()->email }}</p>
                    <span class="mt-6 px-4 py-1.5 bg-white/10 rounded-full text-[10px] font-black uppercase tracking-widest text-racing-orange border border-white/5">
                        Member
                    </span>
                </div>
            </div>

            <div class="space-y-2">
                <a href="#profil" class="flex items-center gap-4 p-5 rounded-2xl bg-racing-card text-gray-200 border border-white/10 hover:border-racing-orange/40 hover:text-white transition-all font-bold text-sm group">
                    <i class="ph-bold ph-user-circle text-xl text-racing-orange"></i> Informasi Profil
                    <i class="ph ph-caret-right ml-auto text-gray-500 group-hover:text-racing-orange transition-all"></i>
                </a>
                <a href="#password" class="flex items-center gap-4 p-5 rounded-2xl bg-racing-card text-gray-200 border border-white/10 hover:border-racing-orange/40 hover:text-white transition-all font-bold text-sm group">
                    <i class="ph-bold ph-lock-key text-xl text-racing-orange"></i> Keamanan Sandi
                    <i class="ph ph-caret-right ml-auto text-gray-500 group-hover:text-racing-orange transition-all"></i>
                </a>
                <a href="#hapus" class="flex items-center gap-4 p-5 rounded-2xl bg-racing-card text-red-400 border border-red-500/20 hover:bg-red-500/10 hover:border-red-500/40 transition-all font-bold text-sm group">
                    <i class="ph-bold ph-trash text-xl"></i> Hapus Akun
                </a>
            </div>
        </div>

        <div class="lg:col-span-8 space-y-12">
            <section id="profil" class="form-dark soft-card p-8 md:p-12 relative overflow-hidden bg-racing-card border border-white/10 rounded-3xl" data-aos="fade-up">
                <div class="absolute top-0 right-0 w-64 h-64 bg-racing-orange/10 blur-3xl rounded-full"></div>
                <h3 class="text-2xl font-black text-white mb-8 flex items-center gap-4">
                    <i class="ph-fill ph-user-circle text-racing-orange bg-white/5 p-3.5 rounded-2xl border border-white/10"></i> Informasi Profil
                </h3>
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </section>

            <section id="password" class="form-dark soft-card p-8 md:p-12 relative overflow-hidden bg-racing-card border border-white/10 rounded-3xl" data-aos="fade-up">
                <div class="absolute top-0 right-0 w-64 h-64 bg-racing-orange/10 blur-3xl rounded-full"></div>
                <h3 class="text-2xl font-black text-white mb-8 flex items-center gap-4">
                    <i class="ph-fill ph-lock-key text-racing-orange bg-white/5 p-3.5 rounded-2xl border border-white/10"></i> Keamanan Akun
                </h3>
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </section>

            <section id="hapus" class="form-dark p-8 md:p-12 rounded-3xl border border-red-500/20 bg-red-950/20" data-aos="fade-up">
                <h3 class="text-2xl font-black text-red-400 mb-8 flex items-center gap-4">
                    <i class="ph-fill ph-warning-circle text-red-400 bg-red-500/10 p-3.5 rounded-2xl border border-red-500/20"></i> Zona Berbahaya
                </h3>
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
