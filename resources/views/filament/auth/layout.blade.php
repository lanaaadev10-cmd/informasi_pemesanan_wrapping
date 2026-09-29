<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login Administrator - {{ $profil->nama_perusahaan ?? config('app.name', 'Dantie Sticker') }}</title>

    <!-- Preconnect Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Audiowide&family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,700&family=Questrial&display=swap" rel="stylesheet">

    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web" defer></script>

    <!-- Vite Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @filamentStyles
</head>
<body class="bg-[#f3f4f6] text-gray-800 antialiased font-questrial min-h-screen flex items-center justify-center p-4 sm:p-6 md:p-8 relative selection:bg-racing-orange selection:text-white overflow-x-hidden">

    <!-- Subtle Warm Ambient Lighting for Light Background -->
    <div class="fixed -top-24 -left-24 w-96 h-96 bg-orange-200/50 blur-[130px] rounded-full pointer-events-none"></div>
    <div class="fixed -bottom-24 -right-24 w-96 h-96 bg-amber-200/40 blur-[130px] rounded-full pointer-events-none"></div>

    <!-- Main Content Container -->
    <div class="relative z-10 w-full max-w-5xl mx-auto">
        {{ $slot }}
    </div>

    @filamentScripts
</body>
</html>
