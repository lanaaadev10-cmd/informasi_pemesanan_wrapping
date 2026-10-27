@extends('layouts.dashboard-customer')

@section('title', 'Pengaturan Akun')

@section('content')
<div class="max-w-6xl mx-auto py-4 sm:py-8 space-y-8 relative overflow-hidden">
    {{-- Header Halaman --}}
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 border-b border-slate-200/80 pb-6">
        <div>
            <div class="flex items-center gap-2 mb-1.5">
                <span class="w-2 h-2 rounded-full bg-[#FF6B00]"></span>
                <span class="text-xs font-montserrat font-bold text-[#FF6B00] uppercase tracking-wider">Manajemen Pengguna</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-audiowide font-bold text-slate-900 tracking-tight">
                Pengaturan <span class="text-[#FF6B00]">Akun</span>
            </h1>
            <p class="text-xs sm:text-sm font-questrial text-slate-500 mt-1">
                Kelola informasi identitas diri, kata sandi, dan keamanan akses akun Anda secara terpusat.
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        {{-- Kolom Kiri: Kartu Profil Singkat & Menu Cepat --}}
        <div class="lg:col-span-4 space-y-5">
            {{-- Kartu Ringkasan Pengguna --}}
            <div class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-7 shadow-sm text-center relative overflow-hidden">
                <div class="absolute -top-12 -right-12 w-32 h-32 bg-orange-500/10 blur-2xl rounded-full pointer-events-none"></div>
                
                <div class="relative z-10 flex flex-col items-center">
                    <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl bg-orange-50 border border-orange-200 text-[#FF6B00] flex items-center justify-center text-3xl sm:text-4xl font-audiowide font-bold mb-4 shadow-sm">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <h3 class="text-lg sm:text-xl font-audiowide font-bold text-slate-900 tracking-tight truncate max-w-full">
                        {{ Auth::user()->name }}
                    </h3>
                    <p class="text-xs font-questrial text-slate-500 mt-1 truncate max-w-full">
                        {{ Auth::user()->email }}
                    </p>
                    <span class="mt-4 px-3.5 py-1 bg-orange-100 text-[#FF6B00] rounded-full text-[10px] font-montserrat font-extrabold uppercase tracking-widest border border-orange-200/80">
                        Pelanggan Aktif
                    </span>
                </div>
            </div>

            {{-- Pintasan Navigasi --}}
            <div class="bg-white border border-slate-200/90 rounded-3xl p-3 shadow-sm space-y-1 font-montserrat text-xs">
                <a href="#profil" class="flex items-center gap-3.5 p-3.5 rounded-2xl text-slate-700 hover:text-[#FF6B00] hover:bg-orange-50/50 transition-all font-bold group">
                    <div class="w-8 h-8 rounded-xl bg-orange-50 border border-orange-200 text-[#FF6B00] flex items-center justify-center shrink-0">
                        <i class="ph-bold ph-user-circle text-base"></i>
                    </div>
                    <span>Informasi Profil</span>
                    <i class="ph-bold ph-caret-right ml-auto text-slate-400 group-hover:text-[#FF6B00] transition-colors"></i>
                </a>
                <a href="#password" class="flex items-center gap-3.5 p-3.5 rounded-2xl text-slate-700 hover:text-[#FF6B00] hover:bg-orange-50/50 transition-all font-bold group">
                    <div class="w-8 h-8 rounded-xl bg-slate-100 border border-slate-200 text-slate-600 flex items-center justify-center shrink-0 group-hover:bg-orange-50 group-hover:border-orange-200 group-hover:text-[#FF6B00] transition-colors">
                        <i class="ph-bold ph-lock-key text-base"></i>
                    </div>
                    <span>Keamanan Sandi</span>
                    <i class="ph-bold ph-caret-right ml-auto text-slate-400 group-hover:text-[#FF6B00] transition-colors"></i>
                </a>
                <a href="#hapus" class="flex items-center gap-3.5 p-3.5 rounded-2xl text-rose-600 hover:bg-rose-50 transition-all font-bold group">
                    <div class="w-8 h-8 rounded-xl bg-rose-50 border border-rose-200 text-rose-600 flex items-center justify-center shrink-0">
                        <i class="ph-bold ph-trash text-base"></i>
                    </div>
                    <span>Hapus Akun</span>
                    <i class="ph-bold ph-caret-right ml-auto text-rose-400 group-hover:text-rose-600 transition-colors"></i>
                </a>
            </div>
        </div>

        {{-- Kolom Kanan: Form Sections --}}
        <div class="lg:col-span-8 space-y-8">
            {{-- 1. INFORMASI PROFIL --}}
            <section id="profil" class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-8 shadow-sm relative overflow-hidden">
                <div class="flex items-center gap-3.5 mb-6 pb-4 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-2xl bg-orange-50 border border-orange-200 text-[#FF6B00] flex items-center justify-center shrink-0 shadow-sm">
                        <i class="ph-bold ph-user-circle text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-base sm:text-lg font-audiowide font-bold text-slate-900">
                            Informasi Profil
                        </h2>
                        <p class="text-xs font-questrial text-slate-500 mt-0.5">
                            Perbarui nama tampilan dan alamat email akun Anda.
                        </p>
                    </div>
                </div>

                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </section>

            {{-- 2. KEAMANAN SANDI --}}
            <section id="password" class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-8 shadow-sm relative overflow-hidden">
                <div class="flex items-center gap-3.5 mb-6 pb-4 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-2xl bg-orange-50 border border-orange-200 text-[#FF6B00] flex items-center justify-center shrink-0 shadow-sm">
                        <i class="ph-bold ph-lock-key text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-base sm:text-lg font-audiowide font-bold text-slate-900">
                            Keamanan Sandi
                        </h2>
                        <p class="text-xs font-questrial text-slate-500 mt-0.5">
                            Pastikan akun Anda menggunakan kata sandi yang aman dan tidak mudah ditebak.
                        </p>
                    </div>
                </div>

                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </section>

            {{-- 3. ZONA BERBAHAYA (HAPUS AKUN) --}}
            <section id="hapus" class="bg-rose-50/40 border border-rose-200/90 rounded-3xl p-6 sm:p-8 shadow-sm relative overflow-hidden">
                <div class="flex items-center gap-3.5 mb-6 pb-4 border-b border-rose-200/70">
                    <div class="w-10 h-10 rounded-2xl bg-rose-100 border border-rose-200 text-rose-600 flex items-center justify-center shrink-0 shadow-sm">
                        <i class="ph-bold ph-warning-circle text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-base sm:text-lg font-audiowide font-bold text-rose-900">
                            Zona Berbahaya
                        </h2>
                        <p class="text-xs font-questrial text-rose-700/80 mt-0.5">
                            Tindakan ini tidak dapat dibatalkan. Seluruh data transaksi dan riwayat akan dihapus.
                        </p>
                    </div>
                </div>

                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
