{{-- ── Kolom Kanan: Panel Interaktif Detail Ketersediaan Slot (5 Cols Span) ── --}}
<div id="calendar-slot-panel" class="lg:col-span-5 flex flex-col h-full">

    {{-- Placeholder Saat Belum Ada Tanggal yang Dipilih --}}
    <div x-show="!modalOpen"
         class="h-full min-h-[220px] p-5 sm:p-6 rounded-2xl bg-slate-50/70 border border-dashed border-slate-200 flex flex-col items-center justify-center text-center transition-all">
        <div class="w-12 h-12 rounded-2xl bg-orange-50 border border-orange-200 flex items-center justify-center text-[#ff6b00] mb-3">
            <i class="ph-bold ph-calendar-check text-2xl"></i>
        </div>
        <h4 class="text-sm font-montserrat font-bold text-slate-900">Detail Ketersediaan Slot</h4>
        <p class="text-xs font-questrial text-slate-500 mt-1 max-w-xs leading-relaxed">
            Pilih salah satu tanggal pada kalender di sebelah kiri untuk melihat rincian slot yang telah terisi dan kuota harian.
        </p>
        <div class="mt-4 flex items-center gap-2 text-[10px] font-mono text-slate-500 bg-white px-3 py-1.5 rounded-lg border border-slate-200 shadow-sm">
            <i class="ph-bold ph-cursor-click text-[#FF6B00]"></i>
            <span>Klik kotak tanggal mana saja</span>
        </div>
    </div>

    {{-- Card Detail Saat Tanggal Dipilih --}}
    <div x-show="modalOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="relative bg-white border border-slate-200/90 rounded-2xl shadow-sm overflow-hidden flex flex-col h-full"
         style="display:none;">

        <div class="absolute -top-6 -right-6 w-24 h-24 bg-orange-500/5 rounded-full blur-[40px] pointer-events-none"></div>

        {{-- Panel Header --}}
        <div class="flex items-start justify-between px-5 pt-4 pb-3 border-b border-slate-100">
            <div>
                <p class="text-[9px] font-montserrat font-bold uppercase tracking-widest text-[#FF6B00] mb-0.5">Ketersediaan Slot</p>
                <h4 class="text-base font-audiowide font-bold text-slate-900 tracking-wide" x-text="modalTitle">—</h4>
            </div>
            <button @click="closeModal()"
                    title="Tutup detail tanggal"
                    class="mt-0.5 w-7 h-7 rounded-lg bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-500 hover:text-slate-900 transition-all shrink-0">
                <i class="ph-bold ph-x text-xs"></i>
            </button>
        </div>

        {{-- Quota Bar --}}
        <div class="px-5 py-3 bg-slate-50/70 border-b border-slate-100">
            <template x-if="modalLoading">
                <div class="h-5 bg-slate-200 rounded animate-pulse w-48"></div>
            </template>
            <template x-if="!modalLoading && modalData">
                <div class="flex items-center gap-4 flex-wrap">
                    {{-- Progress bar kuota --}}
                    <div class="flex-1 min-w-[140px]">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-[10px] font-montserrat font-bold uppercase tracking-wider text-slate-500">Slot Terpakai</span>
                            <span class="text-[10px] font-montserrat font-bold"
                                  :class="modalData.quota.is_full ? 'text-red-500' : (modalData.quota.total_used >= 3 ? 'text-[#FF6B00]' : 'text-emerald-600')"
                                  x-text="modalData.quota.total_used + ' / ' + modalData.quota.max + ' slot'"></span>
                        </div>
                        <div class="w-full h-2 bg-slate-200 rounded-full overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-500"
                                 :class="modalData.quota.is_full ? 'bg-red-500' : (modalData.quota.total_used >= 3 ? 'bg-[#FF6B00]' : 'bg-emerald-500')"
                                 :style="`width: ${Math.round((modalData.quota.total_used / modalData.quota.max) * 100)}%`"></div>
                        </div>
                    </div>
                    <div class="flex items-center gap-1.5 text-[10px] font-montserrat font-bold uppercase tracking-wider shrink-0">
                        <span class="w-1.5 h-1.5 rounded-full"
                              :class="modalData.quota.is_full ? 'bg-red-500' : (modalData.quota.available <= 2 ? 'bg-[#FF6B00]' : 'bg-emerald-500')"></span>
                        <span :class="modalData.quota.is_full ? 'text-red-500' : (modalData.quota.available <= 2 ? 'text-[#FF6B00]' : 'text-slate-700')"
                              x-text="modalData.quota.is_full ? 'PENUH' : (modalData.quota.available + ' Slot Tersedia')"></span>
                    </div>
                </div>
            </template>
        </div>

        {{-- Slot List --}}
        <div class="px-5 py-3.5 space-y-2 flex-1 max-h-60 overflow-y-auto [scrollbar-width:thin]">
            {{-- Loading skeleton --}}
            <template x-if="modalLoading">
                <template x-for="i in 2" :key="i">
                    <div class="h-12 rounded-xl bg-slate-100 animate-pulse"></div>
                </template>
            </template>

            {{-- Empty state --}}
            <template x-if="!modalLoading && modalData && modalData.slots.length === 0">
                <div class="text-center py-6">
                    <p class="text-xs font-montserrat font-bold text-emerald-600">Semua slot masih tersedia!</p>
                    <p class="text-[11px] font-questrial text-slate-500 mt-0.5">Belum ada booking pengerjaan di tanggal ini.</p>
                </div>
            </template>

            {{-- Slot items --}}
            <template x-if="!modalLoading && modalData">
                <template x-for="(slot, idx) in modalData.slots" :key="idx">
                    <component :is="slot.show_url ? 'a' : 'div'"
                               :href="slot.show_url || undefined"
                               class="flex items-center gap-2.5 rounded-xl px-3 py-2 border transition-all text-left"
                               :class="slot.is_mine
                                 ? 'bg-orange-50 border-orange-200 hover:bg-orange-100/60'
                                 : 'bg-slate-50/80 border-slate-200/80'">

                        {{-- Nomor slot --}}
                        <div class="w-6 h-6 rounded-md flex items-center justify-center shrink-0 text-[10px] font-montserrat font-bold"
                             :class="slot.is_mine ? 'bg-[#FF6B00] text-white' : 'bg-slate-200 text-slate-700'"
                             x-text="idx + 1"></div>

                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-montserrat font-bold text-slate-900 truncate flex items-center gap-1.5">
                                <span x-text="slot.layanan"></span>
                                <template x-if="slot.is_mine">
                                    <span class="text-[9px] font-mono font-bold text-[#FF6B00]">(Milik Saya)</span>
                                </template>
                            </p>
                            <p class="text-[9px] font-questrial text-slate-500 mt-0.5 flex items-center gap-2">
                                <span class="inline-flex items-center gap-1">
                                    <i class="ph-bold ph-clock text-[9px]"></i>
                                    <span x-text="'Dikirim ' + slot.submitted_at"></span>
                                </span>
                                <span class="text-slate-300">&bull;</span>
                                <span x-text="slot.slot_type === 'booking' ? 'Booking' : 'Pesanan'"></span>
                            </p>
                        </div>

                        {{-- Status clean text --}}
                        <span class="inline-flex items-center gap-1.5 text-[9px] font-montserrat font-bold uppercase whitespace-nowrap text-[#FF6B00]">
                            <span class="w-1.5 h-1.5 rounded-full shrink-0 bg-[#FF6B00]"></span>
                            <span x-text="slot.status_label"></span>
                        </span>
                    </component>
                </template>
            </template>
        </div>

        {{-- Actions --}}
        <div class="px-5 pb-4 pt-2.5 flex items-center gap-2.5 border-t border-slate-100">
            <template x-if="modalData && modalIsPast">
                <div class="flex-1 text-center py-2.5 min-h-[44px] bg-slate-100 border border-slate-200 text-slate-500 font-montserrat font-bold text-[11px] uppercase tracking-wider rounded-xl flex items-center justify-center gap-1.5">
                    <i class="ph-bold ph-clock-counter-clockwise"></i> Tanggal Sudah Berlalu
                </div>
            </template>
            <template x-if="modalData && !modalIsPast && !modalData.quota.is_full">
                <a :href="'{{ route('booking.create') }}?date=' + modalDate"
                   class="flex-1 text-center py-2.5 min-h-[44px] bg-[#FF6B00] hover:bg-[#ea580c] text-white font-montserrat font-bold text-[11px] uppercase tracking-wider rounded-xl hover:opacity-95 transition-all active:scale-95 shadow-md shadow-orange-500/20 flex items-center justify-center gap-1.5">
                    <i class="ph-bold ph-plus-circle text-sm"></i> Booking Tanggal Ini
                </a>
            </template>
            <template x-if="modalData && !modalIsPast && modalData.quota.is_full">
                <div class="flex-1 text-center py-2.5 min-h-[44px] bg-red-50 border border-red-200 text-red-600 font-montserrat font-bold text-[11px] uppercase tracking-wider rounded-xl flex items-center justify-center">
                    Slot Penuh — Pilih Tanggal Lain
                </div>
            </template>
            <button @click="closeModal()"
                    class="px-4 py-2.5 min-h-[44px] bg-slate-100 hover:bg-slate-200 border border-slate-200 text-slate-600 hover:text-slate-900 font-montserrat font-bold text-[11px] uppercase tracking-wider rounded-xl transition-all">
                Tutup
            </button>
        </div>
    </div>
</div>
