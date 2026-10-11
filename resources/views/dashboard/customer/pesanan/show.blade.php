@extends('layouts.dashboard-customer')

@php
    $statusVal = $pesanan->status instanceof \App\Enums\OrderStatus ? $pesanan->status->value : $pesanan->status;
@endphp

@section('title', 'Verifikasi Pembayaran - ' . $pesanan->kode_pesanan)

@section('content')
<div class="max-w-6xl mx-auto py-8 text-slate-900 space-y-8 relative overflow-hidden">
    <div class="absolute top-0 right-10 w-[500px] h-[400px] bg-[#FF6B00]/5 rounded-full blur-[130px] pointer-events-none z-0"></div>

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 z-10 relative border-b border-slate-200 pb-6">
        <div>
            <h1 class="text-2xl font-audiowide font-bold tracking-tight text-slate-900">{{ $profil->status_verifikasi_pembayaran ?? 'Verifikasi Pembayaran' }}</h1>
        </div>
        
        <div class="flex items-center gap-4">
            <span class="text-[10px] text-slate-400 font-montserrat font-bold">Langkah 4 dari 4</span>
            <div class="flex gap-1.5">
                <div class="w-8 h-1 bg-[#FF6B00] rounded-full"></div>
                <div class="w-8 h-1 bg-[#FF6B00] rounded-full"></div>
                <div class="w-8 h-1 bg-[#FF6B00] rounded-full"></div>
                <div class="w-12 h-1 bg-[#FF6B00] rounded-full shadow-[0_0_8px_rgba(255,107,0,0.6)]"></div>
            </div>
            <span class="text-[10px] text-[#FF6B00] font-montserrat font-bold">Pembayaran Manual</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 z-10 relative">
        {{-- Kolom Kiri: Detail Transfer Bank & Ringkasan --}}
        @include('dashboard.customer.pesanan.partials._detail-transfer')

        {{-- Kolom Kanan: Form Upload Bukti atau Status Terkini --}}
        @include('dashboard.customer.pesanan.partials._status-actions')
    </div>
</div>

{{-- Skrip Interaktif, Preview Upload, dan Notifikasi Toast --}}
@include('dashboard.customer.pesanan.partials._show-scripts')
@endsection
