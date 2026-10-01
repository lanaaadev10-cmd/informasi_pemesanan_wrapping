/**
 * Booking Wizard Alpine.js Component
 * Handles Step-by-Step Car Wrapping Service Booking Flow
 */
function bookingWizardApp(config) {
    const MONTHS = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    const MONTHS_SHORT = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
    const DAYS = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    const MAX_SLOT = 5;

    return {
        // Data Layanan dari Katalog
        layanans: config.layanans || [],
        categories: config.categories || ['Semua', 'Wrapping', 'Kaca Film', 'Audio'],
        selectedCategory: 'Semua',

        // Wizard Steps: 1 = Layanan, 2 = Tanggal, 3 = Data Diri, 4 = Pembayaran
        currentStep: 1,

        // Pilihan Form
        layananId: config.initialLayananId || (config.layanans[0]?.id ?? null),
        bookingDate: config.initialDate || new Date().toISOString().slice(0, 10),
        bookingTime: config.initialTime || '08:30',
        hasChosenDate: Boolean(config.initialDate),
        paymentType: config.initialPaymentType || 'dp',

        // Data Pelanggan
        customerName: config.defaultName || '',
        customerPhone: config.defaultPhone || '',
        customerEmail: config.defaultEmail || '',
        vehicleName: '',
        vehicleColor: '',
        vehicleLicense: '',
        notes: '',
        proofFile: null,
        proofFileName: '',
        rekeningCopied: false,
        isSubmitting: false,

        // Quick Date Strip (14 Hari ke Depan - Rekomendasi 2)
        stripDays: config.stripDays || [],
        showFullCalendar: Boolean(config.initialDate && (new Date(config.initialDate) - new Date() > 14 * 86400000)),

        // Kalender
        viewMode: 'month',
        calYear: new Date().getFullYear(),
        calMonth: new Date().getMonth(),
        calCells: [],
        calQuota: {},
        weekOffset: 0,
        weekDays: [],

        // Bank
        banks: [
            {
                id: 'Transfer BCA',
                code: 'BCA',
                name: 'Bank Central Asia (BCA)',
                shortName: 'Bank BCA',
                number: '8720998811',
                formatted: '8720-9988-11',
                holder: 'Dantie Stiker',
                badgeBg: 'bg-blue-500/20 text-blue-400 border-blue-500/30'
            },
            {
                id: 'Transfer BRI',
                code: 'BRI',
                name: 'Bank Rakyat Indonesia (BRI)',
                shortName: 'Bank BRI',
                number: '012301001234530',
                formatted: '0123-01-001234-53-0',
                holder: 'Dantie Stiker',
                badgeBg: 'bg-sky-500/20 text-sky-400 border-sky-500/30'
            },
            {
                id: 'Transfer BSI',
                code: 'BSI',
                name: 'Bank Syariah Indonesia (BSI)',
                shortName: 'Bank BSI',
                number: '7123456789',
                formatted: '7123-4567-89',
                holder: 'Dantie Stiker',
                badgeBg: 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30'
            }
        ],
        selectedBankId: config.initialPaymentMethod || 'Transfer BCA',

        get currentBank() {
            return this.banks.find(b => b.id === this.selectedBankId) || this.banks[0];
        },

        get selectedLayanan() {
            return this.layanans.find(l => String(l.id) === String(this.layananId)) || null;
        },

        get filteredLayanans() {
            if (this.selectedCategory === 'Semua') {
                return this.layanans;
            }
            return this.layanans.filter(l => l.category === this.selectedCategory);
        },

        get selectedDateQuota() {
            const q = this.calQuota[this.bookingDate];
            if (!q) {
                return { available: MAX_SLOT, is_full: false, total_used: 0 };
            }
            return q;
        },

        get quotaBadgeText() {
            if (this.selectedDateQuota.is_full) return 'Slot Penuh (0/5)';
            if (this.selectedDateQuota.is_blocked) return 'Workshop Tutup';
            const count = this.selectedDateQuota.available ?? MAX_SLOT;
            return `Tersedia ${count} dari ${MAX_SLOT} Slot`;
        },

        get quotaBadgeClass() {
            if (this.selectedDateQuota.is_full) {
                return 'border-rose-500/40 bg-rose-500/20 text-rose-300';
            }
            if ((this.selectedDateQuota.available ?? MAX_SLOT) <= 1) {
                return 'border-[#ff6b00]/40 bg-[#ff6b00]/20 text-[#ff6b00]';
            }
            return 'border-emerald-500/40 bg-emerald-500/20 text-emerald-400';
        },

        get primaryButtonLabel() {
            if (this.isSubmitting) return 'Memproses Booking...';
            if (this.currentStep === 1) return 'Lanjut ke Pemilihan Jadwal';
            if (this.currentStep === 2) return 'Lanjut ke Data Kendaraan';
            if (this.currentStep === 3) return 'Lanjut ke Pembayaran';
            return 'Konfirmasi & Buat Booking Sekarang';
        },

        get primaryButtonIcon() {
            if (this.currentStep === 4) return 'ph-bold ph-check';
            return 'ph-bold ph-arrow-right';
        },

        get calMonthLabel() {
            return `${MONTHS[this.calMonth]} ${this.calYear}`;
        },

        get weekRangeLabel() {
            if (!this.weekDays.length) return '';
            const start = this.weekDays[0];
            const end = this.weekDays[this.weekDays.length - 1];
            return `${start.dayNum} ${start.monthShort} - ${end.dayNum} ${end.monthShort} ${end.year}`;
        },

        // Navigasi Wizard
        goToStep(step) {
            if (step > 1 && !this.layananId) {
                alert('Silakan pilih salah satu layanan terlebih dahulu!');
                return;
            }
            if (step > 2 && !this.bookingDate) {
                alert('Silakan pilih tanggal pengerjaan terlebih dahulu!');
                return;
            }
            if (step > 2 && this.selectedDateQuota.is_full) {
                alert('Tanggal yang Anda pilih sudah PENUH (5/5). Silakan pilih tanggal lain yang masih memiliki slot!');
                return;
            }
            if (step > 3 && (!this.customerName.trim() || !this.customerPhone.trim())) {
                alert('Silakan isi Nama Lengkap dan Nomor WhatsApp Anda!');
                return;
            }
            this.currentStep = step;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },

        nextStep() {
            if (this.currentStep === 1) {
                this.goToStep(2);
            } else if (this.currentStep === 2) {
                this.goToStep(3);
            } else if (this.currentStep === 3) {
                this.goToStep(4);
            } else if (this.currentStep === 4) {
                this.submitBooking();
            }
        },

        prevStep() {
            if (this.currentStep > 1) {
                this.currentStep--;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        },

        handlePrimaryButton() {
            if (this.currentStep === 4) {
                const form = document.getElementById('bookingForm');
                if (form) {
                    form.requestSubmit();
                }
            } else {
                this.nextStep();
            }
        },

        selectLayanan(id) {
            this.layananId = id;
        },

        selectDate(dateStr) {
            this.bookingDate = dateStr;
            this.hasChosenDate = true;
            const [y, m] = dateStr.split('-').map(Number);
            if (y && m && (y !== this.calYear || m - 1 !== this.calMonth)) {
                this.calYear = y;
                this.calMonth = m - 1;
                this.loadCalQuota().then(() => this.renderCalendar());
            }
        },

        scrollStrip(direction) {
            const el = document.getElementById('dateStripContainer');
            if (el) {
                const offset = direction === 'left' ? -260 : 260;
                el.scrollBy({ left: offset, behavior: 'smooth' });
            }
        },

        isStripDisabled(dateStr) {
            const q = this.calQuota[dateStr];
            if (!q) return false;
            return Boolean(q.is_blocked || q.is_full || (q.available <= 0));
        },

        getStripCardClasses(dateStr) {
            const isSelected = this.bookingDate === dateStr;
            const q = this.calQuota[dateStr];
            const isFull = q ? Boolean(q.is_full || q.available <= 0) : false;
            const isBlocked = q ? Boolean(q.is_blocked) : false;

            if (isSelected) {
                return 'border-[#ff6b00] bg-gradient-to-b from-[#ff6b00]/25 via-[#ff6b00]/15 to-[#ff6b00]/5 text-white shadow-[0_0_20px_rgba(255,107,0,0.35)] ring-2 ring-[#ff6b00] scale-[1.03] z-10';
            }

            if (isBlocked) {
                return 'border-white/5 bg-white/[0.02] text-gray-500 cursor-not-allowed opacity-50';
            }

            if (isFull) {
                return 'border-rose-500/20 bg-rose-500/5 text-rose-300/80 cursor-not-allowed opacity-60';
            }

            if (q && q.available === 1) {
                return 'border-[#ff6b00]/30 bg-[#ff6b00]/5 text-gray-200 hover:border-[#ff6b00] hover:bg-[#ff6b00]/15 hover:scale-[1.02]';
            }

            return 'border-white/10 bg-[#0e0e11] text-gray-300 hover:border-[#ff6b00]/60 hover:bg-white/[0.04] hover:text-white hover:scale-[1.02]';
        },

        getStripSlotLabel(dateStr) {
            const q = this.calQuota[dateStr];
            if (!q) return '5 Slot';
            if (q.is_blocked) return 'TUTUP';
            if (q.is_full || (q.available <= 0)) return 'PENUH';
            if (q.available === 1) return 'Sisa 1 Slot';
            return `${q.available} Slot`;
        },

        getStripSlotTextClass(dateStr) {
            const isSelected = this.bookingDate === dateStr;
            const q = this.calQuota[dateStr];
            if (isSelected) return 'text-[#ff6b00] font-black';
            if (!q) return 'text-emerald-400 font-bold';
            if (q.is_blocked) return 'text-gray-500 font-medium';
            if (q.is_full || (q.available <= 0)) return 'text-rose-400 font-bold';
            if (q.available === 1) return 'text-[#ff6b00] font-bold';
            return 'text-emerald-400 font-bold';
        },

        checkAutoSelectAvailableDate() {
            const currentQ = this.calQuota[this.bookingDate];
            if (currentQ && (currentQ.is_full || currentQ.is_blocked || currentQ.available <= 0)) {
                const availableDay = this.stripDays.find(d => {
                    const q = this.calQuota[d.date_str];
                    return q ? (!q.is_full && !q.is_blocked && q.available > 0) : true;
                });
                if (availableDay) {
                    this.selectDate(availableDay.date_str);
                }
            }
        },

        setViewMode(mode) {
            this.viewMode = mode;
            if (mode === 'week') this.renderWeek();
        },

        async initApp() {
            if (this.bookingDate) {
                const [y, m] = this.bookingDate.split('-').map(Number);
                if (y && m) {
                    this.calYear = y;
                    this.calMonth = m - 1;
                }
            }
            await this.loadCalQuota();
            this.renderCalendar();
            this.renderWeek();
            this.checkAutoSelectAvailableDate();
        },

        async fetchMonthQuota(year, month1Indexed) {
            try {
                const res = await fetch(`/api/booking/quota-month/${year}/${month1Indexed}`);
                if (res.ok) {
                    const json = await res.json();
                    this.calQuota = { ...this.calQuota, ...(json.quota ?? {}) };
                }
            } catch (e) {
                console.error(`Gagal memuat kuota ${year}-${month1Indexed}:`, e);
            }
        },

        async loadCalQuota() {
            await this.fetchMonthQuota(this.calYear, this.calMonth + 1);

            // Jika strip 14 hari melintasi bulan berikutnya, muat juga kuota bulan berikutnya
            const now = new Date();
            const endStrip = new Date(now);
            endStrip.setDate(endStrip.getDate() + 14);
            if (endStrip.getMonth() !== now.getMonth()) {
                const nextMonthYear = endStrip.getFullYear();
                const nextMonthNum = endStrip.getMonth() + 1;
                if (nextMonthYear !== this.calYear || nextMonthNum !== (this.calMonth + 1)) {
                    await this.fetchMonthQuota(nextMonthYear, nextMonthNum);
                }
            }
        },

        renderCalendar() {
            const now = new Date();
            const todayStr = `${now.getFullYear()}-${String(now.getMonth()+1).padStart(2,'0')}-${String(now.getDate()).padStart(2,'0')}`;

            const firstDay = new Date(this.calYear, this.calMonth, 1);
            const startWeekday = firstDay.getDay();
            const daysInMonth = new Date(this.calYear, this.calMonth + 1, 0).getDate();
            const cells = [];

            for (let i = 0; i < startWeekday; i++) {
                cells.push({ empty: true, key: `empty_${i}` });
            }

            for (let d = 1; d <= daysInMonth; d++) {
                const monthStr = String(this.calMonth + 1).padStart(2, '0');
                const dayStr = String(d).padStart(2, '0');
                const dateStr = `${this.calYear}-${monthStr}-${dayStr}`;
                const past = dateStr < todayStr;
                const isToday = dateStr === todayStr;

                const q = this.calQuota[dateStr] || { available: MAX_SLOT, is_full: false, total_used: 0, is_blocked: false };
                const isBlocked = Boolean(q.is_blocked);
                const available = isBlocked ? 0 : (q.available ?? MAX_SLOT);
                const used = q.total_used ?? (MAX_SLOT - available);

                let status = 'available';
                if (past) status = 'past';
                else if (isBlocked) status = 'blocked';
                else if (available <= 0) status = 'full';
                else if (available === 1) status = 'few';

                cells.push({
                    empty: false,
                    key: dateStr,
                    date: dateStr,
                    day: d,
                    status,
                    isToday,
                    used,
                    available,
                    blockedReason: q.blocked_reason,
                    disabled: past || isBlocked || status === 'full',
                });
            }

            this.calCells = cells;
        },

        renderWeek() {
            const baseDate = new Date();
            baseDate.setDate(baseDate.getDate() + (this.weekOffset * 7));
            const dayOfWeek = baseDate.getDay();
            const sunday = new Date(baseDate);
            sunday.setDate(baseDate.getDate() - dayOfWeek);

            const now = new Date();
            const todayStr = `${now.getFullYear()}-${String(now.getMonth()+1).padStart(2,'0')}-${String(now.getDate()).padStart(2,'0')}`;

            const days = [];
            for (let i = 0; i < 7; i++) {
                const cur = new Date(sunday);
                cur.setDate(sunday.getDate() + i);

                const y = cur.getFullYear();
                const m = String(cur.getMonth() + 1).padStart(2, '0');
                const d = String(cur.getDate()).padStart(2, '0');
                const dateStr = `${y}-${m}-${d}`;
                const past = dateStr < todayStr;

                const q = this.calQuota[dateStr] || { available: MAX_SLOT, is_full: false, total_used: 0, is_blocked: false };
                const isBlocked = Boolean(q.is_blocked);
                const available = isBlocked ? 0 : (q.available ?? MAX_SLOT);
                const used = q.total_used ?? (MAX_SLOT - available);

                let status = 'available';
                let statusText = `${used}/5 Terisi`;
                let subText = `${available} slot tersisa`;

                if (past) {
                    status = 'past';
                    statusText = 'Lewat';
                    subText = 'Tidak dapat dipilih';
                } else if (isBlocked) {
                    status = 'blocked';
                    statusText = 'Tutup / Libur';
                    subText = q.blocked_reason || 'Tanggal diblokir';
                } else if (available <= 0) {
                    status = 'full';
                    statusText = 'PENUH (5/5)';
                    subText = '0 slot tersisa';
                } else if (available === 1) {
                    status = 'few';
                    statusText = 'Hampir Penuh (4/5)';
                    subText = 'Sisa 1 slot!';
                }

                days.push({
                    date: dateStr,
                    dayName: DAYS[cur.getDay()],
                    dayNum: cur.getDate(),
                    monthShort: MONTHS_SHORT[cur.getMonth()],
                    year: y,
                    status,
                    statusText,
                    subText,
                    disabled: past || isBlocked || status === 'full',
                });
            }

            this.weekDays = days;
        },

        getCellClasses(cell) {
            const isSelected = cell.date === this.bookingDate;

            if (isSelected) {
                return 'border-[#ff6b00] bg-[#ff6b00] text-black font-extrabold shadow-[0_0_18px_rgba(255,107,0,0.5)] ring-2 ring-white scale-105 z-10';
            }

            if (cell.status === 'past' || cell.status === 'blocked') {
                return 'border-white/5 bg-white/[0.02] text-gray-500 cursor-not-allowed opacity-50';
            }
            if (cell.status === 'full') {
                return 'border-rose-500/30 bg-rose-500/10 text-rose-300 cursor-not-allowed';
            }
            if (cell.status === 'few') {
                return 'border-[#ff6b00]/30 bg-[#ff6b00]/10 text-[#ff6b00] hover:border-[#ff6b00] hover:bg-[#ff6b00]/20';
            }
            return 'border-emerald-500/20 bg-emerald-500/5 text-emerald-100 hover:border-emerald-400 hover:bg-emerald-500/15';
        },

        async prevCalMonth() {
            this.calMonth--;
            if (this.calMonth < 0) { this.calMonth = 11; this.calYear--; }
            await this.loadCalQuota();
            this.renderCalendar();
        },

        async nextCalMonth() {
            this.calMonth++;
            if (this.calMonth > 11) { this.calMonth = 0; this.calYear++; }
            await this.loadCalQuota();
            this.renderCalendar();
        },

        async thisCalMonth() {
            const now = new Date();
            this.calYear = now.getFullYear();
            this.calMonth = now.getMonth();
            await this.loadCalQuota();
            this.renderCalendar();
        },

        prevWeek() {
            this.weekOffset--;
            this.renderWeek();
        },

        nextWeek() {
            this.weekOffset++;
            this.renderWeek();
        },

        prevDay() {
            const d = new Date(this.bookingDate);
            d.setDate(d.getDate() - 1);
            const now = new Date();
            now.setHours(0,0,0,0);
            if (d >= now) {
                this.selectDate(d.toISOString().slice(0, 10));
            }
        },

        nextDay() {
            const d = new Date(this.bookingDate);
            d.setDate(d.getDate() + 1);
            this.selectDate(d.toISOString().slice(0, 10));
        },

        formatDateShort(dateStr) {
            if (!dateStr) return 'Belum dipilih';
            const [y, m, d] = dateStr.split('-').map(Number);
            if (!y || !m || !d) return 'Belum dipilih';
            return `${d} ${MONTHS_SHORT[m - 1]} ${y}`;
        },

        formatDateFull(dateStr) {
            if (!dateStr) return 'Pilih Tanggal';
            const [y, m, d] = dateStr.split('-').map(Number);
            if (!y || !m || !d) return 'Pilih Tanggal';
            const dt = new Date(y, m - 1, d);
            return `${DAYS[dt.getDay()]}, ${d} ${MONTHS[m - 1]} ${y}`;
        },

        formatRupiah(num) {
            return 'Rp ' + Math.round(Number(num || 0)).toLocaleString('id-ID');
        },

        handleFile(event) {
            const file = event.target.files[0] || null;
            this.proofFile = file;
            this.proofFileName = file ? file.name : '';
        },

        copyRekening(nomor) {
            if (navigator.clipboard) {
                navigator.clipboard.writeText(nomor);
                this.rekeningCopied = true;
                setTimeout(() => {
                    this.rekeningCopied = false;
                }, 2000);
            }
        },

        submitBooking(e) {
            if (!this.layananId) {
                alert('Silakan pilih salah satu paket layanan!');
                this.currentStep = 1;
                if (e) e.preventDefault();
                return false;
            }
            if (!this.bookingDate) {
                alert('Silakan pilih tanggal booking pada kalender!');
                this.currentStep = 2;
                if (e) e.preventDefault();
                return false;
            }
            if (this.selectedDateQuota && this.selectedDateQuota.is_full) {
                alert('Tanggal yang Anda pilih sudah PENUH (5/5). Silakan pilih tanggal lain yang masih tersedia!');
                this.currentStep = 2;
                if (e) e.preventDefault();
                return false;
            }
            if (!this.customerName || !this.customerName.trim()) {
                alert('Silakan isi nama lengkap Anda!');
                this.currentStep = 3;
                if (e) e.preventDefault();
                return false;
            }
            if (!this.customerPhone || !this.customerPhone.trim()) {
                alert('Silakan isi nomor WhatsApp Anda!');
                this.currentStep = 3;
                if (e) e.preventDefault();
                return false;
            }

            this.isSubmitting = true;
            return true;
        }
    };
}
