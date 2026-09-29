{{--
    COMPONENT: danger-button
    Tombol aksi destruktif (hapus akun, konfirmasi berbahaya).
    Menggantikan tema Breeze default (membuang dark:focus:ring-offset-gray-800).

    Penggunaan:
        <x-danger-button>Hapus Akun</x-danger-button>
--}}
<button {{ $attributes->merge([
    'type'  => 'submit',
    'class' => 'inline-flex items-center justify-center gap-2 px-6 py-2.5
                bg-red-600 hover:bg-red-500 active:bg-red-700 border border-transparent
                text-white font-semibold text-xs uppercase tracking-widest rounded-xl
                focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:ring-offset-racing-black
                transition ease-in-out duration-150 disabled:opacity-50',
]) }}>
    {{ $slot }}
</button>
