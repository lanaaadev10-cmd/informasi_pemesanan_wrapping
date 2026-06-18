<div class="relative overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">

    {{-- Top accent bar --}}
    <div class="h-[3px] bg-gradient-to-r from-primary-500 to-emerald-500"></div>

    <div class="p-6">

        {{-- Header: avatar + nama + tanggal --}}
        <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-primary-50 text-lg font-medium text-primary-600 dark:bg-primary-500/10 dark:text-primary-400">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div>
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white">
                        Selamat Datang, {{ auth()->user()->name }}!
                    </h2>
                    <div class="mt-1">
                        <span class="inline-flex items-center gap-1 rounded-full bg-primary-50 px-2.5 py-0.5 text-xs font-medium text-primary-600 dark:bg-primary-500/10 dark:text-primary-400">
                            <x-filament::icon name="heroicon-m-shield-check" class="h-3.5 w-3.5" />
                            {{ auth()->user()->hasRole('admin') ? 'Administrator' : 'User' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2 rounded-lg border border-gray-100 bg-gray-50 px-3.5 py-2 text-sm text-gray-500 dark:border-white/10 dark:bg-white/5 dark:text-gray-400">
                <x-filament::icon name="heroicon-m-calendar-days" class="h-4 w-4 text-gray-400" />
                {{ now()->locale('id')->translatedFormat('l, d F Y') }}
            </div>
        </div>

        {{-- Divider --}}
        <div class="mb-5 border-t border-gray-100 dark:border-white/10"></div>

        {{-- Action links --}}
        <div class="grid grid-cols-1 gap-2.5 sm:grid-cols-2">
            
                <a href="{{ $createUserUrl }}"
                   class="group flex items-center gap-3.5 rounded-lg border border-gray-100 bg-gray-50 px-4 py-3.5 transition-all hover:border-primary-200 hover:bg-primary-50 dark:border-white/10 dark:bg-white/5 dark:hover:border-primary-500/30 dark:hover:bg-primary-500/10"
            >
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-primary-50 text-primary-600 transition-colors group-hover:bg-primary-100 dark:bg-primary-500/10 dark:text-primary-400 dark:group-hover:bg-primary-500/20">
                    <x-filament::icon name="heroicon-m-user-plus" class="h-4.5 w-4.5" />
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-medium text-gray-900 dark:text-white">Buat Akun User Baru</p>
                    <p class="truncate text-xs text-gray-500 dark:text-gray-400">Untuk pelanggan offline / pesan langsung</p>
                </div>
                <x-filament::icon name="heroicon-m-arrow-right" class="h-4 w-4 shrink-0 text-gray-400 transition-transform group-hover:translate-x-0.5 group-hover:text-primary-500" />
            </a>

            
                <a href="{{ $listUserUrl }}"
                   class="group flex items-center gap-3.5 rounded-lg border border-gray-100 bg-gray-50 px-4 py-3.5 transition-all hover:border-emerald-200 hover:bg-emerald-50 dark:border-white/10 dark:bg-white/5 dark:hover:border-emerald-500/30 dark:hover:bg-emerald-500/10"
            >
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 transition-colors group-hover:bg-emerald-100 dark:bg-emerald-500/10 dark:text-emerald-400 dark:group-hover:bg-emerald-500/20">
                    <x-filament::icon name="heroicon-m-users" class="h-4.5 w-4.5" />
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-medium text-gray-900 dark:text-white">Lihat Semua User</p>
                    <p class="truncate text-xs text-gray-500 dark:text-gray-400">Kelola & edit data pengguna</p>
                </div>
                <x-filament::icon name="heroicon-m-arrow-right" class="h-4 w-4 shrink-0 text-gray-400 transition-transform group-hover:translate-x-0.5 group-hover:text-emerald-500" />
            </a>
        </div>

    </div>
</div>