<x-filament-panels::page>
    <div class="space-y-6" x-data="{ photoModal: @entangle('isPhotoModalOpen') }">

        {{-- Header Summary Bar --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
            <div class="p-4 rounded-xl border border-amber-200 bg-amber-50 dark:bg-amber-950/20 dark:border-amber-900/40">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-amber-700 dark:text-amber-400">1. Antrean Masuk</span>
                    <span class="px-2 py-0.5 rounded-full bg-amber-200 text-amber-900 font-extrabold text-xs">{{ $pendingBookings->count() }}</span>
                </div>
                <p class="text-xs text-amber-800/80 dark:text-amber-300/80 mt-1">Menunggu konfirmasi & bukti transfer.</p>
            </div>

            <div class="p-4 rounded-xl border border-sky-200 bg-sky-50 dark:bg-sky-950/20 dark:border-sky-900/40">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-sky-700 dark:text-sky-400">2. Siap Masuk Workshop</span>
                    <span class="px-2 py-0.5 rounded-full bg-sky-200 text-sky-900 font-extrabold text-xs">{{ $approvedBookings->count() }}</span>
                </div>
                <p class="text-xs text-sky-800/80 dark:text-sky-300/80 mt-1">Jadwal terkunci, siap masuk bay.</p>
            </div>

            <div class="p-4 rounded-xl border border-orange-200 bg-orange-50 dark:bg-orange-950/20 dark:border-orange-900/40">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-orange-700 dark:text-orange-400">3. Sedang Dikerjakan (WIP)</span>
                    <span class="px-2 py-0.5 rounded-full bg-orange-200 text-orange-900 font-extrabold text-xs">{{ $inProgressBookings->count() }}</span>
                </div>
                <p class="text-xs text-orange-800/80 dark:text-orange-300/80 mt-1">Tahap instalasi wrapping & detailing.</p>
            </div>

            <div class="p-4 rounded-xl border border-emerald-200 bg-emerald-50 dark:bg-emerald-950/20 dark:border-emerald-900/40">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-400">4. Siap Diambil / Selesai</span>
                    <span class="px-2 py-0.5 rounded-full bg-emerald-200 text-emerald-900 font-extrabold text-xs">{{ $completedBookings->count() }}</span>
                </div>
                <p class="text-xs text-emerald-800/80 dark:text-emerald-300/80 mt-1">QC lulus, E-Warranty aktif.</p>
            </div>
        </div>

        {{-- 4-Column Kanban Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5 items-start">

            {{-- ── KOLOM 1: ANTREAN MASUK ── --}}
            <div class="bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 flex flex-col min-h-[500px]">
                <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-3 mb-3">
                    <h3 class="font-bold text-sm text-slate-800 dark:text-slate-200 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                        Antrean Konfirmasi
                    </h3>
                    <span class="text-xs font-bold text-slate-500">{{ $pendingBookings->count() }} unit</span>
                </div>

                <div class="space-y-3 flex-1 overflow-y-auto max-h-[700px] pr-1">
                    @forelse($pendingBookings as $b)
                        <div class="bg-white dark:bg-slate-800 p-3.5 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow transition-all space-y-2.5">
                            <div class="flex items-start justify-between gap-2">
                                <span class="font-mono font-bold text-xs text-orange-600 dark:text-orange-400">{{ $b->booking_code }}</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300">
                                    {{ $b->status->label() }}
                                </span>
                            </div>

                            <div>
                                <h4 class="font-bold text-sm text-slate-900 dark:text-white">{{ $b->vehicle_name }}</h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400 font-mono">{{ $b->vehicle_license ?: 'Plat -' }} • {{ $b->customer_name }}</p>
                            </div>

                            <div class="text-[11px] text-slate-600 dark:text-slate-300 bg-slate-50 dark:bg-slate-900/80 p-2 rounded-lg">
                                <p class="font-semibold truncate">{{ $b->service_name_snapshot ?: ($b->layanan?->nama_layanan ?? 'Custom') }}</p>
                                <p class="text-slate-400 text-[10px]">Jadwal: {{ $b->booking_date?->format('d M Y') }} ({{ $b->booking_time ?: '09:00' }})</p>
                            </div>

                            {{-- Actions --}}
                            <div class="pt-1 flex items-center gap-2 flex-wrap">
                                <button type="button" wire:click="advanceToApproved({{ $b->id }})" wire:loading.attr="disabled"
                                        class="px-2.5 py-1.5 rounded-lg bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs flex-1 text-center transition-colors shadow-sm">
                                    ✓ Setujui Slot
                                </button>
                                <a href="{{ $this->getWhatsAppUrl($b, 'awaiting') }}" target="_blank"
                                   class="p-1.5 rounded-lg bg-emerald-500 hover:bg-emerald-600 text-white transition-colors title='Kirim WA Tagihan'">
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-xs text-slate-400">Tidak ada antrean tertunda.</div>
                    @endforelse
                </div>
            </div>

            {{-- ── KOLOM 2: SIAP MASUK WORKSHOP ── --}}
            <div class="bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 flex flex-col min-h-[500px]">
                <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-3 mb-3">
                    <h3 class="font-bold text-sm text-slate-800 dark:text-slate-200 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-sky-500"></span>
                        Siap Masuk Workshop
                    </h3>
                    <span class="text-xs font-bold text-slate-500">{{ $approvedBookings->count() }} unit</span>
                </div>

                <div class="space-y-3 flex-1 overflow-y-auto max-h-[700px] pr-1">
                    @forelse($approvedBookings as $b)
                        <div class="bg-white dark:bg-slate-800 p-3.5 rounded-xl border border-sky-200 dark:border-sky-900/60 shadow-sm hover:shadow transition-all space-y-2.5">
                            <div class="flex items-start justify-between gap-2">
                                <span class="font-mono font-bold text-xs text-sky-600 dark:text-sky-400">{{ $b->booking_code }}</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-sky-100 text-sky-800 dark:bg-sky-950 dark:text-sky-300">
                                    Disetujui
                                </span>
                            </div>

                            <div>
                                <h4 class="font-bold text-sm text-slate-900 dark:text-white">{{ $b->vehicle_name }}</h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400 font-mono">{{ $b->vehicle_license ?: 'Plat -' }} • {{ $b->customer_name }}</p>
                            </div>

                            <div class="text-[11px] text-slate-600 dark:text-slate-300 bg-sky-50/50 dark:bg-sky-950/30 p-2 rounded-lg border border-sky-100 dark:border-sky-900/30">
                                <p class="font-semibold truncate">{{ $b->service_name_snapshot ?: ($b->layanan?->nama_layanan ?? 'Wrapping') }}</p>
                                <p class="text-sky-700 dark:text-sky-400 text-[10px] font-bold">Jadwal: {{ $b->booking_date?->format('d M Y') }} • {{ $b->booking_time ?: '09:00' }}</p>
                            </div>

                            {{-- Actions --}}
                            <div class="pt-1 flex items-center gap-2 flex-wrap">
                                <button type="button" wire:click="advanceToInProgress({{ $b->id }})" wire:loading.attr="disabled"
                                        class="px-2.5 py-1.5 rounded-lg bg-orange-600 hover:bg-orange-700 text-white font-bold text-xs flex-1 text-center transition-colors shadow-sm">
                                    ➔ Mulai Pemasangan
                                </button>
                                <a href="{{ $this->getWhatsAppUrl($b, 'reminder') }}" target="_blank"
                                   class="p-1.5 rounded-lg bg-emerald-500 hover:bg-emerald-600 text-white transition-colors" title="Kirim Pengingat WA">
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-xs text-slate-400">Belum ada mobil yang siap masuk.</div>
                    @endforelse
                </div>
            </div>

            {{-- ── KOLOM 3: SEDANG DIKERJAKAN (WIP) ── --}}
            <div class="bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 flex flex-col min-h-[500px]">
                <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-3 mb-3">
                    <h3 class="font-bold text-sm text-slate-800 dark:text-slate-200 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-orange-500 animate-pulse"></span>
                        Sedang Dikerjakan (WIP)
                    </h3>
                    <span class="text-xs font-bold text-slate-500">{{ $inProgressBookings->count() }} unit</span>
                </div>

                <div class="space-y-3 flex-1 overflow-y-auto max-h-[700px] pr-1">
                    @forelse($inProgressBookings as $b)
                        <div class="bg-white dark:bg-slate-800 p-3.5 rounded-xl border-2 border-orange-300 dark:border-orange-800/80 shadow-md space-y-2.5">
                            <div class="flex items-start justify-between gap-2">
                                <span class="font-mono font-bold text-xs text-orange-600 dark:text-orange-400">{{ $b->booking_code }}</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-orange-100 text-orange-800 dark:bg-orange-950 dark:text-orange-300 flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>
                                    Instalasi
                                </span>
                            </div>

                            <div>
                                <h4 class="font-bold text-sm text-slate-900 dark:text-white">{{ $b->vehicle_name }}</h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400 font-mono">{{ $b->vehicle_license ?: 'Plat -' }} • {{ $b->customer_name }}</p>
                            </div>

                            {{-- Info Progress Photo Count --}}
                            <div class="flex items-center justify-between text-[11px] bg-orange-50 dark:bg-orange-950/40 p-2 rounded-lg text-orange-800 dark:text-orange-300 border border-orange-100 dark:border-orange-900/30">
                                <span class="font-bold flex items-center gap-1">
                                    📸 {{ $b->progressPhotos->count() }} Foto Diunggah
                                </span>
                                <button type="button" wire:click="openPhotoModal({{ $b->id }})"
                                        class="text-[10px] font-extrabold underline text-orange-600 hover:text-orange-800 dark:text-orange-400">
                                    + Tambah Foto
                                </button>
                            </div>

                            {{-- Actions --}}
                            <div class="pt-1 flex items-center gap-2 flex-wrap">
                                <button type="button" wire:click="advanceToCompleted({{ $b->id }})" wire:loading.attr="disabled"
                                        class="px-2.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs flex-1 text-center transition-colors shadow-sm">
                                    ★ Selesai & Terbit Garansi
                                </button>
                                <a href="{{ $this->getWhatsAppUrl($b, 'progress') }}" target="_blank"
                                   class="p-1.5 rounded-lg bg-emerald-500 hover:bg-emerald-600 text-white transition-colors" title="Kirim Update WA">
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-xs text-slate-400">Tidak ada pengerjaan aktif saat ini.</div>
                    @endforelse
                </div>
            </div>

            {{-- ── KOLOM 4: SELESAI & SIAP DIAMBIL ── --}}
            <div class="bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 flex flex-col min-h-[500px]">
                <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-3 mb-3">
                    <h3 class="font-bold text-sm text-slate-800 dark:text-slate-200 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                        Selesai & Siap Diambil
                    </h3>
                    <span class="text-xs font-bold text-slate-500">{{ $completedBookings->count() }} unit</span>
                </div>

                <div class="space-y-3 flex-1 overflow-y-auto max-h-[700px] pr-1">
                    @forelse($completedBookings as $b)
                        <div class="bg-white dark:bg-slate-800 p-3.5 rounded-xl border border-emerald-200 dark:border-emerald-900/60 shadow-sm space-y-2.5">
                            <div class="flex items-start justify-between gap-2">
                                <span class="font-mono font-bold text-xs text-emerald-600 dark:text-emerald-400">{{ $b->booking_code }}</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">
                                    Selesai
                                </span>
                            </div>

                            <div>
                                <h4 class="font-bold text-sm text-slate-900 dark:text-white">{{ $b->vehicle_name }}</h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400 font-mono">{{ $b->vehicle_license ?: 'Plat -' }} • {{ $b->customer_name }}</p>
                            </div>

                            <div class="text-[11px] text-emerald-800 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/40 p-2 rounded-lg border border-emerald-100 dark:border-emerald-900/40">
                                <p class="font-bold">🛡️ E-Warranty Aktif 12 Bulan</p>
                                <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5">Kode: {{ $b->warranty?->warranty_code ?? 'Auto-Issued' }}</p>
                            </div>

                            {{-- Actions --}}
                            <div class="pt-1 flex items-center gap-2 flex-wrap">
                                <a href="{{ route('booking.garansi', $b->id) }}" target="_blank"
                                   class="px-2.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs flex-1 text-center transition-colors shadow-sm">
                                    Unduh Garansi PDF
                                </a>
                                <a href="{{ $this->getWhatsAppUrl($b, 'ready') }}" target="_blank"
                                   class="p-1.5 rounded-lg bg-emerald-500 hover:bg-emerald-600 text-white transition-colors" title="Kirim WA Siap Diambil">
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-xs text-slate-400">Belum ada unit yang selesai.</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ── MODAL UPLOAD FOTO PROGRES ── --}}
        <div x-show="photoModal" x-cloak
             class="fixed inset-0 z-50 overflow-y-auto bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4"
                 @click.outside="$wire.closePhotoModal()">
                
                <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3">
                    <h3 class="font-bold text-base text-slate-900 dark:text-white flex items-center gap-2">
                        📸 Upload Foto Progres Workshop
                    </h3>
                    <button type="button" wire:click="closePhotoModal" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form wire:submit.prevent="saveProgressPhoto" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Tahap Pengerjaan</label>
                        <select wire:model="photoStage" class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 p-2.5">
                            <option value="Pembersihan & Detailing">1. Pembersihan & Detailing Bodi</option>
                            <option value="Bongkar Komponen / Handle">2. Pembongkaran Handle & Aksesoris</option>
                            <option value="Pemasangan Vinyl / Wrapping">3. Pemasangan Vinyl Utama</option>
                            <option value="Finishing & Pemanasan Sudut">4. Finishing & Pemanasan Sudut</option>
                            <option value="Quality Check (QC) Akhir">5. Quality Check (QC) Akhir</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Pilih Foto (Kamera HP / Galeri)</label>
                        <input type="file" wire:model="progressPhotoFile" accept="image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
                        <div wire:loading wire:target="progressPhotoFile" class="text-xs text-orange-600 mt-1 font-semibold">Mengunggah file...</div>
                        @error('progressPhotoFile') <span class="text-xs text-red-500 block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Catatan Tambahan (Opsional)</label>
                        <textarea wire:model="photoCaption" rows="2" placeholder="Contoh: Pemasangan sisi kiri bodi selesai, lem rapat tanpa sambungan."
                                  class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 p-2.5"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
                        <button type="button" wire:click="closePhotoModal"
                                class="px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-700 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800">
                            Batal
                        </button>
                        <button type="submit" wire:loading.attr="disabled"
                                class="px-4 py-2 rounded-xl bg-orange-600 hover:bg-orange-700 text-white text-xs font-bold shadow-md shadow-orange-600/20">
                            Simpan & Kirim ke Customer
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-filament-panels::page>
