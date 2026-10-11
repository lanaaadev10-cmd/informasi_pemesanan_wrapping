/**
 * Booking Wizard Alpine.js Component
 * Handles Step-by-Step Car Wrapping Service Booking Flow
 */
function bookingWizardApp(config) {
    const MONTHS = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    const MONTHS_SHORT = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    const DAYS = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    const MAX_SLOT = 5;

    return {
        // Data Layanan dari Katalog
        layanans: config.layanans || [],
        categories: config.categories || ['Semua', 'Jasa Pasang', 'Wrapping', 'Kaca Film', 'Audio'],
        selectedCategory: 'Semua',

        // Master Kendaraan & Add-ons
        vehicleBrands: config.vehicleBrands || [],
        availableAddons: config.availableAddons || [],
        selectedBrandId: '',
        selectedModelId: '',
        selectedVehicleSize: '',
        selectedAddonIds: [],

        // Data Layanan Khusus
        materialBrand: '',
        materialSize: '',
        materialCondition: 'baru',
        materialDisclaimerAgreed: false,
        materialPhotoName: '',
        customRequestNotes: '',
        customPhotoName: '',

        // Asisten Pintar Filter
        selectedVehicleType: 'semua', // 'semua' | 'motor' | 'mobil'
        selectedNeedType: 'semua',    // 'semua' | 'jasa-pasang' | 'wrapping' | 'partial'
        searchQuery: '',

        // Wizard / Single-Page Checkout Controls
        currentStep: 1,
        showServiceModal: false,

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

        get isJasaPasang() {
            return Boolean(this.selectedLayanan?.is_jasa_pasang || this.selectedLayanan?.jenis_layanan === 'jasa_pasang');
        },

        get isCustom() {
            return Boolean(this.selectedLayanan?.jenis_layanan === 'custom');
        },

        // Kendaraan Getters & Handlers
        get currentBrand() {
            if (!this.selectedBrandId) return null;
            return this.vehicleBrands.find(b => String(b.id) === String(this.selectedBrandId)) || null;
        },

        get availableModels() {
            return this.currentBrand?.models || [];
        },

        get currentModel() {
            if (!this.selectedModelId) return null;
            return this.availableModels.find(m => String(m.id) === String(this.selectedModelId)) || null;
        },

        onBrandChange() {
            this.selectedModelId = '';
            this.selectedVehicleSize = '';
            if (this.currentBrand) {
                this.vehicleName = this.currentBrand.name;
            }
        },

        onModelChange() {
            if (this.currentModel) {
                this.selectedVehicleSize = this.currentModel.vehicle_size || 'medium';
                this.vehicleName = `${this.currentBrand?.name || ''} ${this.currentModel.name}`.trim();
            }
        },

        sizeLabel(size) {
            const labels = {
                small: 'Small (S) - Motor / Hatchback',
                medium: 'Medium (M) - Sedan / Compact SUV',
                large: 'Large (L) - Medium SUV / MPV',
                xlarge: 'X-Large (XL) - Big SUV / Premium Van',
            };
            return labels[size] || (size ? size.toUpperCase() : '-');
        },

        sizeBadge(size) {
            const badges = {
                small: 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30',
                medium: 'bg-blue-500/20 text-blue-400 border-blue-500/30',
                large: 'bg-amber-500/20 text-amber-400 border-amber-500/30',
                xlarge: 'bg-purple-500/20 text-purple-400 border-purple-500/30',
            };
            return badges[size] || 'bg-white/10 text-gray-300 border-white/20';
        },

        // Add-ons Management
        toggleAddon(id) {
            const numId = Number(id);
            const idx = this.selectedAddonIds.indexOf(numId);
            if (idx > -1) {
                this.selectedAddonIds.splice(idx, 1);
            } else {
                this.selectedAddonIds.push(numId);
            }
        },

        isAddonSelected(id) {
            return this.selectedAddonIds.includes(Number(id));
        },

        get selectedAddonsList() {
            return this.availableAddons.filter(a => this.selectedAddonIds.includes(Number(a.id)));
        },

        // Financial & Breakdown Computations
        get basePrice() {
            return this.selectedLayanan ? Number(this.selectedLayanan.price || 0) : 0;
        },

        get addonsTotal() {
            return this.selectedAddonsList.reduce((sum, a) => sum + Number(a.price || 0), 0);
        },

        get grandTotal() {
            return this.basePrice + this.addonsTotal;
        },

        get dpAmount() {
            return Math.round(this.grandTotal * 0.5);
        },

        get currentPaymentAmount() {
            return this.paymentType === 'dp' ? this.dpAmount : this.grandTotal;
        },

        get remainingBalance() {
            return this.paymentType === 'dp' ? (this.grandTotal - this.dpAmount) : 0;
        },

        setVehicleFilter(v) {
            this.selectedVehicleType = v;
            // Motor tidak memiliki aksesoris: jika beralih ke motor saat aksesoris aktif, reset ke semua
            if (v === 'motor' && this.selectedNeedType === 'aksesoris') {
                this.selectedNeedType = 'semua';
                this.selectedCategory = 'Semua';
            }
        },

        setNeedType(need) {
            this.selectedNeedType = need;
            if (need === 'semua') {
                this.selectedCategory = 'Semua';
            } else if (need === 'wrapping') {
                this.selectedCategory = 'Full Wrap';
            } else if (need === 'jasa-pasang') {
                this.selectedCategory = 'Jasa Pasang';
            } else if (need === 'partial') {
                this.selectedCategory = 'Custom';
            } else if (need === 'aksesoris') {
                this.selectedCategory = 'Aksesoris & Film';
            }
        },

        setCategoryFilter(cat) {
            this.selectedCategory = cat;
            if (cat === 'Semua') {
                this.selectedNeedType = 'semua';
            } else if (cat === 'Full Wrap' || cat === 'Wrapping') {
                this.selectedNeedType = 'wrapping';
            } else if (cat === 'Jasa Pasang') {
                this.selectedNeedType = 'jasa-pasang';
            } else if (cat === 'Custom' || cat === 'Parsial & Custom' || cat === 'Parsial') {
                this.selectedNeedType = 'partial';
            } else if (cat === 'Aksesoris & Film' || cat === 'Lighting' || cat === 'Audio' || cat === 'Kaca Film') {
                this.selectedNeedType = 'aksesoris';
            }
        },

        get filteredLayanans() {
            let list = this.layanans;

            // 1. Filter Tipe Kendaraan (semua / motor / mobil)
            if (this.selectedVehicleType !== 'semua') {
                list = list.filter(l => (l.kategori || '').toLowerCase() === this.selectedVehicleType);
            }

            // 2. Filter Jenis Kebutuhan (semua / jasa-pasang / wrapping / partial / aksesoris / custom)
            if (this.selectedNeedType !== 'semua') {
                if (this.selectedNeedType === 'custom') {
                    return []; // Custom tidak ada paket baku; menampilkan kartu negosiasi WhatsApp
                } else if (this.selectedNeedType === 'jasa-pasang') {
                    list = list.filter(l => (l.scope_key === 'jasa-pasang') || l.is_jasa_pasang || (l.raw_tipe_paket || '').includes('jasa-pasang'));
                } else if (this.selectedNeedType === 'wrapping') {
                    list = list.filter(l => (l.scope_key === 'wrapping') || (!l.is_jasa_pasang && (l.raw_tipe_paket || '') === 'wrapping'));
                } else if (this.selectedNeedType === 'partial') {
                    list = list.filter(l => (l.scope_key === 'partial') || ['partial', 'striping'].includes((l.raw_tipe_paket || '').toLowerCase()));
                } else if (this.selectedNeedType === 'aksesoris') {
                    // Motor tidak ada aksesoris
                    list = list.filter(l => (l.kategori || '').toLowerCase() !== 'motor' && ((l.scope_key === 'aksesoris') || ['lighting', 'audio', 'window-film', 'aksesoris'].includes((l.raw_tipe_paket || '').toLowerCase())));
                }
            }

            // 3. Pencarian teks live (nama, deskripsi, kecocokan kendaraan, fitur)
            if (this.searchQuery && this.searchQuery.trim() !== '') {
                const q = this.searchQuery.toLowerCase().trim();
                list = list.filter(l => {
                    const name = (l.name || '').toLowerCase();
                    const desc = (l.deskripsi || '').toLowerCase();
                    const compat = (l.compatibility || '').toLowerCase();
                    const features = (l.fitur || []).join(' ').toLowerCase();
                    return name.includes(q) || desc.includes(q) || compat.includes(q) || features.includes(q);
                });
            }

            return list;
        },

        resetFilters() {
            this.selectedVehicleType = 'semua';
            this.selectedNeedType = 'semua';
            this.selectedCategory = 'Semua';
            this.searchQuery = '';
        },

        get hasActiveFilters() {
            return this.selectedVehicleType !== 'semua' ||
                this.selectedNeedType !== 'semua' ||
                this.selectedCategory !== 'Semua' ||
                Boolean(this.searchQuery && this.searchQuery.trim() !== '');
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
            if (this.currentStep === 1) return 'Lanjut ke Jadwal & Shift';
            if (this.currentStep === 2) return 'Lanjut ke Data Kendaraan';
            if (this.currentStep === 3) return 'Lanjut ke Pembayaran';
            return 'Bayar & Konfirmasi Booking Sekarang';
        },

        get primaryButtonIcon() {
            if (this.currentStep < 4) return 'ph-bold ph-arrow-right';
            return 'ph-bold ph-shield-check';
        },

        scrollToSection(id) {
            const el = document.getElementById(id);
            if (el) {
                el.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
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

        // Validasi per-step sebelum berpindah ke langkah berikutnya
        validateStep(step) {
            if (step === 1) {
                if (!this.layananId) {
                    alert('Silakan pilih salah satu paket layanan terlebih dahulu!');
                    return false;
                }
                if (this.isJasaPasang && !this.materialDisclaimerAgreed) {
                    alert('Untuk layanan Jasa Pasang (Bawa Bahan Sendiri), Anda wajib menyetujui pernyataan tanggung jawab material (Disclaimer).');
                    return false;
                }
                return true;
            }
            if (step === 2) {
                if (!this.bookingDate) {
                    alert('Silakan tentukan tanggal pengerjaan pada kalender atau strip tanggal!');
                    return false;
                }
                if (this.selectedDateQuota && this.selectedDateQuota.is_full) {
                    alert('Tanggal yang Anda pilih sudah PENUH (5/5). Silakan pilih tanggal lain yang masih tersedia!');
                    return false;
                }
                return true;
            }
            if (step === 3) {
                if (!this.customerName || !this.customerName.trim()) {
                    alert('Silakan lengkapi nama lengkap pemesan!');
                    return false;
                }
                if (!this.customerPhone || !this.customerPhone.trim()) {
                    alert('Silakan lengkapi nomor WhatsApp pemesan!');
                    return false;
                }
                return true;
            }
            return true;
        },

        // Navigasi Wizard
        goToStep(step) {
            if (step === this.currentStep) return true;
            if (step > this.currentStep) {
                for (let s = this.currentStep; s < step; s++) {
                    if (!this.validateStep(s)) {
                        return false;
                    }
                }
            }
            this.currentStep = step;
            window.scrollTo({ top: 80, behavior: 'smooth' });
            return true;
        },

        nextStep() {
            if (this.currentStep < 4) {
                if (this.validateStep(this.currentStep)) {
                    this.currentStep++;
                    window.scrollTo({ top: 80, behavior: 'smooth' });
                }
            } else {
                this.handlePrimaryButton();
            }
        },

        prevStep() {
            if (this.currentStep > 1) {
                this.currentStep--;
                window.scrollTo({ top: 80, behavior: 'smooth' });
            }
        },

        handlePrimaryButton() {
            if (this.currentStep < 4) {
                this.nextStep();
            } else {
                const form = document.getElementById('bookingForm');
                if (form) {
                    form.requestSubmit();
                }
            }
        },

        selectLayanan(id) {
            this.layananId = id;
            this.showServiceModal = false;
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
                return 'border-[#ff6b00] bg-orange-50 text-[#ff6b00] font-black shadow-md ring-2 ring-[#ff6b00] scale-[1.03] z-10';
            }

            if (isBlocked) {
                return 'border-slate-200 bg-slate-100 text-slate-400 cursor-not-allowed opacity-60';
            }

            if (isFull) {
                return 'border-rose-200 bg-rose-50 text-rose-500 cursor-not-allowed opacity-70';
            }

            if (q && q.available === 1) {
                return 'border-orange-300 bg-orange-50/60 text-slate-800 hover:border-[#ff6b00] hover:bg-orange-50 hover:scale-[1.02]';
            }

            return 'border-slate-200 bg-white text-slate-800 shadow-sm hover:border-[#ff6b00]/60 hover:bg-orange-50/30 hover:scale-[1.02]';
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
            const todayStr = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}-${String(now.getDate()).padStart(2, '0')}`;

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
            const todayStr = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}-${String(now.getDate()).padStart(2, '0')}`;

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
                return 'border-[#ff6b00] bg-[#ff6b00] text-white font-extrabold shadow-md ring-2 ring-[#ff6b00]/30 scale-105 z-10';
            }

            if (cell.status === 'past' || cell.status === 'blocked') {
                return 'border-slate-200 bg-slate-100 text-slate-400 cursor-not-allowed opacity-60';
            }
            if (cell.status === 'full') {
                return 'border-rose-200 bg-rose-50 text-rose-600 cursor-not-allowed';
            }
            if (cell.status === 'few') {
                return 'border-orange-300 bg-orange-50 text-[#ff6b00] hover:border-[#ff6b00] hover:bg-orange-100';
            }
            return 'border-emerald-200 bg-emerald-50 text-emerald-700 hover:border-emerald-400 hover:bg-emerald-100';
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
            now.setHours(0, 0, 0, 0);
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

        handleMaterialPhoto(event) {
            const file = event.target.files[0] || null;
            this.materialPhotoName = file ? file.name : '';
        },

        handleCustomPhoto(event) {
            const file = event.target.files[0] || null;
            this.customPhotoName = file ? file.name : '';
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
            for (let s = 1; s <= 3; s++) {
                if (!this.validateStep(s)) {
                    this.currentStep = s;
                    window.scrollTo({ top: 80, behavior: 'smooth' });
                    if (e) e.preventDefault();
                    return false;
                }
            }

            this.isSubmitting = true;
            return true;
        }
    };
}
