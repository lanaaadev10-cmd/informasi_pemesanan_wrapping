@extends('layouts.tampilan-utama')

@section('title', 'Kebijakan Privasi')

@section('content')
    {{-- Header Section --}}
    <div class="max-w-4xl mx-auto w-full px-4 sm:px-6 lg:px-8 pb-8 text-center" data-aos="fade-up">
        <span class="inline-block px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest bg-orange-50 text-[#ea580c] border border-orange-200 mb-4">
            Legal &amp; Transparansi
        </span>
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-slate-900 tracking-tight mb-4 font-montserrat">
            Kebijakan Privasi
        </h1>
        <p class="text-slate-600 leading-relaxed max-w-2xl mx-auto text-sm sm:text-base font-normal">
            Kami menghargai privasi dan kepercayaan Anda. Halaman ini menjelaskan bagaimana {{ \App\Helpers\StaticContent::APP_NAME }} mengumpulkan, menggunakan, dan melindungi data pribadi Anda.
        </p>
        <p class="text-slate-400 text-xs mt-3 italic">
            Terakhir diperbarui: {{ now()->translatedFormat('d F Y') }}
        </p>
    </div>

    {{-- Main Legal Articles --}}
    <div class="max-w-4xl mx-auto w-full px-4 sm:px-6 lg:px-8 pb-20 sm:pb-24 space-y-6 sm:space-y-8">

        {{-- 1. Informasi yang Dikumpulkan --}}
        <div class="bg-white border border-slate-200/90 rounded-2xl sm:rounded-3xl p-5 sm:p-8 space-y-4 shadow-sm hover:border-slate-300 transition-all" data-aos="fade-up">
            <h2 class="text-lg sm:text-xl font-bold font-montserrat text-slate-900 flex items-center gap-3">
                <span class="w-8 h-8 rounded-xl bg-orange-50 text-[#ea580c] border border-orange-200 flex items-center justify-center text-sm font-black shrink-0">1</span>
                Informasi yang Kami Kumpulkan
            </h2>
            <p class="text-slate-600 text-xs sm:text-sm leading-relaxed font-normal">
                Saat Anda menggunakan situs dan layanan kami, kami dapat mengumpulkan informasi berikut:
            </p>
            <ul class="text-slate-600 text-xs sm:text-sm leading-relaxed list-disc pl-5 space-y-2">
                <li><strong class="text-slate-900 font-semibold">Data akun:</strong> nama lengkap, alamat email, dan nomor telepon saat Anda mendaftar atau berkonsultasi.</li>
                <li><strong class="text-slate-900 font-semibold">Data kendaraan &amp; pesanan:</strong> detail jenis kendaraan serta riwayat layanan wrapping yang Anda pesan.</li>
                <li><strong class="text-slate-900 font-semibold">Data pembayaran:</strong> bukti transfer dan informasi transaksi terkait pesanan Anda.</li>
                <li><strong class="text-slate-900 font-semibold">Data teknis:</strong> alamat IP, jenis perangkat, dan browser yang digunakan untuk mengakses situs.</li>
            </ul>
        </div>

        {{-- 2. Penggunaan Informasi --}}
        <div class="bg-white border border-slate-200/90 rounded-2xl sm:rounded-3xl p-5 sm:p-8 space-y-4 shadow-sm hover:border-slate-300 transition-all" data-aos="fade-up">
            <h2 class="text-lg sm:text-xl font-bold font-montserrat text-slate-900 flex items-center gap-3">
                <span class="w-8 h-8 rounded-xl bg-orange-50 text-[#ea580c] border border-orange-200 flex items-center justify-center text-sm font-black shrink-0">2</span>
                Penggunaan Informasi
            </h2>
            <ul class="text-slate-600 text-xs sm:text-sm leading-relaxed list-disc pl-5 space-y-2">
                <li>Memproses pesanan layanan wrapping dan komunikasi teknis terkait pengerjaan.</li>
                <li>Mengirimkan informasi status pengerjaan, faktur resmi, dan pemberitahuan penting.</li>
                <li>Menyediakan dukungan pelanggan serta menangani konsultasi bahan dan garansi.</li>
                <li>Meningkatkan kualitas layanan berdasarkan analisis kepuasan dan performa situs.</li>
            </ul>
            <p class="text-slate-600 text-xs sm:text-sm leading-relaxed font-normal pt-2 border-t border-slate-100">
                Kami <strong class="text-slate-900 font-semibold">tidak</strong> pernah menjual, menyewakan, atau membagikan data pribadi Anda kepada pihak ketiga untuk keperluan periklanan tanpa izin Anda.
            </p>
        </div>

        {{-- 3. Perlindungan Data --}}
        <div class="bg-white border border-slate-200/90 rounded-2xl sm:rounded-3xl p-5 sm:p-8 space-y-4 shadow-sm hover:border-slate-300 transition-all" data-aos="fade-up">
            <h2 class="text-lg sm:text-xl font-bold font-montserrat text-slate-900 flex items-center gap-3">
                <span class="w-8 h-8 rounded-xl bg-orange-50 text-[#ea580c] border border-orange-200 flex items-center justify-center text-sm font-black shrink-0">3</span>
                Perlindungan &amp; Keamanan Data
            </h2>
            <p class="text-slate-600 text-xs sm:text-sm leading-relaxed font-normal">
                Kami menerapkan langkah keamanan teknis dan organisatoris yang ketat untuk melindungi data pribadi Anda dari akses, pengubahan, pengungkapan, atau pemusnahan yang tidak sah. Akses ke data pelanggan hanya diberikan kepada teknisi dan manajemen workshop yang berwenang.
            </p>
        </div>

        {{-- 4. Hak Pelanggan --}}
        <div class="bg-white border border-slate-200/90 rounded-2xl sm:rounded-3xl p-5 sm:p-8 space-y-4 shadow-sm hover:border-slate-300 transition-all" data-aos="fade-up">
            <h2 class="text-lg sm:text-xl font-bold font-montserrat text-slate-900 flex items-center gap-3">
                <span class="w-8 h-8 rounded-xl bg-orange-50 text-[#ea580c] border border-orange-200 flex items-center justify-center text-sm font-black shrink-0">4</span>
                Hak Anda Sebagai Pelanggan
            </h2>
            <ul class="text-slate-600 text-xs sm:text-sm leading-relaxed list-disc pl-5 space-y-2">
                <li>Meminta akses dan ringkasan data pengerjaan serta riwayat kendaraan Anda.</li>
                <li>Meminta perbaikan atas data kontak atau informasi kendaraan yang keliru.</li>
                <li>Meminta penghapusan data akun jika seluruh garansi dan kewajiban telah selesai.</li>
                <li>Menarik persetujuan komunikasi berkala sewaktu-waktu.</li>
            </ul>
        </div>

        {{-- 5. Perubahan Kebijakan --}}
        <div class="bg-white border border-slate-200/90 rounded-2xl sm:rounded-3xl p-5 sm:p-8 space-y-4 shadow-sm hover:border-slate-300 transition-all" data-aos="fade-up">
            <h2 class="text-lg sm:text-xl font-bold font-montserrat text-slate-900 flex items-center gap-3">
                <span class="w-8 h-8 rounded-xl bg-orange-50 text-[#ea580c] border border-orange-200 flex items-center justify-center text-sm font-black shrink-0">5</span>
                Pembaruan Kebijakan
            </h2>
            <p class="text-slate-600 text-xs sm:text-sm leading-relaxed font-normal">
                Kebijakan privasi ini dapat kami perbarui sewaktu-waktu sesuai dengan perkembangan layanan dan regulasi hukum yang berlaku. Setiap perubahan akan diumumkan pada halaman ini dengan tanggal pembaruan yang transparan.
            </p>
        </div>

        {{-- 6. Hubungi Kami --}}
        <div class="bg-white border border-slate-200/90 rounded-2xl sm:rounded-3xl p-5 sm:p-8 space-y-4 shadow-sm hover:border-slate-300 transition-all" data-aos="fade-up">
            <h2 class="text-lg sm:text-xl font-bold font-montserrat text-slate-900 flex items-center gap-3">
                <span class="w-8 h-8 rounded-xl bg-orange-50 text-[#ea580c] border border-orange-200 flex items-center justify-center text-sm font-black shrink-0">6</span>
                Kontak Layanan Privasi
            </h2>
            <p class="text-slate-600 text-xs sm:text-sm leading-relaxed font-normal">
                Apabila Anda memiliki pertanyaan atau kendala terkait privasi data, tim workshop kami siap melayani melalui email resmi
                <a href="mailto:{{ \App\Helpers\StaticContent::COMPANY_EMAIL }}" class="text-[#ea580c] font-semibold hover:underline">{{ \App\Helpers\StaticContent::COMPANY_EMAIL }}</a>
                atau melalui WhatsApp di
                <a href="{{ \App\Helpers\StaticContent::COMPANY_WHATSAPP }}" target="_blank" rel="noopener" class="text-[#ea580c] font-semibold hover:underline">{{ \App\Helpers\StaticContent::COMPANY_PHONE }}</a>.
            </p>
        </div>

        {{-- CTA Back to Home --}}
        <div class="pt-4 text-center">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-7 py-3.5 rounded-full bg-gradient-to-r from-[#ea580c] to-[#f97316] text-white text-xs font-montserrat font-medium shadow-md shadow-orange-500/20 hover:brightness-105 active:scale-95 transition-all">
                <i class="ph-bold ph-arrow-left text-sm"></i>
                <span>Kembali ke Beranda</span>
            </a>
        </div>

    </div>
@endsection
