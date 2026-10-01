{{-- ═══════════════════════════════════════════════════════════
     MODAL RATING & ULASAN TERPADU (BOOKING & PESANAN)
     Desain Premium Glassmorphism, Interaktif & Responsif Mobile
═══════════════════════════════════════════════════════════ --}}
<div x-data="ratingModalHandler()"
     @open-rating-modal.window="openModal($event.detail)"
     @keydown.escape.window="closeModal()"
     x-cloak
     x-show="isOpen"
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;">

    {{-- Backdrop --}}
    <div x-show="isOpen"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="closeModal()"
         class="fixed inset-0 bg-black/80 backdrop-blur-md transition-opacity"></div>

    {{-- Modal Dialog Container --}}
    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div x-show="isOpen"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             @click.stop
             class="relative transform overflow-hidden rounded-3xl bg-[#121215] border border-white/10 text-left shadow-2xl transition-all sm:my-8 w-full max-w-lg p-5 sm:p-7">

            {{-- Close Button --}}
            <button type="button"
                    @click="closeModal()"
                    class="absolute top-5 right-5 w-8 h-8 rounded-full bg-white/5 border border-white/10 text-gray-400 hover:text-white hover:bg-white/10 flex items-center justify-center transition-all">
                <i class="ph-bold ph-x text-base"></i>
            </button>

            {{-- Header --}}
            <div class="flex items-center gap-3 mb-5">
                <div class="w-12 h-12 rounded-2xl bg-[#FF6B00]/15 border border-[#FF6B00]/30 text-[#FF6B00] flex items-center justify-center shrink-0">
                    <i class="ph-fill ph-star text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-lg sm:text-xl font-audiowide font-bold text-white">Beri Ulasan Layanan</h3>
                    <p class="text-xs font-questrial text-gray-400 mt-0.5" x-text="serviceName || 'Layanan Wrapping & Variasi'"></p>
                </div>
            </div>

            {{-- Reference Badge --}}
            <div class="flex items-center justify-between bg-white/[0.03] border border-white/5 rounded-2xl px-4 py-2.5 mb-5 text-xs">
                <span class="text-gray-400 font-questrial">Referensi Transaksi:</span>
                <span class="font-mono font-bold text-[#FF6B00]" x-text="orderCode || '-'"></span>
            </div>

            {{-- Form Rating --}}
            <form @submit.prevent="submitRating()" class="space-y-5">
                {{-- 1. Rating Stars --}}
                <div class="text-center bg-white/[0.02] border border-white/5 rounded-2xl p-4">
                    <label class="block text-xs font-montserrat font-bold text-gray-300 uppercase tracking-wider mb-2">
                        Beri Nilai Kepuasan Anda
                    </label>
                    <div class="flex items-center justify-center gap-2 my-2">
                        <template x-for="star in [1, 2, 3, 4, 5]" :key="star">
                            <button type="button"
                                    @click="rating = star"
                                    @mouseenter="hoverRating = star"
                                    @mouseleave="hoverRating = 0"
                                    class="p-1 focus:outline-none transition-transform hover:scale-125 active:scale-95">
                                <i class="ph-fill text-3xl transition-colors"
                                   :class="(hoverRating ? star <= hoverRating : star <= rating) ? 'text-[#FFB800] drop-shadow-[0_0_8px_rgba(255,184,0,0.5)]' : 'text-white/20'">
                                </i>
                            </button>
                        </template>
                    </div>
                    {{-- Dynamic Star Label --}}
                    <p class="text-xs font-montserrat font-bold transition-all min-h-[18px]"
                       :class="rating > 0 ? 'text-[#FFB800]' : 'text-gray-500'"
                       x-text="getRatingLabel()"></p>
                </div>

                {{-- 2. Ulasan Textarea --}}
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="ulasan-input" class="text-xs font-montserrat font-bold text-gray-300 uppercase tracking-wider">
                            Ceritakan Pengalaman Anda <span class="text-gray-500 font-normal lowercase">(opsional)</span>
                        </label>
                        <span class="text-[10px] font-mono text-gray-500" x-text="`${ulasan.length}/500`"></span>
                    </div>
                    <textarea id="ulasan-input"
                              x-model="ulasan"
                              maxlength="500"
                              rows="3"
                              placeholder="Bagaimana kerapian stiker, ketepatan waktu pengerjaan, dan pelayanan teknisi kami?"
                              class="w-full bg-[#18181C] border border-white/10 rounded-2xl p-3.5 text-xs text-white placeholder-gray-500 focus:border-[#FF6B00] focus:ring-1 focus:ring-[#FF6B00] outline-none transition-all resize-none"></textarea>
                </div>

                {{-- 3. Foto Hasil / Media Upload --}}
                <div>
                    <label class="block text-xs font-montserrat font-bold text-gray-300 uppercase tracking-wider mb-2">
                        Foto Hasil Pengerjaan <span class="text-gray-500 font-normal lowercase">(maks. 2 foto)</span>
                    </label>
                    <div class="flex items-center gap-3">
                        {{-- Photo Previews --}}
                        <template x-for="(photo, index) in previews" :key="index">
                            <div class="relative w-20 h-20 rounded-2xl overflow-hidden border border-white/10 group">
                                <img :src="photo" class="w-full h-full object-cover">
                                <button type="button"
                                        @click="removePhoto(index)"
                                        class="absolute top-1 right-1 w-6 h-6 rounded-full bg-red-600/90 text-white flex items-center justify-center text-xs opacity-90 hover:opacity-100 transition-opacity">
                                    <i class="ph-bold ph-trash"></i>
                                </button>
                            </div>
                        </template>

                        {{-- Add Photo Button --}}
                        <label x-show="previews.length < 2"
                               class="w-20 h-20 rounded-2xl border-2 border-dashed border-white/10 hover:border-[#FF6B00] bg-white/[0.02] hover:bg-white/5 flex flex-col items-center justify-center cursor-pointer transition-all text-gray-400 hover:text-[#FF6B00]">
                            <i class="ph-bold ph-camera text-xl"></i>
                            <span class="text-[9px] font-montserrat font-bold mt-1">Upload</span>
                            <input type="file"
                                   accept="image/jpeg,image/png,image/webp"
                                   multiple
                                   @change="handlePhotoUpload($event)"
                                   class="sr-only">
                        </label>
                    </div>
                </div>

                {{-- Error Message Display --}}
                <div x-show="errorMessage"
                     x-transition
                     class="bg-red-500/10 border border-red-500/30 text-red-400 text-xs rounded-xl p-3"
                     x-text="errorMessage"></div>

                {{-- Submit Button --}}
                <div class="pt-2 flex items-center gap-3">
                    <button type="button"
                            @click="closeModal()"
                            :disabled="isLoading"
                            class="w-1/3 py-3 rounded-2xl border border-white/10 text-gray-300 font-montserrat font-bold text-xs uppercase tracking-wider hover:bg-white/5 active:scale-95 transition-all">
                        Batal
                    </button>
                    <button type="submit"
                            :disabled="isLoading || rating === 0"
                            class="w-2/3 py-3 rounded-2xl bg-[#FF6B00] hover:bg-[#E05D00] text-black font-montserrat font-black text-xs uppercase tracking-wider shadow-[0_4px_20px_rgba(255,107,0,0.35)] hover:scale-[1.02] active:scale-95 transition-all disabled:opacity-40 disabled:cursor-not-allowed disabled:transform-none flex items-center justify-center gap-2">
                        <i x-show="isLoading" class="ph-bold ph-spinner animate-spin text-base"></i>
                        <span x-text="isLoading ? 'Mengirim...' : (isEdit ? 'Perbarui Ulasan' : 'Kirim Ulasan Sekarang')"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function ratingModalHandler() {
    return {
        isOpen: false,
        isLoading: false,
        isEdit: false,
        bookingId: null,
        pesananId: null,
        layananId: null,
        serviceName: '',
        orderCode: '',
        rating: 5,
        hoverRating: 0,
        ulasan: '',
        files: [],
        previews: [],
        errorMessage: '',

        openModal(detail) {
            this.bookingId = detail.bookingId || null;
            this.pesananId = detail.pesananId || null;
            this.layananId = detail.layananId || null;
            this.serviceName = detail.serviceName || 'Layanan Wrapping';
            this.orderCode = detail.orderCode || '';
            this.rating = detail.currentRating || 5;
            this.ulasan = detail.currentUlasan || '';
            this.isEdit = !!detail.currentRating;
            this.files = [];
            this.previews = [];
            this.errorMessage = '';
            this.isOpen = true;
            document.body.classList.add('overflow-hidden');
        },

        closeModal() {
            if (this.isLoading) return;
            this.isOpen = false;
            document.body.classList.remove('overflow-hidden');
        },

        getRatingLabel() {
            const active = this.hoverRating || this.rating;
            switch(active) {
                case 1: return 'Sangat Kecewa 😞';
                case 2: return 'Kurang Puas 🙁';
                case 3: return 'Cukup Baik 😐';
                case 4: return 'Bagus & Puas! 😊';
                case 5: return 'Sempurna & Sangat Memuaskan! ⭐⭐⭐⭐⭐';
                default: return 'Sentuh bintang untuk menilai';
            }
        },

        handlePhotoUpload(event) {
            const selectedFiles = Array.from(event.target.files);
            const availableSlots = 2 - this.files.length;
            const toAdd = selectedFiles.slice(0, availableSlots);

            toAdd.forEach(file => {
                if (file.size > 5 * 1024 * 1024) {
                    alert('Ukuran file ' + file.name + ' melebihi batas 5MB.');
                    return;
                }
                this.files.push(file);
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.previews.push(e.target.result);
                };
                reader.readAsDataURL(file);
            });
            event.target.value = '';
        },

        removePhoto(index) {
            this.files.splice(index, 1);
            this.previews.splice(index, 1);
        },

        async submitRating() {
            if (this.rating === 0) {
                this.errorMessage = 'Silakan pilih bintang penilaian Anda (1-5).';
                return;
            }

            this.isLoading = true;
            this.errorMessage = '';

            const formData = new FormData();
            formData.append('rating', this.rating);
            if (this.ulasan) formData.append('ulasan', this.ulasan);
            if (this.bookingId) formData.append('booking_id', this.bookingId);
            if (this.pesananId) formData.append('id_pesanan', this.pesananId);
            if (this.layananId) formData.append('id_layanan', this.layananId);

            this.files.forEach(file => {
                formData.append('foto[]', file);
            });

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            try {
                const response = await fetch('{{ route('rating.quick-submit') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    this.closeModal();
                    if (window.toastNotification) {
                        window.toastNotification.success(data.message || 'Terima kasih atas ulasan Anda!');
                    } else {
                        alert(data.message || 'Terima kasih atas ulasan Anda!');
                    }
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    this.errorMessage = data.message || 'Terjadi kesalahan saat menyimpan ulasan.';
                }
            } catch (err) {
                console.error(err);
                this.errorMessage = 'Gagal terhubung ke server. Silakan coba lagi.';
            } finally {
                this.isLoading = false;
            }
        }
    };
}

window.openRatingModal = function(detail) {
    window.dispatchEvent(new CustomEvent('open-rating-modal', { detail: detail }));
};
</script>
