{{-- ── MODAL DETAIL BOOKING PER HARI ── --}}
<div x-show="selectedDateDetail !== null"
     x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm">
    <div @click.away="selectedDateDetail = null"
         class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-3xl max-w-xl w-full p-6 shadow-2xl space-y-5">
        <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-3">
            <div>
                <span class="text-xs font-bold uppercase text-primary-500">Detail Jadwal &amp; Slot</span>
                <h3 class="text-lg font-black text-gray-900 dark:text-white" x-text="selectedDateTitle"></h3>
            </div>
            <button type="button" @click="selectedDateDetail = null" class="p-1 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-400">
                <svg width="20" height="20" style="width: 20px; height: 20px; min-width: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        {{-- Quota status pill --}}
        <div class="p-4 rounded-2xl bg-gray-50 dark:bg-gray-800/50 flex items-center justify-between text-xs">
            <div>
                <span class="text-gray-500 block">Total Digunakan:</span>
                <span class="font-black text-sm text-gray-900 dark:text-white" x-text="(selectedDateQuota?.total_used ?? 0) + ' dari 5 Slot Maksimal'"></span>
            </div>
            <div>
                <span class="text-gray-500 block">Slot Tersisa:</span>
                <span class="font-black text-sm text-emerald-600 dark:text-emerald-400" x-text="(selectedDateQuota?.available ?? 5) + ' Slot'"></span>
            </div>
        </div>

        {{-- List Slot (Booking + Pesanan) on that day --}}
        <div class="space-y-3 max-h-80 overflow-y-auto">
            <template x-if="daySlots.length === 0">
                <p class="text-xs text-gray-400 text-center py-6">Belum ada booking/pesanan pada tanggal ini.</p>
            </template>

            <template x-for="(b, idx) in daySlots" :key="idx">
                <div class="p-3.5 rounded-2xl border border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-800 flex items-center justify-between gap-3 text-xs">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="text-[9px] font-black uppercase px-1.5 py-0.5 rounded"
                                  :class="b.slot_type === 'booking' ? 'bg-primary-100 text-primary-700' : 'bg-amber-100 text-amber-700'"
                                  x-text="b.slot_type === 'booking' ? 'Booking' : 'Pesanan'"></span>
                            <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase"
                                  :class="b.status === 'completed' ? 'bg-emerald-100 text-emerald-700' : (b.status === 'cancelled' || b.status === 'rejected' ? 'bg-rose-100 text-rose-700' : 'bg-sky-100 text-sky-700')"
                                  x-text="b.status_label || b.status"></span>
                            <span x-show="b.is_mine" class="text-[9px] font-black px-1.5 py-0.5 rounded bg-emerald-100 text-emerald-700">Saya</span>
                        </div>
                        <p class="text-gray-700 dark:text-gray-300 font-semibold mt-1 truncate" x-text="b.layanan || 'Layanan'"></p>
                        <p class="text-[11px] text-gray-400" x-text="b.submitted_at || '-'"></p>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <template x-if="b.show_url">
                            <a :href="b.show_url"
                               class="px-2.5 py-1.5 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 font-bold text-[10px]">
                                Buka
                            </a>
                        </template>
                        <template x-if="!b.show_url">
                            <span class="px-2.5 py-1.5 rounded-xl bg-gray-50 dark:bg-gray-900 text-gray-400 text-[10px] italic">Slot terisi</span>
                        </template>
                    </div>
                </div>
            </template>
        </div>

        <div class="pt-2 flex justify-end">
            <button type="button" @click="selectedDateDetail = null"
                    class="px-4 py-2 rounded-xl bg-gray-100 dark:bg-gray-800 text-xs font-bold text-gray-700 dark:text-gray-300">
                Tutup
            </button>
        </div>
    </div>
</div>
