@extends('layouts.dashboard_customer')

@section('title', 'User Dashboard')

@section('content')
    <!-- Greeting & Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12" data-aos="fade-down">
                <div>
                    <h1 class="text-4xl font-black text-gray-900 tracking-tight mb-2">
                        Welcome back, <span class="text-orange-600">{{ Auth::user()->name }}</span>! 👋
                    </h1>
                    <p class="text-gray-500 font-medium italic">Senang melihat Anda kembali. Apa rencana Anda hari ini?</p>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('katalog.user') }}" class="btn-premium text-white px-8 py-3 rounded-2xl font-bold shadow-xl shadow-orange-100 flex items-center gap-2">
                        <i class="ph-bold ph-plus-circle"></i>
                        Buat Pesanan Baru
                    </a>
                </div>
            </div>

            <!-- Bento Grid Dashboard -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                <!-- Status Pesanan Card -->
                <div class="md:col-span-2 bg-white rounded-[40px] border border-gray-100 p-10 shadow-sm hover:shadow-xl transition-all" data-aos="fade-up">
                    <div class="flex justify-between items-center mb-8">
                        <h3 class="text-xl font-bold text-gray-900 flex items-center gap-3">
                            <i class="ph-fill ph-car text-orange-600 text-2xl"></i>
                            Status Pesanan Terakhir
                        </h3>
                        <span class="text-sm font-bold text-orange-600 hover:underline cursor-pointer">Lihat Semua</span>
                    </div>
                    
                    <!-- Empty State (Jika belum ada pesanan) -->
                    <div class="py-12 text-center bg-gray-50 rounded-3xl border-2 border-dashed border-gray-100">
                        <i class="ph ph-package text-5xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500 font-medium italic">Anda belum memiliki pesanan aktif saat ini.</p>
                        <a href="{{ route('katalog.user') }}" class="inline-block mt-4 text-sm font-black text-orange-600 hover:text-orange-700">Mulai Jelajahi Katalog →</a>
                    </div>
                </div>

                <!-- Profile Brief Card -->
                <div class="bg-gray-900 rounded-[40px] p-10 text-white flex flex-col justify-between relative overflow-hidden" data-aos="fade-up" data-aos-delay="100">
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-orange-600/20 blur-[60px] rounded-full"></div>
                    <div class="z-10">
                        <span class="text-[10px] font-black uppercase tracking-[0.3em] text-orange-500 mb-6 block">Member Status</span>
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-16 h-16 rounded-2xl bg-orange-600 flex items-center justify-center text-3xl">
                                <i class="ph-fill ph-crown"></i>
                            </div>
                            <div>
                                <h4 class="text-xl font-bold">Premium User</h4>
                                <p class="text-gray-400 text-xs">Aktif sejak {{ Auth::user()->created_at->format('M Y') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="z-10 pt-8 border-t border-white/10 flex justify-between items-center text-sm">
                        <span class="text-gray-400 font-medium">Informasi Akun</span>
                        <a href="{{ route('profile.edit') }}" class="font-black text-orange-500 hover:text-orange-400">Settings <i class="ph ph-arrow-right"></i></a>
                    </div>
                </div>
            </div>

            <!-- Features Shortcut Section (Katalog & Galeri) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Katalog Shortcut -->
                <a href="{{ route('katalog.user') }}" class="group relative overflow-hidden rounded-[40px] bg-white border border-gray-100 p-8 flex items-center gap-8 hover:shadow-2xl transition-all" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-24 h-24 rounded-3xl bg-orange-50 flex items-center justify-center text-orange-600 group-hover:scale-110 transition-transform duration-500">
                        <i class="ph-fill ph-tag text-5xl"></i>
                    </div>
                    <div>
                        <h4 class="text-2xl font-black text-gray-900 mb-2 tracking-tight group-hover:text-orange-600 transition-colors">Lihat Katalog</h4>
                        <p class="text-gray-500 text-sm font-medium italic">Temukan paket wrapping dan stiker terbaik kami.</p>
                    </div>
                    <div class="absolute right-8 text-gray-200 group-hover:text-orange-200 transition-colors">
                        <i class="ph ph-caret-right text-4xl"></i>
                    </div>
                </a>

                <!-- Galeri Shortcut -->
                <a href="{{ route('galeri.user') }}" class="group relative overflow-hidden rounded-[40px] bg-white border border-gray-100 p-8 flex items-center gap-8 hover:shadow-2xl transition-all" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-24 h-24 rounded-3xl bg-blue-50 flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform duration-500">
                        <i class="ph-fill ph-image-square text-5xl"></i>
                    </div>
                    <div>
                        <h4 class="text-2xl font-black text-gray-900 mb-2 tracking-tight group-hover:text-blue-600 transition-colors">Galeri Karya</h4>
                        <p class="text-gray-500 text-sm font-medium italic">Lihat inspirasi hasil pengerjaan tim kami.</p>
                    </div>
                    <div class="absolute right-8 text-gray-200 group-hover:text-blue-200 transition-colors">
                        <i class="ph ph-caret-right text-4xl"></i>
                    </div>
                </a>
            </div>

@endsection