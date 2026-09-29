<x-filament-panels::page>
    <div class="calendar-page-wrapper" x-data="adminCalendarApp()" x-init="init()">

        {{-- Scoped Styles for Guaranteed Layout & Sizing extracted to partial (<300 lines rule) --}}
        @include('filament.pages.partials._kalender-booking-styles')

        {{-- ── STATS OVERVIEW CARDS ── --}}
        <div class="cal-stats-grid">
            {{-- Kuota Hari Ini --}}
            <div class="cal-stat-card">
                <div>
                    <span style="font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em;">Kuota Hari Ini</span>
                    <p style="font-size: 1.5rem; font-weight: 800; color: #ea580c; margin-top: 0.25rem;">
                        {{ $todayQuota['available'] }} <span style="font-size: 0.875rem; font-weight: 400; color: #6b7280;">/ 5 Slot Tersedia</span>
                    </p>
                </div>
                <div class="cal-icon-box" style="background-color: #fff7ed; color: #ea580c;">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>

            {{-- Booking Aktif Hari Ini --}}
            <div class="cal-stat-card">
                <div>
                    <span style="font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em;">Booking Aktif Hari Ini</span>
                    <p style="font-size: 1.5rem; font-weight: 800; color: #111827; margin-top: 0.25rem;" class="dark:text-white">
                        {{ $todayQuota['booked_count'] }} <span style="font-size: 0.875rem; font-weight: 400; color: #6b7280;">Kendaraan</span>
                    </p>
                </div>
                <div class="cal-icon-box" style="background-color: #f0fdf4; color: #16a34a;">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>

            {{-- Status Kuota Hari Ini --}}
            <div class="cal-stat-card">
                <div>
                    <span style="font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em;">Status Kuota Hari Ini</span>
                    <p style="font-size: 1.125rem; font-weight: 800; margin-top: 0.25rem;" 
                       style="color: {{ $todayQuota['is_full'] ? '#dc2626' : ($todayQuota['available'] <= 1 ? '#d97706' : '#16a34a') }};">
                        {{ $todayQuota['is_full'] ? 'PENUH (5/5 Slot)' : ($todayQuota['available'] <= 1 ? 'HAMPIR PENUH' : 'TERSEDIA') }}
                    </p>
                </div>
                <div class="cal-icon-box" style="background-color: #f3f4f6; color: #4b5563;">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
        </div>

        {{-- ── KALENDER UTAMA ADMIN ── --}}
        <div class="cal-main-box">

            {{-- Top Controls --}}
            <div class="cal-controls-row">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <button type="button" @click="prevMonth()" class="cal-btn">
                        &larr; Prev
                    </button>
                    <h2 style="font-size: 1.125rem; font-weight: 800; text-transform: capitalize; margin: 0 0.5rem;" class="text-gray-900 dark:text-white" x-text="monthLabel"></h2>
                    <button type="button" @click="nextMonth()" class="cal-btn">
                        Next &rarr;
                    </button>
                    <button type="button" @click="todayMonth()" class="cal-btn cal-btn-primary">
                        Hari Ini
                    </button>
                </div>

                {{-- Mode View (Bulanan / Mingguan) --}}
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <div style="display: flex; align-items: center; background-color: #f3f4f6; padding: 0.25rem; border-radius: 0.75rem;" class="dark:bg-gray-800">
                        <button type="button" @click="viewMode = 'month'"
                                :style="viewMode === 'month' ? 'background: #ffffff; color: #ea580c; font-weight: 800; box-shadow: 0 1px 2px rgba(0,0,0,0.05);' : 'background: transparent; color: #6b7280; font-weight: 600;'"
                                class="cal-btn" style="border: none; padding: 0.35rem 0.75rem;">
                            Bulanan
                        </button>
                        <button type="button" @click="viewMode = 'week'; renderWeek()"
                                :style="viewMode === 'week' ? 'background: #ffffff; color: #ea580c; font-weight: 800; box-shadow: 0 1px 2px rgba(0,0,0,0.05);' : 'background: transparent; color: #6b7280; font-weight: 600;'"
                                class="cal-btn" style="border: none; padding: 0.35rem 0.75rem;">
                            Mingguan
                        </button>
                    </div>
                </div>
            </div>

            {{-- Legenda Kuota --}}
            <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 1rem; font-size: 0.75rem; color: #4b5563;">
                <span style="display: flex; align-items: center; gap: 0.35rem;"><span style="display: inline-block; width: 10px; height: 10px; border-radius: 50%; background-color: #10b981;"></span> Kuota Tersedia (&ge;2)</span>
                <span style="display: flex; align-items: center; gap: 0.35rem;"><span style="display: inline-block; width: 10px; height: 10px; border-radius: 50%; background-color: #f59e0b;"></span> Sisa 1 Slot (4/5)</span>
                <span style="display: flex; align-items: center; gap: 0.35rem;"><span style="display: inline-block; width: 10px; height: 10px; border-radius: 50%; background-color: #f43f5e;"></span> Penuh (5/5)</span>
                <span style="display: flex; align-items: center; gap: 0.35rem;"><span style="display: inline-block; width: 10px; height: 10px; border-radius: 50%; background-color: #9ca3af;"></span> Libur / Diblokir</span>
            </div>

            {{-- ── TAMPILAN BULANAN ── --}}
            <div x-show="viewMode === 'month'" style="display: flex; flex-direction: column; gap: 0.5rem;">
                <div class="cal-grid-7">
                    <template x-for="d in ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu']" :key="d">
                        <div style="text-align: center; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; color: #9ca3af; padding: 0.25rem 0;" x-text="d"></div>
                    </template>
                </div>

                <div class="cal-grid-7">
                    <template x-for="cell in cells" :key="cell.key">
                        <template x-if="cell.empty">
                            <div class="cal-day-cell-empty"></div>
                        </template>
                        <template x-if="!cell.empty">
                            <div @click="openDayDetail(cell.date)"
                                 class="cal-day-cell"
                                 :class="cell.isBlocked ? 'cal-cell-blocked' : (cell.used >= 5 ? 'cal-cell-full' : (cell.used === 4 ? 'cal-cell-warning' : 'cal-cell-available'))">
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <span style="font-weight: 800; font-size: 0.875rem;" x-text="cell.day"></span>
                                    <span x-show="cell.isToday" style="font-size: 0.55rem; font-weight: 800; padding: 2px 4px; border-radius: 4px; background-color: #ea580c; color: #ffffff;">HARI INI</span>
                                </div>
                                <div>
                                    <template x-if="cell.isBlocked">
                                        <span style="font-size: 0.65rem; font-weight: 700; display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" x-text="cell.blockedReason || 'Tutup'"></span>
                                    </template>
                                    <template x-if="!cell.isBlocked">
                                        <div>
                                            <span style="font-size: 0.65rem; font-weight: 800; display: block;"
                                                  x-text="cell.used + '/5 Booking'">
                                            </span>
                                            <span style="font-size: 0.6rem; opacity: 0.75; display: block;" x-text="cell.available + ' slot sisa'"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </template>
                </div>
            </div>

            {{-- ── TAMPILAN MINGGUAN ── --}}
            <div x-show="viewMode === 'week'" style="display: flex; flex-direction: column; gap: 1rem;">
                <div class="cal-grid-7">
                    <template x-for="w in weekCells" :key="w.date">
                        <div @click="openDayDetail(w.date)"
                             class="cal-day-cell" style="min-height: 120px; padding: 0.75rem;"
                             :class="w.isBlocked ? 'cal-cell-blocked' : (w.used >= 5 ? 'cal-cell-full' : (w.used === 4 ? 'cal-cell-warning' : 'cal-cell-available'))">
                            <div>
                                <span style="font-size: 0.65rem; font-weight: 800; text-transform: uppercase; opacity: 0.7;" x-text="w.dayName"></span>
                                <p style="font-size: 1.25rem; font-weight: 800; margin: 0.25rem 0;" x-text="w.dayNum"></p>
                                <p style="font-size: 0.75rem; opacity: 0.7;" x-text="w.monthShort"></p>
                            </div>
                            <div style="border-top: 1px solid rgba(0,0,0,0.06); padding-top: 0.5rem; margin-top: 0.5rem;">
                                <span style="font-size: 0.75rem; font-weight: 800; display: block;"
                                      x-text="w.isBlocked ? 'TUTUP / LIBUR' : w.used + '/5 Terisi'">
                                </span>
                                <span style="font-size: 0.65rem; opacity: 0.75; display: block;" x-text="w.isBlocked ? (w.blockedReason || 'Libur') : w.available + ' slot sisa'"></span>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

        </div>

        {{-- ── MODAL DETAIL BOOKING PER HARI ── --}}
        @include('filament.pages.partials._modal-detail-booking')

    </div>

    {{-- Admin Calendar Alpine Component Script --}}
    @include('filament.pages.partials._admin-calendar-script')

</x-filament-panels::page>
