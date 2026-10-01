{{-- Horizontal Date Strip Picker (Mobile-First Slot Booking UX) --}}
@php
    use Carbon\Carbon;
    $today = Carbon::today();
    $days = [];
    for ($i = 0; $i < 14; $i++) {
        $d = $today->copy()->addDays($i);
        $days[] = [
            'date_str' => $d->toDateString(),
            'day_num'  => $d->format('d'),
            'day_name' => $d->translatedFormat('D'),
            'month'    => $d->translatedFormat('M'),
            'is_today' => ($i === 0),
        ];
    }
@endphp

<div class="space-y-3" x-data="dateStripWidget()" x-init="init()">
    <div class="flex items-center justify-between px-1">
        <span class="text-[10px] font-montserrat font-black uppercase tracking-widest text-[#8A8D93]">
            Ketersediaan Slot 14 Hari
        </span>
        <a href="{{ route('booking.index') }}" class="text-[10px] font-montserrat font-bold text-[#FF6B00] hover:underline">
            Lihat Kalender &rarr;
        </a>
    </div>

    {{-- Horizontal Scrollable Date Strip --}}
    <div class="flex gap-2.5 overflow-x-auto no-scrollbar py-1 -mx-4 px-4 sm:mx-0 sm:px-0 scroll-smooth">
        @foreach($days as $item)
            @php
                $bookingUrl = route('booking.create', ['date' => $item['date_str']]);
            @endphp
            <a href="{{ $bookingUrl }}"
               id="strip-day-{{ $item['date_str'] }}"
               class="date-strip-card flex flex-col items-center justify-between p-3 rounded-2xl bg-[#0E0E10] border border-white/10 hover:border-[#FF6B00] active:scale-95 transition-all text-center min-w-[76px] sm:min-w-[84px] shrink-0 min-h-[96px] shadow-lg group relative overflow-hidden"
               :class="{
                   'border-[#FF6B00] bg-[#FF6B00]/10': isSelected('{{ $item['date_str'] }}'),
                   'opacity-60 pointer-events-none': isFull('{{ $item['date_str'] }}')
               }">

                {{-- Today Tag --}}
                @if($item['is_today'])
                    <span class="absolute top-1.5 left-1/2 -translate-x-1/2 text-[8px] font-montserrat font-black uppercase text-[#FF6B00] tracking-wider">
                        Hari Ini
                    </span>
                @endif

                <div class="space-y-0.5 {{ $item['is_today'] ? 'pt-2.5' : 'pt-1' }}">
                    <span class="text-[10px] font-montserrat font-bold uppercase tracking-wider text-[#8A8D93] group-hover:text-white transition-colors block">
                        {{ $item['day_name'] }}
                    </span>
                    <span class="text-xl sm:text-2xl font-audiowide font-bold text-white group-hover:text-[#FF6B00] transition-colors block">
                        {{ $item['day_num'] }}
                    </span>
                    <span class="text-[9px] font-questrial text-gray-500 block">
                        {{ $item['month'] }}
                    </span>
                </div>

                {{-- Slot Availability Text Indicator --}}
                <div class="pt-2 border-t border-white/5 w-full">
                    <span class="text-[10px] font-montserrat font-bold uppercase tracking-wider text-[#FF6B00]"
                          x-text="getSlotLabel('{{ $item['date_str'] }}')">
                        5 Slot
                    </span>
                </div>
            </a>
        @endforeach
    </div>
</div>

<script>
function dateStripWidget() {
    return {
        quota: {},
        selectedDate: '{{ $today->toDateString() }}',

        async init() {
            try {
                const now = new Date();
                const res = await fetch(`/api/booking/quota-month/${now.getFullYear()}/${now.getMonth() + 1}`);
                const data = await res.json();
                this.quota = data.quota || {};
            } catch (e) {
                console.error('Gagal fetch slot:', e);
            }
        },

        isFull(dateStr) {
            return this.quota[dateStr] ? this.quota[dateStr].is_full : false;
        },

        isSelected(dateStr) {
            return this.selectedDate === dateStr;
        },

        getSlotLabel(dateStr) {
            if (!this.quota[dateStr]) return 'Tersedia';
            const q = this.quota[dateStr];
            if (q.is_full) return 'FULL';
            return `${q.available} Slot`;
        }
    };
}
</script>
