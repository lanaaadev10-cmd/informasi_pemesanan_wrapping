{{-- ============================================
    KARTU KHUSUS: JASA PASANG & CUSTOM QUOTATION
============================================ --}}

{{-- Kartu Khusus Jasa Pasang (Deklarasi Material & Status) --}}
@if($booking->material_brand || $booking->material_status)
    <div class="bg-emerald-50/70 border border-emerald-200 rounded-3xl p-6 shadow-sm space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-emerald-200 pb-3">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-600 border border-emerald-200 flex items-center justify-center shrink-0">
                    <i class="ph-bold ph-scissors text-base"></i>
                </div>
                <div>
                    <h3 class="text-xs font-montserrat font-bold text-emerald-800 uppercase tracking-wider">
                        Deklarasi Material Vinyl (Jasa Pasang)
                    </h3>
                    <p class="text-[11px] font-questrial text-slate-600">Material dibawa sendiri oleh pelanggan</p>
                </div>
            </div>
            <div>
                @php
                    $mStatus = $booking->material_status ?? 'pending';
                    $mLabel = [
                        'approved' => 'Bahan Disetujui Teknisi',
                        'rejected' => 'Bahan Ditolak / Tidak Layak',
                        'pending'  => 'Menunggu Verifikasi Fisik',
                    ][$mStatus] ?? ucfirst($mStatus);
                    $mTextClr = match($mStatus) {
                        'approved' => 'text-emerald-600',
                        'rejected' => 'text-red-500',
                        default    => 'text-[#FF6B00]',
                    };
                    $mDotClr = match($mStatus) {
                        'approved' => 'bg-emerald-500',
                        'rejected' => 'bg-red-500',
                        default    => 'bg-[#FF6B00]',
                    };
                @endphp
                <span class="inline-flex items-center gap-1.5 text-[11px] font-montserrat font-bold uppercase tracking-wider {{ $mTextClr }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $mDotClr }} shrink-0"></span>
                    <span>{{ $mLabel }}</span>
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
            <div>
                <span class="text-slate-500 block mb-0.5">Merk / Brand Material:</span>
                <strong class="text-slate-900">{{ $booking->material_brand ?: '-' }}</strong>
            </div>
            <div>
                <span class="text-slate-500 block mb-0.5">Dimensi / Estimasi Panjang:</span>
                <strong class="text-slate-900">{{ $booking->material_size ?: '-' }}</strong>
            </div>
            <div>
                <span class="text-slate-500 block mb-0.5">Kondisi Bahan:</span>
                <strong class="text-slate-900">{{ ucwords(str_replace('_', ' ', $booking->material_condition ?: '-')) }}</strong>
            </div>
        </div>

        @if($booking->material_photo)
            <div class="pt-2 border-t border-emerald-200 flex items-center justify-between text-xs">
                <span class="text-slate-500">Lampiran Foto Bahan:</span>
                <a href="{{ Storage::url($booking->material_photo) }}" target="_blank"
                   class="text-emerald-700 hover:text-emerald-800 font-bold underline flex items-center gap-1">
                    <i class="ph-bold ph-image"></i>
                    <span>Lihat Foto Bahan</span>
                </a>
            </div>
        @endif
    </div>
@endif

{{-- Kartu Khusus Custom Quotation --}}
@if($booking->custom_request_notes || $booking->quotation_status)
    <div class="bg-amber-50/70 border border-amber-200 rounded-3xl p-6 shadow-sm space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-amber-200 pb-3">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-600 border border-amber-200 flex items-center justify-center shrink-0">
                    <i class="ph-bold ph-sketch-logo text-base"></i>
                </div>
                <div>
                    <h3 class="text-xs font-montserrat font-bold text-amber-800 uppercase tracking-wider">
                        Penawaran Proyek Custom (Quotation)
                    </h3>
                    <p class="text-[11px] font-questrial text-slate-600">Estimasi teknis oleh perancang workshop</p>
                </div>
            </div>
            <div>
                @php
                    $qStatus = $booking->quotation_status ?? 'pending';
                    $qLabel = [
                        'sent'     => 'Penawaran Diterbitkan',
                        'accepted' => 'Penawaran Disetujui',
                        'rejected' => 'Penawaran Ditolak',
                        'pending'  => 'Menunggu Estimasi Admin',
                    ][$qStatus] ?? ucfirst($qStatus);
                    $qTextClr = match($qStatus) {
                        'accepted', 'sent' => 'text-emerald-600',
                        'rejected'         => 'text-red-500',
                        default            => 'text-[#FF6B00]',
                    };
                    $qDotClr = match($qStatus) {
                        'accepted', 'sent' => 'bg-emerald-500',
                        'rejected'         => 'bg-red-500',
                        default            => 'bg-[#FF6B00]',
                    };
                @endphp
                <span class="inline-flex items-center gap-1.5 text-[11px] font-montserrat font-bold uppercase tracking-wider {{ $qTextClr }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $qDotClr }} shrink-0"></span>
                    <span>{{ $qLabel }}</span>
                </span>
            </div>
        </div>

        @if($booking->custom_request_notes)
            <div class="text-xs">
                <span class="text-slate-500 block mb-1">Catatan Spesifikasi Kustom:</span>
                <p class="text-slate-800 bg-white p-3 rounded-xl border border-amber-200">{{ $booking->custom_request_notes }}</p>
            </div>
        @endif

        @if($booking->quotation_amount)
            <div class="p-3.5 rounded-xl bg-white border border-amber-200 flex items-center justify-between text-xs">
                <span class="text-amber-800 font-bold">Nominal Penawaran Resmi (Quotation):</span>
                <span class="font-audiowide font-bold text-amber-600 text-sm">Rp {{ number_format((float) $booking->quotation_amount, 0, ',', '.') }}</span>
            </div>
        @endif

        @if($booking->quotation_notes)
            <div class="text-xs">
                <span class="text-slate-500 block mb-0.5">Catatan Penawaran Admin:</span>
                <p class="text-slate-700 italic">{{ $booking->quotation_notes }}</p>
            </div>
        @endif

        @if($booking->custom_reference_photo)
            <div class="pt-2 border-t border-amber-200 flex items-center justify-between text-xs">
                <span class="text-slate-500">Lampiran Foto Referensi Desain:</span>
                <a href="{{ Storage::url($booking->custom_reference_photo) }}" target="_blank"
                   class="text-amber-700 hover:text-amber-800 font-bold underline flex items-center gap-1">
                    <i class="ph-bold ph-image"></i>
                    <span>Lihat Foto Referensi</span>
                </a>
            </div>
        @endif

        @php
            $rawWa = !empty($profil->nomor_telepon ?? null) ? preg_replace('/[^0-9]/', '', $profil->nomor_telepon) : '6283117163605';
            $workshopWa = $rawWa ?: '6283117163605';
        @endphp

        @if($qStatus === 'pending')
            <div class="pt-3 border-t border-amber-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <p class="text-[11px] font-questrial text-amber-800">
                    Ingin diskusi desain langsung atau nego penawaran harga via WhatsApp?
                </p>
                <a href="https://api.whatsapp.com/send?phone={{ $workshopWa }}&text={{ urlencode('Halo Tim Dantie Stiker, saya ingin konsultasi & sepakati penawaran harga custom untuk booking saya: ' . $booking->booking_code . ' (' . ($booking->service_name_snapshot ?? ($booking->layanan?->nama_layanan ?? 'Custom')) . ')') }}"
                   target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-montserrat font-bold text-xs shadow-md transition-all shrink-0">
                    <i class="ph-fill ph-whatsapp-logo text-base"></i>
                    <span>Chat WhatsApp untuk Deal Harga</span>
                </a>
            </div>
        @elseif($qStatus === 'sent')
            <div class="pt-3 border-t border-amber-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <p class="text-[11px] font-questrial text-emerald-800">
                    Penawaran resmi telah terbit. Silakan lakukan pembayaran DP untuk mengunci slot kalender pengerjaan.
                </p>
                <a href="https://api.whatsapp.com/send?phone={{ $workshopWa }}&text={{ urlencode('Halo Tim Dantie Stiker, saya setuju dengan penawaran custom booking ' . $booking->booking_code . ' sebesar Rp ' . number_format((float) $booking->quotation_amount, 0, ',', '.') . '. Saya ingin konfirmasi pembayaran.') }}"
                   target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-montserrat font-bold text-xs shadow-md transition-all shrink-0">
                    <i class="ph-fill ph-whatsapp-logo text-base"></i>
                    <span>Konfirmasi Deal via WA</span>
                </a>
            </div>
        @endif
    </div>
@endif
