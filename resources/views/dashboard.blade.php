<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="p-6">
    @if($profil)
        <h2 class="text-xl font-bold">{{ $profil->nama_perusahaan }}</h2>
        <p>{{ $profil->deskripsi }}</p>
        
        @if($profil->logo)
            <img src="{{ asset('storage/' . $profil->logo) }}" class="w-32 h-auto mt-2">
        @endif
    @else
        <p>Halo! Selamat datang di sistem kami.</p>
    @endif
</div>
</x-app-layout>
