<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fadeIn 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    /* Sembunyikan panah di input number */
    input[type="number"]::-webkit-inner-spin-button, 
    input[type="number"]::-webkit-outer-spin-button { 
        -webkit-appearance: none; margin: 0; 
    }
</style>

<script>
    function updateLokasiLabel() {
        // Lokasi pengerjaan sudah tetap
    }

    function formatTanggal(isoString) {
        if(!isoString) return "-";
        const date = new Date(isoString);
        return date.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute:'2-digit' }) + ' WIB';
    }

    function goToStep(step) {
        if (step === 3) {
            const currentPanel = document.getElementById('step-panel-2');
            const requiredInputs = currentPanel.querySelectorAll('input[required], textarea[required]');
            let allValid = true;
            requiredInputs.forEach(input => {
                if (!input.value) {
                    allValid = false;
                    input.classList.add('border-red-500');
                    input.classList.remove('border-white/10');
                } else {
                    input.classList.remove('border-red-500');
                    input.classList.add('border-white/10');
                }
            });
            if (!allValid) {
                alert('{{ $profil->alert_lengkapi_data ?? 'Harap lengkapi semua data wajib bertanda bintang merah (*).' }}');
                return;
            }

            document.getElementById('review-merk').innerText = document.getElementById('input_merk').value;
            document.getElementById('review-warna').innerText = document.getElementById('input_warna').value;
            document.getElementById('review-nopol').innerText = document.getElementById('input_nopol').value.toUpperCase();
            document.getElementById('review-tahun').innerText = document.getElementById('input_tahun').value;
            
            document.getElementById('review-jadwal').innerText = formatTanggal(document.getElementById('input_jadwal').value);
            
            document.getElementById('review-lokasi').innerHTML = 'Dantie Setiker<br><span class="text-[9px] text-gray-500 leading-tight">Jl. Abu Hasan No.7, Area Sawah, Kedaleman, Kec. Rogojampi, Kabupaten Banyuwangi</span>';
        }

        document.querySelectorAll('[id^="step-panel-"]').forEach(p => {
            p.classList.add('hidden');
            p.classList.remove('animate-fade-in');
        });
        
        const target = document.getElementById('step-panel-' + step);
        if(target) {
            target.classList.remove('hidden');
            void target.offsetWidth;
            target.classList.add('animate-fade-in');
        }

        const title = document.getElementById('page-title');
        const subtitle = document.getElementById('page-subtitle');
        
        if (step === 3) {
            title.innerText = "{{ $profil->cta_konfirmasi_pemesanan ?? 'Konfirmasi Pemesanan' }}";
            subtitle.innerText = "{{ $profil->checkout_review_prompt ?? 'Harap tinjau kembali detail pesanan Anda sebelum melanjutkan ke pembayaran.' }}";
            
            document.getElementById('step-circle-2').innerHTML = '<i class="ph-bold ph-check"></i>';
            document.getElementById('step-circle-2').classList.remove('scale-110');
            document.getElementById('step-line-2').classList.add('bg-[#f2994a]', 'shadow-[0_0_10px_rgba(242,153,74,0.5)]');
            document.getElementById('step-line-2').classList.remove('bg-white/10');
            
            document.getElementById('step-circle-3').className = "w-10 h-10 rounded-full bg-[#f2994a] text-black font-bold flex items-center justify-center text-sm shadow-[0_0_15px_rgba(242,153,74,0.4)] transition-all scale-110";
            document.getElementById('step-label-3').className = "text-[10px] font-bold text-[#f2994a] transition-all text-center";
        } else if (step === 2) {
            title.innerText = "{{ $profil->section_data_kendaraan ?? 'Data Kendaraan & Jadwal' }}";
            subtitle.innerText = "{{ $profil->checkout_lengkapi_prompt ?? 'Harap lengkapi informasi kendaraan dan jadwal penyerahan sebelum tinjauan.' }}";
            
            document.getElementById('step-circle-2').innerHTML = '<i class="ph-bold ph-pencil-simple text-lg"></i>';
            document.getElementById('step-circle-2').classList.add('scale-110');
            document.getElementById('step-line-2').classList.remove('bg-[#f2994a]', 'shadow-[0_0_10px_rgba(242,153,74,0.5)]');
            document.getElementById('step-line-2').classList.add('bg-white/10');
            
            document.getElementById('step-circle-3').className = "w-10 h-10 rounded-full bg-[#202020] text-gray-400 font-bold flex items-center justify-center text-sm border border-white/10 transition-all";
            document.getElementById('step-label-3').className = "text-[10px] font-bold text-gray-500 transition-all text-center";
        }

        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function prepareSubmit() {
        const nopol = document.getElementById('input_nopol').value;
        const tahun = document.getElementById('input_tahun').value;
        
        let customData = `Nomor Polisi: ${nopol.toUpperCase()} | Tahun Produksi: ${tahun}`;
        
        document.getElementById('hidden_keterangan').value = customData;
    }
</script>
