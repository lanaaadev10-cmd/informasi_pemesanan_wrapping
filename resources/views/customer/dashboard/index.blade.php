@extends('layouts.dashboard_customer')

@section('title', 'Beranda')

@section('content')
    <!-- Greeting & Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
        <div>
            <h1 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight mb-2">
                {{ $profil->dashboard_title ?? 'Selamat datang kembali,' }} <br class="md:hidden"> <span class="text-orange-600">{{ Auth::user()->name }}</span>! 👋
            </h1>
            <p class="text-slate-500 font-medium text-sm md:text-base">{{ $profil->dashboard_subtitle ?? 'Senang melihat Anda kembali. Apa rencana proyek Anda hari ini?' }}</p>
        </div>
        <div class="flex items-center">
            <a href="{{ route('katalog.user') }}" class="w-full md:w-auto inline-flex justify-center items-center gap-3 bg-slate-900 text-white px-6 py-3.5 rounded-2xl font-bold text-sm transition-all hover:bg-orange-600 shadow-[0_10px_40px_-10px_rgba(0,0,0,0.3)]">
                <i class="ph-bold ph-plus-circle text-lg"></i> Buat Pesanan
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 mb-10">
        <div class="bg-white p-6 md:p-8 rounded-3xl border border-slate-200/60 shadow-sm flex flex-col items-center justify-center relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-16 h-16 bg-orange-50 rounded-full group-hover:scale-[2.5] transition-transform duration-500 z-0"></div>
            <h4 class="text-3xl font-black text-slate-900 mb-1 z-10">{{ $profil->stats_experience ?? '5+' }}</h4>
            <p class="text-[10px] md:text-xs font-bold text-slate-400 uppercase tracking-widest z-10">Tahun Exp.</p>
        </div>
        <div class="bg-white p-6 md:p-8 rounded-3xl border border-slate-200/60 shadow-sm flex flex-col items-center justify-center relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-16 h-16 bg-orange-50 rounded-full group-hover:scale-[2.5] transition-transform duration-500 z-0"></div>
            <h4 class="text-3xl font-black text-slate-900 mb-1 z-10">{{ $profil->stats_projects ?? '1.2k' }}</h4>
            <p class="text-[10px] md:text-xs font-bold text-slate-400 uppercase tracking-widest z-10">Proyek Selesai</p>
        </div>
        <div class="bg-white p-6 md:p-8 rounded-3xl border border-slate-200/60 shadow-sm flex flex-col items-center justify-center relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-16 h-16 bg-orange-50 rounded-full group-hover:scale-[2.5] transition-transform duration-500 z-0"></div>
            <h4 class="text-3xl font-black text-slate-900 mb-1 z-10">{{ $profil->stats_satisfaction ?? '99%' }}</h4>
            <p class="text-[10px] md:text-xs font-bold text-slate-400 uppercase tracking-widest z-10">Tingkat Puas</p>
        </div>
        <div class="bg-white p-6 md:p-8 rounded-3xl border border-slate-200/60 shadow-sm flex flex-col items-center justify-center relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-16 h-16 bg-orange-50 rounded-full group-hover:scale-[2.5] transition-transform duration-500 z-0"></div>
            <h4 class="text-3xl font-black text-slate-900 mb-1 z-10">{{ $profil->stats_support ?? '24/7' }}</h4>
            <p class="text-[10px] md:text-xs font-bold text-slate-400 uppercase tracking-widest z-10">Dukungan</p>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-10">
        
        <!-- Aktivitas Pesanan -->
        <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-200/60 p-6 md:p-8 shadow-sm flex flex-col h-full">
            <div class="flex justify-between items-center mb-8">
                <h3 class="text-lg font-black text-slate-900 flex items-center gap-3">
                    <span class="w-8 h-8 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center">
                        <i class="ph-bold ph-ticket"></i>
                    </span>
                    Pesanan Aktif
                </h3>
                <a href="{{ route('pesanan.index') }}" class="text-[11px] font-bold text-slate-400 hover:text-orange-600 transition-colors uppercase tracking-widest flex items-center gap-1">
                    Lihat Semua <i class="ph-bold ph-caret-right"></i>
                </a>
            </div>
            
            <div class="space-y-4 flex-1">
                @forelse($latestOrders as $order)
                <div class="flex flex-col sm:flex-row sm:items-center justify-between p-5 bg-slate-50/50 rounded-2xl border border-slate-100 hover:border-orange-200 hover:bg-orange-50/30 transition-all group">
                    <div class="flex items-center gap-4 mb-4 sm:mb-0">
                        <div class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center text-slate-700 font-bold group-hover:text-orange-600 group-hover:shadow-md transition-all">
                            <i class="ph-fill ph-car-profile text-xl"></i>
                        </div>
                        <div>
                            <span class="block text-base font-bold text-slate-900 tracking-tight mb-0.5">{{ $order->form->model_kendaraan ?? 'Layanan Custom' }}</span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest flex items-center gap-1.5">
                                #{{ $order->kode_pesanan }} <span class="w-1 h-1 bg-slate-300 rounded-full"></span> {{ $order->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between sm:justify-end gap-4 w-full sm:w-auto border-t border-slate-200 sm:border-0 pt-4 sm:pt-0">
                        @php
                            $statusStyles = [
                                'menunggu_verifikasi' => 'bg-amber-50 text-amber-700 border-amber-200',
                                'menunggu_pembayaran' => 'bg-orange-50 text-orange-700 border-orange-200',
                                'dibayar' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'selesai' => 'bg-slate-900 text-white border-slate-900',
                                'dibatalkan' => 'bg-red-50 text-red-700 border-red-200',
                            ];
                        @endphp
                        <span class="{{ $statusStyles[$order->status] ?? 'bg-slate-100 text-slate-600' }} px-3.5 py-1.5 rounded-lg border text-[10px] font-bold uppercase tracking-widest text-center whitespace-nowrap">
                            {{ str_replace('_', ' ', $order->status) }}
                        </span>
                        <a href="{{ route('pesanan.show', $order->id_pesanan) }}" class="w-9 h-9 bg-white border border-slate-200 rounded-lg flex items-center justify-center text-slate-500 hover:bg-slate-900 hover:text-white hover:border-slate-900 transition-all shrink-0">
                            <i class="ph-bold ph-arrow-right"></i>
                        </a>
                    </div>
                </div>
                @empty
                <div class="h-full flex flex-col items-center justify-center text-center p-8 bg-slate-50/50 rounded-2xl border-2 border-dashed border-slate-200">
                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-sm mb-4 text-slate-300">
                        <i class="ph-fill ph-package text-3xl"></i>
                    </div>
                    <p class="text-sm font-semibold text-slate-600 mb-1">Belum ada pesanan aktif</p>
                    <p class="text-xs font-medium text-slate-400">Pesanan Anda akan muncul di sini.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Sidebar Member Card -->
        <div class="bg-slate-900 rounded-3xl p-8 flex flex-col relative overflow-hidden shadow-xl shadow-slate-900/10 h-full">
            <!-- Decorative Background Element -->
            <div class="absolute -right-16 -top-16 w-64 h-64 bg-gradient-to-br from-orange-500 to-orange-600 blur-[80px] rounded-full opacity-40"></div>
            
            <div class="relative z-10 flex-1 flex flex-col">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center text-white text-2xl shadow-lg shadow-orange-500/30">
                        <i class="ph-fill ph-crown"></i>
                    </div>
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-[0.2em] text-orange-400 block mb-1">Status Member</span>
                        <h4 class="text-xl font-black text-white tracking-tight">Premium User</h4>
                    </div>
                </div>

                <div class="space-y-5 bg-white/5 border border-white/10 rounded-2xl p-5 mb-8">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-2.5">
                            <i class="ph-fill ph-receipt text-slate-400"></i>
                            <span class="text-slate-300 text-xs font-semibold">Total Pesanan</span>
                        </div>
                        <span class="font-black text-base text-white">{{ $latestOrders->count() }}</span>
                    </div>
                    <div class="w-full h-px bg-white/10"></div>
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-2.5">
                            <i class="ph-fill ph-medal text-orange-400"></i>
                            <span class="text-slate-300 text-xs font-semibold">Poin Loyalty</span>
                        </div>
                        <span class="font-black text-base text-orange-400">240 <span class="text-[10px] text-slate-500">pts</span></span>
                    </div>
                </div>
                
                <div class="mt-auto">
                    <a href="{{ route('profile.edit') }}" class="w-full flex justify-center items-center gap-2 py-3.5 bg-white/10 hover:bg-white/20 border border-white/10 rounded-xl text-white font-bold text-xs uppercase tracking-widest transition-all">
                        Pengaturan Akun <i class="ph-bold ph-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Catalog -->
    <div class="mb-10">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-black text-slate-900 flex items-center gap-3">
                <span class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center">
                    <i class="ph-bold ph-sparkle"></i>
                </span>
                Layanan Rekomendasi
            </h3>
            <a href="{{ route('katalog.user') }}" class="text-[11px] font-bold text-slate-400 hover:text-emerald-600 transition-colors uppercase tracking-widest flex items-center gap-1">
                Eksplorasi <i class="ph-bold ph-caret-right"></i>
            </a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($layanans->take(3) as $item)
                <div class="bg-white rounded-3xl overflow-hidden border border-slate-200/60 hover:border-orange-300 hover:shadow-xl hover:shadow-orange-500/5 transition-all duration-300 flex flex-col group">
                    <div class="relative aspect-[4/3] overflow-hidden bg-slate-100">
                        @if($item->foto_contoh)
                            <img src="{{ asset('storage/' . $item->foto_contoh) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" alt="{{ $item->nama_layanan }}">
                        @else
                            <img src="https://images.unsplash.com/photo-1614850523459-c2f4c699c52e?q=80&w=2070&auto=format&fit=crop" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" alt="Default">
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </div>
                    
                    <div class="p-6 flex flex-col flex-grow">
                        <div class="mb-5 flex-grow">
                            <h4 class="text-lg font-bold text-slate-900 mb-1.5 group-hover:text-orange-600 transition-colors line-clamp-1">{{ $item->nama_layanan }}</h4>
                            <p class="text-slate-500 text-xs font-medium leading-relaxed line-clamp-2">{{ $item->deskripsi }}</p>
                        </div>
                        
                        <div class="flex items-center justify-between pt-5 border-t border-slate-100 mb-5">
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-0.5">Mulai Dari</span>
                                <span class="text-base font-black text-slate-900">Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
                            </div>
                            <form action="{{ route('keranjang.tambah') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id_paket" value="{{ $item->id_layanan }}">
                                <input type="hidden" name="jumlah" value="1">
                                <button type="submit" class="w-10 h-10 bg-slate-50 text-slate-600 rounded-xl flex items-center justify-center hover:bg-slate-900 hover:text-white transition-all border border-slate-200 hover:border-slate-900 shadow-sm" title="Tambah ke Keranjang">
                                    <i class="ph-bold ph-shopping-cart-simple text-lg"></i>
                                </button>
                            </form>
                        </div>
                        
                        <form action="{{ route('keranjang.tambah') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id_paket" value="{{ $item->id_layanan }}">
                            <input type="hidden" name="jumlah" value="1">
                            <input type="hidden" name="direct_checkout" value="1">
                            <button type="submit" class="w-full py-3.5 bg-slate-900 text-white rounded-xl font-bold text-xs tracking-widest uppercase hover:bg-orange-600 transition-all flex justify-center items-center gap-2">
                                Pesan Sekarang <i class="ph-bold ph-arrow-right"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Highlighted Testimonial -->
    @php
        $testimonis = collect($profil->testimonis_json ?? [])->map(fn($item) => (object)$item);
    @endphp
    @if($testimonis->count() > 0)
    <div class="mt-10 bg-gradient-to-br from-slate-900 to-slate-800 rounded-3xl p-8 md:p-12 text-center border border-slate-700 relative overflow-hidden">
        <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-orange-500/20 blur-[60px] rounded-full"></div>
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-orange-500/20 blur-[60px] rounded-full"></div>
        
        <div class="relative z-10 max-w-3xl mx-auto">
            @php $testi = $testimonis->first(); @endphp
            <div class="flex justify-center gap-1.5 mb-6">
                @for($i=0; $i<($testi->rating ?? 5); $i++) <i class="ph-fill ph-star text-orange-400 text-xl"></i> @endfor
            </div>
            <h3 class="text-xl md:text-2xl font-semibold text-white leading-relaxed mb-6">
                "{!! isset($testi->isi) ? nl2br(e($testi->isi)) : '' !!}"
            </h3>
            <div class="inline-flex items-center justify-center gap-3 bg-white/5 border border-white/10 px-6 py-2.5 rounded-full">
                <span class="text-xs font-black text-white uppercase tracking-widest">{{ $testi->nama ?? 'Anonymous' }}</span>
                <span class="w-1 h-1 bg-slate-500 rounded-full"></span>
                <span class="text-xs font-medium text-slate-400">{{ $testi->jabatan ?? 'Customer' }}</span>
            </div>
        </div>
    </div>
    @endif
@endsection