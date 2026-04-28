<!DOCTYPE html>
<html lang="en">

<nav class="flex justify-between items-center p-6 bg-zinc-900 border-b border-zinc-800">
    <div class="text-orange-500 font-bold text-2xl">DANTIE STICKER</div>
    
    <div class="flex gap-4">
        @guest
            {{-- Muncul kalau belum login --}}
            <a href="{{ route('login') }}" class="text-white hover:text-orange-500">Login</a>
            <a href="{{ route('register') }}" class="bg-orange-600 text-white px-4 py-2 rounded-lg">Register</a>
        @endguest

        @auth
            {{-- Muncul kalau sudah login --}}
            <span class="text-white">Halo, {{ Auth::user()->name }}</span>
            <a href="/profile" class="text-gray-400 hover:text-white">Cek Profil</a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="text-red-500">Logout</button>
            </form>
        @endauth
    </div>
</nav>

<header>
    {{-- Menampilkan Logo --}}
    <img src="{{ asset('storage/' . $profil->logo) }}" alt="Logo">

    {{-- Menampilkan Nama & Deskripsi --}}
    <h1>{{ $profil->nama_perusahaan ?? 'Nama Toko' }}</h1>
    <p>{{ $profil->deskripsi }}</p>

    {{-- Info Kontak --}}
    <ul>
        <li>Alamat: {{ $profil->alamat }}</li>
        <li>Email: {{ $profil->email }}</li>
        <li>WA: {{ $profil->nomor_telepon }}</li>
    </ul>
</header>

<hr>

<section>
    <h2>Katalog Layanan Kami</h2>
    <div class="katalog-container">
        @foreach($layanans as $layanan)
            <div class="card">
                {{-- Nama & Tipe Layanan --}}
                <h3>{{ $layanan->nama_layanan }} ({{ $layanan->tipe_layanan }})</h3>
                
                {{-- Deskripsi Layanan --}}
                <p>{{ $layanan->deskripsi }}</p>

                {{-- Harga Format Rupiah --}}
                <span>Rp {{ number_format($layanan->harga, 0, ',', '.') }}</span>
                
                <button>Pesan Sekarang</button>
            </div>
        @endforeach
    </div>
</section>

    <footer class="bg-gray-900 text-gray-500 py-10 text-center border-t border-gray-800">
        <p>&copy; 2026 {{ $profil->nama_perusahaan }}. All Rights Reserved.</p>
    </footer>

</body>
</html>