<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="bg-white">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Dantie Sticker') }}</title>

        <!-- Scripts & Styles -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800;900&family=Questrial&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-questrial text-slate-800 antialiased bg-white min-h-screen">
        <div class="min-h-screen flex flex-col sm:justify-center items-center p-4 sm:p-6 bg-white">
            <div class="mb-6">
                <a href="/" class="text-2xl font-black tracking-tight font-montserrat">
                    <span class="text-slate-900 uppercase font-black">Dantie</span><span class="text-[#d35400] uppercase font-black">Sticker</span>
                </a>
            </div>

            <div class="w-full sm:max-w-md bg-white border border-slate-200 shadow-xl overflow-hidden rounded-2xl p-6 sm:p-8">
                {{ $slot }}
            </div>
            
            <div class="mt-6 text-center text-xs text-slate-400 font-montserrat">
                <a href="{{ route('login') }}" class="text-[#d35400] hover:underline font-semibold inline-flex items-center gap-1">
                    &larr; Kembali ke Login
                </a>
            </div>
        </div>
    </body>
</html>
