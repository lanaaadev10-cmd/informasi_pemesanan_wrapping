@extends('layouts.tampilan_utama')

@section('title', 'Kebijakan Privasi')

@section('content')
    @php
        $accentColor = '#f2994a';
    @endphp

    <!-- HEADER SECTION -->
    <div class="max-w-4xl mx-auto w-full px-4 sm:px-6 lg:px-8 pb-8 text-center">
        <span class="inline-block px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest bg-[#f2994a]/10 text-[#f2994a] border border-[#f2994a]/20 mb-6">Legal</span>
        <h1 class="text-4xl sm:text-5xl font-extrabold text-white tracking-tight mb-4">Kebijakan Privasi</h1>
        <p class="text-gray-400 leading-relaxed max-w-2xl mx-auto">
            Kami menghargai privasi Anda. Halaman ini menjelaskan bagaimana {{ \App\Helpers\StaticContent::APP_NAME }} mengumpulkan, menggunakan, dan melindungi data pribadi Anda.
        </p>
        <p class="text-gray-500 text-xs mt-6 italic">Terakhir diperbarui: {{ now()->translatedFormat('d F Y') }}</p>
    </div>

    <!-- MAIN CONTENT -->
    <div class="max-w-4xl mx-auto w-full px-4 sm:px-6 lg:px-8 pb-24 space-y-10">

        <div class="bg-white/[0.03] border border-white/10 rounded-2xl p-8 space-y-4">
            <h2 class="text-xl font-bold text-white flex items-center gap-3">
                <span class="w-8 h-8 rounded-lg bg-[#f2994a]/15 text-[#f2994a] flex items-center justify-center text-sm font-extrabold">1</span>
                Informasi yang Kami Kumpulkan
            </h2>
            <p class="text-gray-400 text-sm leading-relaxed">
                Saat Anda menggunakan situs dan layanan kami, kami dapat mengumpulkan informasi berikut:
            </p>
            <ul class="text-gray-400 text-sm leading-relaxed list-disc pl-5 space-y-2">
                <li><strong class="text-gray-300">Data akun:</strong> nama lengkap, alamat email, dan nomor telepon saat Anda mendaftar.</li>
                <li><strong class="text-gray-300">Data kendaraan & pesanan:</strong> detail kendaraan serta riwayat layanan wrapping yang Anda pesan.</li>
                <li><strong class="text-gray-300">Data pembayaran:</strong> bukti transfer dan informasi transaksi terkait pesanan Anda.</li>
                <li><strong class="text-gray-300">Data teknis:</strong> alamat IP, jenis perangkat, dan browser yang digunakan untuk mengakses situs.</li>
            </ul>
        </div>

        <div class="bg-white/[0.03] border border-white/10 rounded-2xl p-8 space-y-4">
            <h2 class="text-xl font-bold text-white flex items-center gap-3">
                <span class="w-8 h-8 rounded-lg bg-[#f2994a]/15 text-[#f2994a] flex items-center justify-center text-sm font-extrabold">2</span>
                Penggunaan Informasi
            </h2>
            <ul class="text-gray-400 text-sm leading-relaxed list-disc pl-5 space-y-2">
                <li>Memproses pesanan layanan wrapping dan komunikasi terkait pengerjaan.</li>
                <li>Mengirimkan informasi status pesanan, invoice, dan pemberitahuan penting lainnya.</li>
                <li>Menyediakan dukungan pelanggan serta menangani keluhan atau pertanyaan.</li>
                <li>Meningkatkan kualitas layanan berdasarkan analisis penggunaan situs.</li>
            </ul>
            <p class="text-gray-400 text-sm leading-relaxed">
                Kami <strong class="text-gray-300">tidak</strong> menjual, menyewakan, atau membagikan data pribadi Anda kepada pihak ketiga untuk keperluan pemasaran.
            </p>
        </div>

        <div class="bg-white/[0.03] border border-white/10 rounded-2xl p-8 space-y-4">
            <h2 class="text-xl font-bold text-white flex items-center gap-3">
                <span class="w-8 h-8 rounded-lg bg-[#f2994a]/15 text-[#f2994a] flex items-center justify-center text-sm font-extrabold">3</span>
                Perlindungan Data
            </h2>
            <p class="text-gray-400 text-sm leading-relaxed">
                Kami menerapkan langkah keamanan teknis dan organisatoris yang wajar untuk melindungi data pribadi Anda dari akses, pengubahan, pengungkapan, atau pemusnahan yang tidak sah. Akses ke data hanya diberikan kepada personel yang berwenang.
            </p>
        </div>

        <div class="bg-white/[0.03] border border-white/10 rounded-2xl p-8 space-y-4">
            <h2 class="text-xl font-bold text-white flex items-center gap-3">
                <span class="w-8 h-8 rounded-lg bg-[#f2994a]/15 text-[#f2994a] flex items-center justify-center text-sm font-extrabold">4</span>
                Hak Anda
            </h2>
            <ul class="text-gray-400 text-sm leading-relaxed list-disc pl-5 space-y-2">
                <li>Meminta akses dan salinan data pribadi yang kami simpan.</li>
                <li>Meminta perbaikan atas data yang tidak akurat atau tidak lengkap.</li>
                <li>Meminta penghapusan data pribadi dalam kondisi tertentu.</li>
                <li>Tarik persetujuan atas penggunaan data untuk tujuan tertentu.</li>
            </ul>
        </div>

        <div class="bg-white/[0.03] border border-white/10 rounded-2xl p-8 space-y-4">
            <h2 class="text-xl font-bold text-white flex items-center gap-3">
                <span class="w-8 h-8 rounded-lg bg-[#f2994a]/15 text-[#f2994a] flex items-center justify-center text-sm font-extrabold">5</span>
                Perubahan Kebijakan
            </h2>
            <p class="text-gray-400 text-sm leading-relaxed">
                Kebijakan privasi ini dapat kami perbarui dari waktu ke waktu. Setiap perubahan akan dipublikasikan pada halaman ini beserta tanggal pembaruannya. Kami menyarankan Anda meninjau halaman ini secara berkala.
            </p>
        </div>

        <div class="bg-white/[0.03] border border-white/10 rounded-2xl p-8 space-y-4">
            <h2 class="text-xl font-bold text-white flex items-center gap-3">
                <span class="w-8 h-8 rounded-lg bg-[#f2994a]/15 text-[#f2994a] flex items-center justify-center text-sm font-extrabold">6</span>
                Hubungi Kami
            </h2>
            <p class="text-gray-400 text-sm leading-relaxed">
                Jika Anda memiliki pertanyaan mengenai kebijakan privasi ini, silakan hubungi kami melalui email
                <a href="mailto:{{ \App\Helpers\StaticContent::COMPANY_EMAIL }}" class="text-[#f2994a] hover:underline">{{ \App\Helpers\StaticContent::COMPANY_EMAIL }}</a>
                atau WhatsApp di
                <a href="{{ \App\Helpers\StaticContent::COMPANY_WHATSAPP }}" target="_blank" rel="noopener" class="text-[#f2994a] hover:underline">{{ \App\Helpers\StaticContent::COMPANY_PHONE }}</a>.
            </p>
        </div>

        <!-- CTA BACK -->
        <div class="pt-4 text-center">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-gradient-to-r from-[#e28a44] to-[#f2994a] text-white text-sm font-semibold hover:opacity-90 transition-all">
                <i class="ph-bold ph-arrow-left"></i> Kembali ke Beranda
            </a>
        </div>
    </div>
@endsection
