{{--
    COMPONENT: secondary-button
    Tombol aksi sekunder, tema racing (ghost/outline).
    Menggantikan tema Breeze default (gray + dark: classes).

    Penggunaan:
        <x-secondary-button>Batal</x-secondary-button>
--}}
<button {{ $attributes->merge([
    'type'  => 'button',
    'class' => 'inline-flex items-center justify-center gap-2 px-6 py-2.5
                bg-white/5 hover:bg-white/10 border border-white/10
                hover:border-white/20 text-gray-300 hover:text-white
                font-semibold text-xs uppercase tracking-widest rounded-xl
                focus:outline-none focus:ring-2 focus:ring-white/20 focus:ring-offset-2 focus:ring-offset-racing-black
                disabled:opacity-25 transition ease-in-out duration-150',
]) }}>
    {{ $slot }}
</button>
