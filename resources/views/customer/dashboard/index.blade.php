@extends('layouts.dashboard_customer')

@section('title', 'Beranda')

@section('content')
    <!-- Greeting & Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
        <div>
            <h1 class="font-serif text-3xl md:text-4xl font-black text-stone-900 tracking-tight mb-1">
                Selamat datang, <span class="text-[#f2541b]">{{ Auth::user()->name }}</span> 👋
            </h1>
            <p class="text-stone-500 font-medium text-xs md:text-sm">Selamat datang kembali di dashboard dantiestiker Anda.</p>
        </div>
        <div>
            <a href="{{ route('katalog.user') }}" class="inline-flex items-center gap-2 bg-[#151413] hover:bg-[#2a2927] text-white px-5 py-3 rounded-full font-bold text-xs tracking-wider uppercase transition-all shadow-md">
                + Buat Pesanan
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 mb-10">
        <div class="bg-white p-5 md:p-6 rounded-[24px] border-t-[3px] border-[#f2541b] shadow-sm flex flex-col justify-center overflow-hidden">
            <h4 class="font-serif text-base md:text-3xl font-black text-stone-900 mb-1 truncate" title="{{ $profil->stats_experience ?? '12' }}">{{ $profil->stats_experience ?? '12' }}</h4>
            <p class="text-[9px] md:text-[10px] font-bold text-stone-400 uppercase tracking-widest">Tahun Exp.</p>
        </div>
        <div class="bg-white p-5 md:p-6 rounded-[24px] border-t-[3px] border-[#f2541b] shadow-sm flex flex-col justify-center overflow-hidden">
            <h4 class="font-serif text-base md:text-3xl font-black text-stone-900 mb-1 truncate" title="{{ $profil->stats_projects ?? '238' }}">{{ $profil->stats_projects ?? '238' }}</h4>
            <p class="text-[9px] md:text-[10px] font-bold text-stone-400 uppercase tracking-widest">Proyek Selesai</p>
        </div>
        <div class="bg-white p-5 md:p-6 rounded-[24px] border-t-[3px] border-[#f2541b] shadow-sm flex flex-col justify-center overflow-hidden">
            <h4 class="font-serif text-base md:text-3xl font-black text-stone-900 mb-1 truncate" title="{{ $profil->stats_satisfaction ?? '98%' }}">{{ $profil->stats_satisfaction ?? '98%' }}</h4>
            <p class="text-[9px] md:text-[10px] font-bold text-stone-400 uppercase tracking-widest">Tingkat Puas</p>
        </div>
        <div class="bg-white p-5 md:p-6 rounded-[24px] border-t-[3px] border-[#f2541b] shadow-sm flex flex-col justify-center overflow-hidden">
            <h4 class="font-serif text-base md:text-3xl font-black text-stone-900 mb-1 truncate" title="{{ $profil->stats_support ?? '24/7' }}">{{ $profil->stats_support ?? '24/7' }}</h4>
            <p class="text-[9px] md:text-[10px] font-bold text-stone-400 uppercase tracking-widest">Dukungan</p>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-10">
        
        <!-- Aktivitas Pesanan -->
        <div class="lg:col-span-2 bg-white rounded-[32px] p-6 md:p-8 border border-stone-200/50 shadow-sm flex flex-col h-full">
            <div class="flex justify-between items-center mb-8">
                <h3 class="font-serif text-lg font-black text-stone-900">
                    Pesanan Aktif
                </h3>
                <a href="{{ route('pesanan.index') }}" class="text-[11px] font-bold text-[#f2541b] hover:text-[#d33d0a] transition-colors uppercase tracking-widest flex items-center gap-1.5">
                    Lihat Semua &rarr;
                </a>
            </div>
            
            <div class="space-y-4 flex-1">
                @forelse($latestOrders->take(2) as $order)
                <div class="flex items-center justify-between p-4 bg-[#fcfbfa] rounded-2xl border border-stone-100/85 hover:border-[#f2541b]/30 transition-all group">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-stone-200/60 rounded-xl flex items-center justify-center text-stone-600 font-bold shrink-0">
                            <div class="w-4 h-4 bg-stone-300 rounded"></div>
                        </div>
                        <div>
                            <span class="block text-sm font-serif font-black text-stone-900 tracking-tight mb-0.5">{{ $order->form->model_kendaraan ?? 'Layanan Custom' }}</span>
                            <span class="text-[10px] font-bold text-stone-400 uppercase tracking-widest">
                                #{{ $order->kode_pesanan }} &middot; {{ $order->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        @php
                            $statusStyles = [
                                'menunggu_verifikasi' => 'bg-amber-100/70 text-amber-800 border-amber-200',
                                'menunggu_pembayaran' => 'bg-orange-100/70 text-orange-800 border-orange-200',
                                'dibayar' => 'bg-[#d1f5ea] text-[#118f70] border-[#b0ebd8]',
                                'selesai' => 'bg-[#151413] text-white border-[#151413]',
                                'dibatalkan' => 'bg-red-50 text-red-700 border-red-200',
                            ];
                            $statusLabels = [
                                'menunggu_verifikasi' => 'VERIFIKASI',
                                'menunggu_pembayaran' => 'BELUM BAYAR',
                                'dibayar' => 'DIBAYAR',
                                'selesai' => 'SELESAI',
                                'dibatalkan' => 'BATAL',
                            ];
                        @endphp
                        <span class="{{ $statusStyles[$order->status] ?? 'bg-stone-100 text-stone-600' }} px-3.5 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest text-center whitespace-nowrap">
                            {{ $statusLabels[$order->status] ?? str_replace('_', ' ', $order->status) }}
                        </span>
                        <a href="{{ route('pesanan.show', $order->id_pesanan) }}" class="text-stone-400 hover:text-stone-950 transition-colors">
                            <i class="ph-bold ph-arrow-right text-lg"></i>
                        </a>
                    </div>
                </div>
                @empty
                <div class="h-full flex flex-col items-center justify-center text-center p-8 bg-[#fcfbfa] rounded-2xl border border-dashed border-stone-200">
                    <div class="w-12 h-12 bg-stone-100 rounded-full flex items-center justify-center shadow-sm mb-3 text-stone-300">
                        <i class="ph-fill ph-package text-xl"></i>
                    </div>
                    <p class="text-xs font-bold text-stone-600 mb-1">Belum ada pesanan aktif</p>
                    <p class="text-[10px] font-medium text-stone-400">Pesanan Anda akan muncul di sini.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Sidebar Member Card -->
        <div class="bg-[#151413] rounded-[32px] p-8 flex flex-col relative overflow-hidden shadow-xl shadow-stone-900/10 h-full min-h-[300px]">
            <!-- Decorative Background Element -->
            <div class="absolute -right-12 -top-12 w-48 h-48 bg-gradient-to-br from-[#f2541b] to-orange-500 blur-[60px] rounded-full opacity-35"></div>
            
            <div class="relative z-10 flex-1 flex flex-col">
                 <div class="mb-6">
                     <span class="text-[9px] font-black uppercase tracking-[0.2em] text-[#f2541b] block mb-1">STATUS MEMBER</span>
                     <h4 class="font-serif text-2xl font-black text-white tracking-tight">Premium User 👑</h4>
                 </div>

                 <div class="space-y-4 bg-white/5 border border-white/5 rounded-2xl p-5 mb-6">
                     <div class="flex justify-between items-center">
                         <span class="text-[#a19f9a] text-xs font-semibold">Total Pesanan</span>
                         <span class="font-serif font-black text-base text-white">{{ $latestOrders->count() }}</span>
                     </div>
                     <div class="w-full h-px bg-white/10"></div>
                     <div class="flex justify-between items-center">
                         <span class="text-[#a19f9a] text-xs font-semibold">Poin Loyalty</span>
                         <span class="font-serif font-black text-base text-[#f2541b]">240 <span class="text-[10px] text-[#a19f9a]">pts</span></span>
                     </div>
                 </div>
                 
                 <div class="mt-auto">
                     <a href="{{ route('profile.edit') }}" class="w-full py-3.5 bg-[#201e1c] hover:bg-[#2b2926] border border-stone-850 rounded-xl text-white font-bold text-[10px] uppercase tracking-widest transition-all flex items-center justify-center gap-2">
                         Pengaturan Akun &rarr;
                     </a>
                 </div>
            </div>
        </div>
    </div>

    <!-- Featured Catalog -->
    <div class="mb-10">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-serif text-lg font-black text-stone-900">
                Layanan Rekomendasi
            </h3>
            <a href="{{ route('katalog.user') }}" class="text-[11px] font-bold text-[#f2541b] hover:text-[#d33d0a] transition-colors uppercase tracking-widest flex items-center gap-1">
                Eksplorasi &rarr;
            </a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($layanans->take(3) as $item)
                <div class="bg-white rounded-[28px] overflow-hidden border border-stone-200/60 hover:border-[#f2541b]/30 hover:shadow-xl hover:shadow-[#f2541b]/5 transition-all duration-300 flex flex-col group p-4">
                    <div class="relative aspect-[4/3] rounded-[20px] overflow-hidden bg-[#e9e8e4] flex items-center justify-center text-stone-400 font-bold shrink-0 mb-4">
                        @if($item->foto_contoh)
                            <img src="{{ asset('storage/' . $item->foto_contoh) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" alt="{{ $item->nama_layanan }}">
                        @else
                            <div class="text-[11px] uppercase tracking-wider text-stone-500 font-serif">[ Gambar Layanan ]</div>
                        @endif
                    </div>
                    
                    <div class="flex flex-col flex-grow">
                        <div class="mb-4 flex-grow">
                            <h4 class="font-serif text-base font-black text-stone-900 mb-1.5 group-hover:text-[#f2541b] transition-colors line-clamp-1">{{ $item->nama_layanan }}</h4>
                            <p class="text-stone-500 text-xs font-medium leading-relaxed line-clamp-2">{{ $item->deskripsi }}</p>
                        </div>
                        
                        <div class="flex flex-col mb-4">
                            <span class="text-[9px] font-bold text-stone-400 uppercase tracking-widest mb-0.5">Mulai Dari</span>
                            <span class="font-serif text-lg font-black text-stone-900">Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
                        </div>
                        
                        <form action="{{ route('keranjang.tambah') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id_paket" value="{{ $item->id_layanan }}">
                            <input type="hidden" name="jumlah" value="1">
                            <input type="hidden" name="direct_checkout" value="1">
                            <button type="submit" class="w-full py-3.5 bg-[#151413] hover:bg-[#2c2a28] text-white rounded-xl font-bold text-[10px] tracking-widest uppercase transition-all flex justify-center items-center gap-2">
                                Pesan Sekarang &rarr;
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
    <div class="mt-12 bg-[#151413] rounded-[32px] p-8 md:p-10 text-center border border-stone-800 relative overflow-hidden">
        <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-[#f2541b]/10 blur-[60px] rounded-full"></div>
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-[#f2541b]/10 blur-[60px] rounded-full"></div>
        
        <div class="relative z-10 max-w-3xl mx-auto">
            @php $testi = $testimonis->first(); @endphp
            <div class="flex justify-center gap-1.5 mb-4">
                @for($i=0; $i<($testi->rating ?? 5); $i++) 
                    <span class="text-orange-400 text-sm">★</span>
                @endfor
            </div>
            <h3 class="font-serif text-lg md:text-xl font-medium text-white leading-relaxed mb-4">
                "{!! isset($testi->isi) ? nl2br(e($testi->isi)) : '' !!}"
            </h3>
            <div class="inline-flex items-center justify-center gap-2.5 bg-white/5 border border-white/10 px-5 py-2 rounded-full">
                <span class="text-[10px] font-black text-white uppercase tracking-widest">{{ $testi->nama ?? 'Anonymous' }}</span>
                <span class="w-1 h-1 bg-stone-600 rounded-full"></span>
                <span class="text-[10px] font-medium text-stone-400">{{ $testi->jabatan ?? 'Customer' }}</span>
            </div>
        </div>
    </div>
    @endif
@endsection