<!-- Halo Greeting Header -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h1 class="text-3xl font-extrabold text-white tracking-tight">
            {{ $profil->dashboard_title ?? 'Halo' }}, {{ Auth::user()->name }}
        </h1>
        <p class="text-xs text-gray-400 font-light mt-1">
            {{ $profil->dashboard_subtitle ?? 'Pantau status pengerjaan kendaraan premium Anda di sini.' }}
        </p>
    </div>
</div>
