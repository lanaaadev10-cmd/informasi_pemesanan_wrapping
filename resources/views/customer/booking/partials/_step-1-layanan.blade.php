{{-- ──────────────────────────────────────────
     LANGKAH 1: PILIH LAYANAN (DATA DARI KATALOG)
────────────────────────────────────────── --}}
<section x-show="currentStep === 1" x-transition.opacity
         class="bg-[#141416]/95 border border-white/10 rounded-3xl p-5 sm:p-7 shadow-2xl space-y-6">

    {{-- Step Header & Filter Tabs Sesuai Katalog --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-white/10 pb-5">
        <h2 class="text-base sm:text-lg font-audiowide font-bold text-white tracking-wide">
            Langkah 1: Pilih Layanan
        </h2>

        {{-- Filter Tabs (Semua, Wrapping, Kaca Film, Audio) --}}
        <div class="flex items-center bg-[#0d0d0f] border border-white/10 p-1 rounded-2xl w-fit">
            <template x-for="cat in categories" :key="cat">
                <button type="button"
                        @click="selectedCategory = cat"
                        :class="selectedCategory === cat ? 'bg-[#2a2a30] text-white shadow-sm font-montserrat font-bold' : 'text-gray-400 hover:text-white font-montserrat font-medium'"
                        class="px-3 sm:px-4 py-1.5 rounded-xl text-xs transition-all"
                        x-text="cat">
                </button>
            </template>
        </div>
    </div>

    {{-- Service Cards Grid (Thumbnail & Data Persis Seperti Katalog) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
        <template x-for="item in filteredLayanans" :key="item.id">
            <div @click="selectLayanan(item.id)"
                 class="rounded-2xl border transition-all duration-300 flex flex-col justify-between cursor-pointer group relative overflow-hidden shadow-xl"
                 :class="String(layananId) === String(item.id)
                     ? 'border-[#ff6b00] bg-[#1e1713] ring-2 ring-[#ff6b00]/50 shadow-[0_0_24px_rgba(255,107,0,0.25)] scale-[1.01]'
                     : 'border-white/10 bg-[#16161a] hover:border-white/25 hover:bg-[#1a1a20]'">

                <div>
                    {{-- Thumbnail Foto Persis Seperti Katalog --}}
                    <div class="relative h-44 sm:h-48 bg-gradient-to-br from-[#ff6b00]/20 to-transparent overflow-hidden">
                        <img :src="item.foto"
                             :alt="item.name"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/20 to-transparent"></div>

                        {{-- Badge Tipe Paket (Persis Katalog) --}}
                        <div class="absolute top-3 left-3 bg-[#ff6b00] px-2.5 py-0.5 sm:px-3 sm:py-1 rounded-full shadow-md z-10">
                            <p class="text-[9px] font-montserrat font-bold text-white uppercase tracking-wider" x-text="item.tipe_paket"></p>
                        </div>

                        {{-- Checkmark Selection Indicator --}}
                        <div class="absolute top-3 right-3 z-10">
                            <div x-show="String(layananId) === String(item.id)"
                                 class="w-6 h-6 rounded-full bg-[#ff6b00] text-black flex items-center justify-center text-xs font-black shadow-lg">
                                <i class="ph-bold ph-check text-sm"></i>
                            </div>
                            <div x-show="String(layananId) !== String(item.id)"
                                 class="w-6 h-6 rounded-full border border-white/40 bg-black/50 backdrop-blur-sm">
                            </div>
                        </div>

                        {{-- Rating Bintang di Bagian Bawah Thumbnail --}}
                        <div class="absolute bottom-2.5 left-3 flex items-center gap-1.5 z-10" x-show="item.rating_avg">
                            <span class="text-[#ff6b00] font-montserrat font-bold text-xs flex items-center gap-1 bg-black/70 backdrop-blur-md px-2 py-0.5 rounded-lg border border-white/10">
                                <i class="ph-fill ph-star text-[#ff6b00] text-xs"></i>
                                <span x-text="item.rating_avg"></span>
                            </span>
                            <span class="text-[10px] font-questrial text-gray-400 bg-black/60 backdrop-blur-md px-1.5 py-0.5 rounded-md" x-text="'(' + item.rating_count + ' ulasan)'"></span>
                        </div>
                    </div>

                    {{-- Konten Data Katalog --}}
                    <div class="p-4 sm:p-5 flex flex-col justify-between flex-1 space-y-3">
                        <div>
                            {{-- Nama Layanan --}}
                            <h3 class="font-audiowide font-bold text-white text-base leading-snug group-hover:text-[#ff6b00] transition-colors"
                                x-text="item.name"></h3>

                            {{-- Deskripsi Asli dari Katalog --}}
                            <p class="text-xs font-questrial text-gray-400 line-clamp-2 mt-1.5 leading-relaxed"
                               x-text="item.deskripsi"></p>

                            {{-- Daftar Fitur Unggulan Asli dari Katalog --}}
                            <template x-if="item.fitur && item.fitur.length">
                                <div class="space-y-1.5 pt-3 mt-3 border-t border-white/5 font-questrial">
                                    <template x-for="(fiturText, idx) in item.fitur" :key="idx">
                                        <div class="flex items-center gap-2 text-xs text-gray-300">
                                            <i class="ph-bold ph-check-circle text-[#ff6b00] text-xs shrink-0"></i>
                                            <span class="truncate" x-text="fiturText"></span>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- Baris Tarif & Estimasi Waktu --}}
                <div class="p-4 sm:p-5 pt-0">
                    <div class="pt-3 border-t border-white/10 flex items-center justify-between text-xs">
                        <div>
                            <span class="text-[9px] font-montserrat font-bold uppercase tracking-wider text-gray-500 block">Tarif Mulai</span>
                            <span class="font-audiowide font-bold text-[#ff6b00] text-base"
                                  x-text="formatRupiah(item.price)"></span>
                        </div>
                        <div class="text-right">
                            <span class="text-[9px] font-montserrat font-bold uppercase tracking-wider text-gray-500 block">Estimasi</span>
                            <span class="text-gray-300 font-questrial flex items-center justify-end gap-1 text-xs">
                                <i class="ph-bold ph-clock text-[#ff6b00]"></i>
                                <span x-text="item.estimasi"></span>
                            </span>
                        </div>
                    </div>
                </div>

            </div>
        </template>
    </div>

    @error('layanan_id')
        <p class="text-red-400 text-xs font-semibold">{{ $message }}</p>
    @enderror
</section>
