<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

<<<<<<< HEAD
    <div class="p-6 text-gray-900 dark:text-gray-100">

    <h2 class="text-2xl font-bold mb-4">Katalog Layanan</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($layanan as $item)
            <div class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow">
                
                <h3 class="text-lg font-semibold mb-2">
                    {{ $item->nama_layanan }}
                </h3>

                <p class="text-sm mb-3">
                    {{ $item->deskripsi }}
                </p>

                @if($item->tipe_layanan == 'fix')
                    <p class="font-bold text-green-500">
                        Rp {{ number_format($item->harga) }}
                    </p>
                @else
                    <p class="font-bold text-yellow-500">
                        Harga Custom
                    </p>
                @endif

                <button class="mt-3 w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600">
                    Pesan Sekarang
                </button>

            </div>
        @endforeach
    </div>
</div>
</x-app-layout>
