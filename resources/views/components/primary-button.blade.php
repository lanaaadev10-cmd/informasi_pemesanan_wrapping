{{--
    COMPONENT: primary-button
    Tombol submit utama, tema racing (oranye).
    Disamakan dengan design system proyek, menggantikan tema Breeze default.

    Penggunaan:
        <x-primary-button>Simpan</x-primary-button>
        <x-primary-button class="w-full">Submit</x-primary-button>
--}}
<button {{ $attributes->merge([
    'type'  => 'submit',
    'class' => 'inline-flex items-center justify-center gap-2 px-6 py-2.5
                bg-racing-orangeLight hover:bg-racing-orangeHover active:scale-95
                text-black font-extrabold text-xs uppercase tracking-widest
                rounded-xl border-transparent shadow-glow-orange
                focus:outline-none focus:ring-2 focus:ring-racing-orangeLight focus:ring-offset-2 focus:ring-offset-racing-black
                transition ease-in-out duration-150 disabled:opacity-50',
]) }}>
    {{ $slot }}
</button>
