@extends('layouts.dashboard-customer')

@section('title', 'Keranjang Belanja')

@php
    $accentColor = '#FF6B00';
    $keranjangTitle = $profil->keranjang_title ?? 'Keranjang Belanja';
    $keranjangSubtitle = $profil->keranjang_subtitle ?? 'Tinjau pilihan layanan premium Anda sebelum melakukan pembayaran.';
@endphp

<style>
    :root {
        --accent-color: {{ $accentColor }};
    }
    .accent-bg { background-color: var(--accent-color); }
    .accent-color { color: var(--accent-color); }
</style>

@section('content')
<div class="max-w-6xl mx-auto py-6 text-slate-900 space-y-8 relative overflow-hidden">

    <!-- Ambient glowing backdrop orb -->
    <div class="absolute top-10 left-1/3 -translate-x-1/2 w-[400px] h-[200px] rounded-full blur-[100px] pointer-events-none z-0" style="background-color: rgba(255,107,0,0.08);"></div>

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 z-10 relative">
        <div>
            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest font-mono">{{ $profil->keranjang_hero_text ?? 'PILIHAN LAYANAN ANDA' }}</span>
            <h1 class="text-3xl font-audiowide font-bold text-slate-900 tracking-tight mt-1">
                {{ $keranjangTitle }}
            </h1>
            <p class="text-slate-500 text-xs sm:text-sm font-questrial mt-1">{{ $keranjangSubtitle }}</p>
        </div>
        
        @if($keranjang && $keranjang->details->isNotEmpty())
            <div class="flex items-center shrink-0">
                <form action="{{ route('keranjang.kosongkan') }}" method="POST" onsubmit="return confirmEmptyCart(event, this)">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-red-50 hover:bg-red-100 border border-red-200 text-red-600 rounded-2xl font-bold text-[10px] tracking-wider uppercase transition-all active:scale-95 shadow-sm">
                        <i class="ph-bold ph-trash-simple text-xs"></i>
                        <span>{{ $profil->cta_kosongkan ?? 'Kosongkan Keranjang' }}</span>
                    </button>
                </form>
            </div>
        @endif
    </div>

    <!-- Main Grid Content -->
    @if(!$keranjang || $keranjang->details->isEmpty())
        <!-- Empty State in clean white layout -->
        <div class="bg-white border border-slate-200/80 rounded-[32px] p-16 text-center shadow-sm relative overflow-hidden z-10">
            <div class="absolute -right-10 -top-10 w-64 h-64 bg-[#FF6B00]/5 blur-[80px] rounded-full"></div>
            <div class="relative z-10 max-w-md mx-auto space-y-6">
                <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center text-slate-400 mx-auto shadow-inner border border-slate-200">
                    <i class="ph-bold ph-shopping-bag text-3xl text-slate-400"></i>
                </div>
                <div class="space-y-2">
                    <h3 class="text-xl font-audiowide font-bold text-slate-900">{{ $profil->empty_keranjang_title ?? 'Keranjang Kosong' }}</h3>
                    <p class="text-slate-500 text-xs font-questrial leading-relaxed">{{ $profil->empty_keranjang_desc ?? 'Sepertinya Anda belum memilih layanan wrapping premium terbaik untuk kendaraan Anda.' }}</p>
                </div>
                <a href="{{ route('katalog.user') }}" 
                   class="inline-flex items-center gap-2 px-6 py-3.5 bg-[#FF6B00] hover:bg-[#E05D00] text-white rounded-2xl font-extrabold text-xs tracking-wider uppercase transition-all shadow-md hover:scale-105 active:scale-95">
                    {{ $profil->cta_explore_layanan ?? 'Jelajahi Katalog Layanan' }} &rarr;
                </a>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 z-10 relative">
            
            <!-- Left Side: List of items -->
            <div class="lg:col-span-8 space-y-4">
                @foreach($keranjang->details as $item)
                    @php
                        $itemImage = \App\Helpers\StaticContent::fotoUrl($item->layanan->foto_contoh ?? '');
                    @endphp

                    <div class="bg-white border border-slate-200/80 rounded-[28px] overflow-hidden p-5 flex flex-col sm:flex-row items-center gap-6 group hover:border-[#FF6B00]/60 transition-all duration-300 relative shadow-sm hover:shadow-md">
                        
                        <!-- Rounded visual thumbnail -->
                        <div class="w-24 h-24 rounded-2xl overflow-hidden bg-slate-100 flex items-center justify-center shrink-0 border border-slate-200 shadow-inner">
                            <img src="{{ $itemImage }}" alt="{{ $item->layanan->nama_layanan }}" class="w-full h-full object-cover transform scale-100 group-hover:scale-105 transition-transform duration-700">
                        </div>

                        <!-- Product details -->
                        <div class="flex-grow flex flex-col justify-between self-stretch py-1">
                            <div class="flex justify-between items-start gap-4">
                                <div class="space-y-1">
                                    <span class="text-[9px] font-bold text-[#FF6B00] uppercase tracking-widest block font-mono">
                                        {{ $item->layanan->kategori ?? 'Layanan Premium' }}
                                    </span>
                                    <h3 class="text-base font-bold text-slate-900 group-hover:text-[#FF6B00] transition-colors leading-tight line-clamp-1 font-montserrat">
                                        {{ $item->layanan->nama_layanan }}
                                    </h3>
                                </div>

                                <!-- Delete button from figma -->
                                <form action="{{ route('keranjang.hapus', $item->id_detail) }}" method="POST" class="shrink-0" onsubmit="return confirmDeleteItem(event, this, '{{ $item->layanan->nama_layanan }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="flex items-center gap-1.5 text-xs text-red-500 hover:text-red-600 font-bold transition-all px-3 py-1.5 rounded-xl hover:bg-red-50 active:scale-95">
                                        <i class="ph ph-trash-simple text-sm"></i>
                                        <span>{{ $profil->cta_hapus ?? 'Hapus' }}</span>
                                    </button>
                                </form>
                            </div>
                            
                            <!-- Bottom control panel -->
                            <div class="flex flex-wrap items-end justify-between gap-4 mt-4">
                                <div class="flex items-center gap-6">
                                    
                                    <!-- Dynamic quantity buttons block -->
                                    <div class="space-y-1.5">
                                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest block">Jumlah Unit</span>
                                        <div class="flex items-center bg-slate-100 p-1 rounded-xl border border-slate-200">
                                            <!-- Decrease Button -->
                                            <button type="button" 
                                                    id="btn-dec-{{ $item->id_detail }}" 
                                                    onclick="changeQty({{ $item->id_detail }}, -1)" 
                                                    {{ $item->jumlah <= 1 ? 'disabled' : '' }} 
                                                    class="w-7 h-7 bg-white hover:bg-slate-200 rounded-lg flex items-center justify-center text-slate-600 hover:text-slate-900 transition-all disabled:opacity-30 disabled:cursor-not-allowed shadow-sm">
                                                <i class="ph ph-minus text-[10px]"></i>
                                            </button>
                                            
                                            <!-- Current Qty -->
                                            <span id="qty-{{ $item->id_detail }}" 
                                                  class="w-8 text-center text-xs font-bold text-slate-900">
                                                {{ $item->jumlah }}
                                            </span>
                                            
                                            <!-- Increase Button -->
                                            <button type="button" 
                                                    id="btn-inc-{{ $item->id_detail }}" 
                                                    onclick="changeQty({{ $item->id_detail }}, 1)" 
                                                    class="w-7 h-7 bg-white hover:bg-slate-200 rounded-lg flex items-center justify-center text-slate-600 hover:text-slate-900 transition-all shadow-sm">
                                                <i class="ph ph-plus text-[10px]"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="w-px h-8 bg-slate-200 self-end"></div>

                                    <div class="space-y-1.5">
                                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest block">Harga Satuan</span>
                                        <span class="text-xs text-slate-600 font-medium block pb-1.5">
                                            Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Subtotal calculation display -->
                                <div class="text-right">
                                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest block mb-0.5">{{ $profil->label_subtotal ?? 'Subtotal' }}</span>
                                    <span id="subtotal-{{ $item->id_detail }}" 
                                          class="text-[#FF6B00] text-lg font-black font-audiowide"
                                          data-unit-price="{{ $item->harga_satuan }}">
                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>

                            @if($item->catatan_custom)
                                <div class="mt-4 p-3 bg-slate-50 border border-slate-200 rounded-2xl flex items-start gap-2.5">
                                    <span class="text-xs">📝</span>
                                    <p class="text-[10px] font-bold text-slate-600 leading-relaxed italic">{{ $item->catatan_custom }}</p>
                                </div>
                            @endif

                            <div class="mt-4 pt-3 border-t border-slate-100 flex flex-wrap items-center justify-between gap-2">
                                <span class="text-[10px] text-slate-500 font-questrial">
                                    Pilih jadwal reservasi pengerjaan untuk paket ini:
                                </span>
                                <a href="{{ route('booking.create', ['layanan_id' => $item->id_paket]) }}"
                                   class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-orange-50 hover:bg-[#FF6B00] text-[#FF6B00] hover:text-white border border-orange-200 text-[10px] font-montserrat font-bold uppercase tracking-wider transition-all active:scale-95 shadow-sm">
                                    <i class="ph-bold ph-calendar-plus text-xs"></i>
                                    <span>Jadwalkan Paket Ini</span>
                                    <i class="ph-bold ph-arrow-right text-[10px]"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Dynamic Dashed Add Service Shortcut -->
                <a href="{{ route('katalog.user') }}" 
                   class="border-2 border-dashed border-slate-200 hover:border-[#FF6B00]/60 bg-white hover:bg-slate-50 rounded-[28px] p-6 transition-all cursor-pointer flex flex-col items-center justify-center gap-2 group shadow-sm z-10 relative">
                    <div class="w-10 h-10 rounded-full bg-orange-50 group-hover:bg-orange-100 flex items-center justify-center text-[#FF6B00] transition-all">
                        <i class="ph ph-plus text-base"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-800 tracking-wider uppercase font-montserrat">{{ $profil->cta_tambah_lainnya ?? 'Tambah Layanan Lainnya' }}</span>
                </a>
            </div>

            <!-- Right Side: Order Summary -->
            @include('dashboard.customer.keranjang.partials._cart-summary')

        </div>
    @endif
</div>

<!-- Quantity Adjustment Controller & Confirmation Modal -->
@include('dashboard.customer.keranjang.partials._cart-scripts')

@endsection
