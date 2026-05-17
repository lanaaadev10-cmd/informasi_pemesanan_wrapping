<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - {{ config('app.name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .sidebar-link-active { background-color: #ea580c; color: white !important; }
        
        /* Pengaturan Khusus agar Desktop tidak rusak oleh gaya HP */
        @media (min-width: 1024px) {
            #side-menu { transform: translateX(0) !important; }
            #side-overlay { display: none !important; }
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased">
    
    {{-- OVERLAY GELAP --}}
    <div id="side-overlay" onclick="tutupMenu()" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.6); backdrop-filter:blur(4px); z-index:9000;"></div>

    {{-- SIDEBAR --}}
    <aside id="side-menu" style="position:fixed; top:0; bottom:0; left:0; width:288px; background:white; border-right:1px solid #f3f4f6; z-index:9001; transition:0.3s; transform:translateX(-100%);" class="flex flex-col">
        
        <div class="p-6 flex justify-between items-center border-b border-gray-50">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-orange-600 rounded-lg flex items-center justify-center text-white font-bold">A</div>
                <span class="font-black text-orange-600 text-xl italic tracking-tighter">ALTRA</span>
            </div>
            {{-- TOMBOL TUTUP HP --}}
            <button onclick="tutupMenu()" class="lg:hidden w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                <i class="ph-bold ph-x"></i>
            </button>
        </div>

        <nav class="flex-grow p-6 space-y-2 overflow-y-auto">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-4 px-4 py-4 rounded-2xl font-bold text-sm {{ Request::routeIs('dashboard') ? 'sidebar-link-active' : 'text-gray-500 hover:bg-gray-50' }}">
                <i class="ph-bold ph-house text-xl"></i> Beranda
            </a>
            <a href="{{ route('profil.perusahaan') }}" class="flex items-center gap-4 px-4 py-4 rounded-2xl font-bold text-sm {{ Request::routeIs('profil.perusahaan') ? 'sidebar-link-active' : 'text-gray-500 hover:bg-gray-50' }}">
                <i class="ph-bold ph-buildings text-xl"></i> Profil
            </a>
            <a href="{{ route('katalog.user') }}" class="flex items-center gap-4 px-4 py-4 rounded-2xl font-bold text-sm {{ Request::routeIs('katalog.user') ? 'sidebar-link-active' : 'text-gray-500 hover:bg-gray-50' }}">
                <i class="ph-bold ph-tag text-xl"></i> Katalog
            </a>
            <a href="{{ route('galeri.user') }}" class="flex items-center gap-4 px-4 py-4 rounded-2xl font-bold text-sm {{ Request::routeIs('galeri.user') ? 'sidebar-link-active' : 'text-gray-500 hover:bg-gray-50' }}">
                <i class="ph-bold ph-image-square text-xl"></i> Galeri
            </a>
            <a href="{{ route('profile.edit') }}" class="flex items-center gap-4 px-4 py-4 rounded-2xl font-bold text-sm {{ Request::routeIs('profile.edit') ? 'sidebar-link-active' : 'text-gray-500 hover:bg-gray-50' }}">
                <i class="ph-bold ph-user-gear text-xl"></i> Akun
            </a>
        </nav>

        <div class="p-6 border-t">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full py-4 text-red-500 font-bold bg-red-50 rounded-2xl hover:bg-red-100 transition-colors">Keluar</button>
            </form>
        </div>
    </aside>

    <div class="lg:ml-72 min-h-screen flex flex-col">
        <header class="h-20 bg-white border-b px-6 flex items-center justify-between sticky top-0 z-[100]">
            {{-- TOMBOL BUKA HP --}}
            <button onclick="bukaMenu()" class="lg:hidden p-3 bg-gray-50 rounded-xl text-gray-900">
                <i class="ph-bold ph-list text-2xl"></i>
            </button>
            
            <div class="ml-auto flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <p class="text-xs font-bold text-gray-900 leading-none">{{ Auth::user()->name }}</p>
                    <p class="text-[9px] font-bold text-orange-600 uppercase mt-1">Premium Member</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-orange-600 text-white flex items-center justify-center font-bold">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
            </div>
        </header>

        <main class="p-6 md:p-8">
            @yield('content')
        </main>
    </div>

    <script>
        function bukaMenu() {
            document.getElementById('side-menu').style.transform = 'translateX(0)';
            document.getElementById('side-overlay').style.display = 'block';
            document.body.style.overflow = 'hidden';
        }
        function tutupMenu() {
            document.getElementById('side-menu').style.transform = 'translateX(-100%)';
            document.getElementById('side-overlay').style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    </script>
</body>
</html>
