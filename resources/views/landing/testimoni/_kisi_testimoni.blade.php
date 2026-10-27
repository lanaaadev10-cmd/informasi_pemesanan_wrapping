{{-- ============================================
    BAGIAN: Grid Kartu Ulasan Testimoni Pelanggan
    Deskripsi: Daftar ulasan terverifikasi lengkap dengan bintang, respon workshop & media
============================================ --}}
@if($ratings->isEmpty())
    <div class="text-center py-16 border border-dashed border-slate-200 rounded-3xl bg-slate-50" data-aos="fade-up">
        <p class="text-slate-500 text-xs sm:text-sm font-normal">Belum ada ulasan yang sesuai dengan filter ini.</p>
    </div>
@else
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5" data-aos="fade-up">
        @foreach($ratings as $rating)
            <div class="bg-white border border-slate-200/90 rounded-2xl p-6 flex flex-col justify-between shadow-sm hover:border-slate-300 transition-all">
                <div>
                    {{-- User Header & Service Tag --}}
                    <div class="flex items-center justify-between gap-2 mb-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center font-bold text-slate-700 text-xs">
                                {{ strtoupper(substr($rating->user?->name ?? 'P', 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-bold text-slate-900 text-xs sm:text-sm leading-tight">{{ $rating->user?->name ?? 'Pelanggan' }}</p>
                                <span class="text-[10px] text-slate-400 font-medium">{{ $rating->created_at?->translatedFormat('d M Y') }}</span>
                            </div>
                        </div>
                        <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full bg-orange-50 text-[#ea580c] border border-orange-100 max-w-[120px] truncate">
                            {{ $rating->layanan?->nama_layanan }}
                        </span>
                    </div>

                    {{-- Star Rating --}}
                    <div class="text-amber-400 text-xs flex items-center gap-0.5 mb-3">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="ph-fill ph-star {{ $i <= $rating->rating ? 'text-amber-400' : 'text-slate-200' }}"></i>
                        @endfor
                    </div>

                    {{-- Review Text --}}
                    @if($rating->ulasan)
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal">
                            "{{ $rating->ulasan }}"
                        </p>
                    @else
                        <p class="text-xs text-slate-400 italic">Tanpa ulasan tertulis.</p>
                    @endif

                    {{-- Admin Reply --}}
                    @if($rating->balasan_admin)
                        <div class="mt-4 p-3.5 rounded-xl bg-orange-50/60 border border-orange-200/70 space-y-1">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-[#ea580c]">Balasan Workshop</p>
                            <p class="text-xs text-slate-700 leading-relaxed">"{{ $rating->balasan_admin }}"</p>
                            @if($rating->dibalas_at)
                                <p class="text-[10px] text-slate-400">{{ $rating->dibalas_at->translatedFormat('d M Y') }}</p>
                            @endif
                        </div>
                    @endif

                    {{-- Attached Photos --}}
                    @if($rating->medias->isNotEmpty())
                        <div class="flex gap-2 mt-4 overflow-x-auto pb-1">
                            @foreach($rating->medias as $media)
                                <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($media->path) }}" target="_blank" class="block w-16 h-16 rounded-xl overflow-hidden border border-slate-200 shrink-0 hover:opacity-85 transition-opacity">
                                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($media->path) }}" class="w-full h-full object-cover" alt="Foto ulasan">
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Order Footer --}}
                @if($rating->pesanan)
                    <div class="pt-3 mt-4 border-t border-slate-100 text-[11px] text-slate-400 flex items-center justify-between">
                        <span>No. Pesanan:</span>
                        <span class="font-bold text-slate-700 font-mono">{{ $rating->pesanan->kode_pesanan }}</span>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
@endif
