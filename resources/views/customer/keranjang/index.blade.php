@extends('layouts.dashboard_customer')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="max-w-6xl mx-auto pb-24" data-aos="fade-up">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-8 mb-16">
        <div>
            <div class="flex items-center gap-3 mb-4">
                <div class="w-2 h-2 bg-orange-600 rounded-full animate-pulse"></div>
                <span class="text-orange-600 font-black text-[10px] uppercase tracking-[0.4em]">Premium Selection</span>
            </div>
            <h1 class="text-5xl md:text-6xl font-black text-gray-900 tracking-tighter">Your <span class="text-orange-600 italic">Cart.</span></h1>
            <p class="text-gray-400 mt-2 font-medium italic">Tinjau layanan pilihan Anda sebelum memproses pengerjaan.</p>
        </div>
        
        @if($keranjang && $keranjang->details->count() > 0)
        <div class="flex items-center gap-6">
            <div class="text-right">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">Total Items</p>
                <span class="font-black text-xl text-gray-900">{{ $keranjang->details->count() }}</span>
            </div>
            <form action="{{ route('keranjang.kosongkan') }}" method="POST" onsubmit="return confirm('Kosongkan semua layanan?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="group w-14 h-14 bg-white border border-gray-100 text-gray-300 rounded-[1.5rem] flex items-center justify-center hover:text-red-500 hover:border-red-100 hover:shadow-xl hover:shadow-red-50 transition-all">
                    <i class="ph-bold ph-trash-simple text-2xl group-hover:shake"></i>
                </button>
            </form>
        </div>
        @endif
    </div>

    @if(!$keranjang || $keranjang->details->isEmpty())
        <div class="soft-card p-24 text-center relative overflow-hidden">
            <div class="absolute top-0 right-0 w-96 h-96 bg-orange-600/5 blur-[120px] rounded-full"></div>
            <div class="relative z-10">
                <div class="w-32 h-32 bg-gray-50 rounded-[3rem] flex items-center justify-center text-gray-200 mx-auto mb-10 shadow-inner">
                    <i class="ph-fill ph-shopping-cart-simple text-7xl"></i>
                </div>
                <h3 class="text-3xl font-black text-gray-900 mb-4 tracking-tight">Belum Ada Layanan.</h3>
                <p class="text-gray-400 mb-12 italic font-medium max-w-sm mx-auto">Sepertinya Anda belum memilih layanan wrapping terbaik untuk kendaraan Anda.</p>
                <a href="{{ route('katalog.user') }}" class="inline-flex items-center gap-4 px-12 py-5 bg-orange-600 text-white rounded-2xl font-black text-xs tracking-widest uppercase shadow-2xl shadow-orange-200 hover:bg-orange-500 hover:-translate-y-1 transition-all">
                    MULAI EXPLORE LAYANAN <i class="ph-bold ph-arrow-right"></i>
                </a>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            <!-- Items List -->
            <div class="lg:col-span-8 space-y-8">
                @foreach($keranjang->details as $item)
                <div class="soft-card group hover:border-orange-500/30 transition-all duration-700 overflow-hidden flex flex-col md:flex-row relative">
                    {{-- Product Image --}}
                    <div class="w-full md:w-56 h-56 md:h-auto overflow-hidden relative">
                        <div class="absolute inset-0 bg-gray-900/10 group-hover:bg-transparent transition-colors z-10"></div>
                        @if($item->layanan->foto_contoh)
                            <img src="{{ asset('storage/' . $item->layanan->foto_contoh) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000">
                        @else
                            <div class="w-full h-full bg-gray-50 flex items-center justify-center text-gray-200">
                                <i class="ph-fill ph-sketch-logo text-6xl"></i>
                            </div>
                        @endif
                    </div>

                    <div class="p-8 md:p-10 flex-grow flex flex-col justify-between">
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <span class="text-[10px] font-black uppercase text-orange-600 tracking-[0.3em] mb-3 block">{{ $item->layanan->kategori ?? 'Premium Service' }}</span>
                                <h3 class="text-2xl font-black text-gray-900 tracking-tighter leading-none group-hover:text-orange-600 transition-colors">{{ $item->layanan->nama_layanan }}</h3>
                            </div>
                            <form action="{{ route('keranjang.hapus', $item->id_detail) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-10 h-10 rounded-xl text-gray-300 hover:text-red-500 hover:bg-red-50 transition-all flex items-center justify-center">
                                    <i class="ph-bold ph-x text-xl"></i>
                                </button>
                            </form>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-6">
                                <div class="flex flex-col">
                                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Quantity</p>
                                    <span class="font-black text-gray-900">{{ $item->jumlah }} Unit</span>
                                </div>
                                <div class="w-[1px] h-8 bg-gray-100"></div>
                                <div class="flex flex-col">
                                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Unit Price</p>
                                    <span class="font-bold text-gray-400">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Subtotal</p>
                                <p class="text-2xl font-black text-gray-900 italic tracking-tighter">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        @if($item->catatan_custom)
                        <div class="mt-8 p-4 bg-gray-50 rounded-2xl flex items-start gap-4 border border-gray-100 group-hover:bg-orange-50/50 group-hover:border-orange-100 transition-all">
                            <i class="ph-fill ph-note text-orange-400 text-lg"></i>
                            <p class="text-[11px] font-bold text-gray-500 leading-relaxed italic">{{ $item->catatan_custom }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Summary Sidebar -->
            <div class="lg:col-span-4">
                <div class="sticky top-32 space-y-8">
                    <div class="soft-card p-10 bg-gray-900 text-white border-none shadow-3xl relative overflow-hidden">
                        {{-- Decorative Elements --}}
                        <div class="absolute -top-24 -right-24 w-64 h-64 bg-orange-600/20 blur-[100px] rounded-full"></div>
                        <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-blue-600/10 blur-[100px] rounded-full"></div>

                        <h3 class="text-sm font-black uppercase tracking-[0.3em] mb-12 flex items-center gap-4 relative z-10">
                            <i class="ph-fill ph-receipt text-orange-500 text-2xl"></i> Billing Summary
                        </h3>

                        <div class="space-y-6 mb-12 relative z-10">
                            <div class="flex justify-between items-center group">
                                <span class="text-xs font-bold text-gray-500 uppercase tracking-widest group-hover:text-gray-300 transition-colors">Net Total</span>
                                <span class="font-black text-lg">Rp {{ number_format($keranjang->details->sum('subtotal'), 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center group">
                                <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">Tax (0%)</span>
                                <span class="font-black text-lg text-green-500 italic">Free</span>
                            </div>
                            <div class="pt-8 border-t border-white/10 flex justify-between items-end">
                                <div>
                                    <p class="text-[10px] font-black text-orange-500 uppercase tracking-widest mb-1">Total Payable</p>
                                    <p class="text-4xl font-black italic tracking-tighter">Rp {{ number_format($keranjang->details->sum('subtotal'), 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('pesanan.checkout.form') }}" class="relative z-10 w-full bg-orange-600 text-white py-6 rounded-[2rem] font-black text-center block shadow-2xl shadow-orange-900/50 hover:bg-orange-500 hover:-translate-y-1 transition-all group active:scale-95">
                            PROCEED TO CHECKOUT <i class="ph-bold ph-arrow-right ml-3 group-hover:translate-x-2 transition-transform"></i>
                        </a>
                        
                        <p class="text-[9px] font-bold text-gray-500 text-center mt-6 uppercase tracking-widest opacity-60">Secure Checkout Powered by Dantie Stiker</p>
                    </div>

                    {{-- Trust Badge --}}
                    <div class="soft-card p-6 border-dashed border-2 bg-gray-50/30">
                        <div class="flex gap-5 items-center">
                            <div class="w-12 h-12 rounded-2xl bg-white shadow-sm flex items-center justify-center text-orange-600">
                                <i class="ph-fill ph-shield-check text-2xl"></i>
                            </div>
                            <div>
                                <h4 class="text-[11px] font-black text-gray-900 uppercase tracking-widest">Garansi Pengerjaan</h4>
                                <p class="text-[10px] font-medium text-gray-400 leading-relaxed italic">Proteksi maksimal untuk kendaraan kesayangan Anda.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
