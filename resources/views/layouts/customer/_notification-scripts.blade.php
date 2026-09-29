<script>
    // =============================================
    // Notification Panel
    // =============================================
    let notifPanelOpen = false;
    let notifLoaded   = false;

    function toggleNotifPanel() {
        const panel = document.getElementById('notif-panel');
        notifPanelOpen = !notifPanelOpen;

        if (notifPanelOpen) {
            panel.classList.remove('hidden');
            panel.style.opacity = '0';
            panel.style.transform = 'translateY(-8px)';
            panel.style.transition = 'opacity 0.2s ease, transform 0.2s ease';
            requestAnimationFrame(() => {
                panel.style.opacity = '1';
                panel.style.transform = 'translateY(0)';
            });
            if (!notifLoaded) loadNotifikasi();
        } else {
            panel.style.opacity = '0';
            panel.style.transform = 'translateY(-8px)';
            setTimeout(() => panel.classList.add('hidden'), 200);
        }
    }

    // Tutup jika klik di luar panel
    document.addEventListener('click', function(e) {
        const wrapper = document.getElementById('notif-wrapper');
        if (notifPanelOpen && wrapper && !wrapper.contains(e.target)) {
            const panel = document.getElementById('notif-panel');
            panel.style.opacity = '0';
            panel.style.transform = 'translateY(-8px)';
            setTimeout(() => panel.classList.add('hidden'), 200);
            notifPanelOpen = false;
        }
    });

    function getIconByJudul(judul) {
        const j = (judul || '').toLowerCase();
        if (j.includes('selesai') || j.includes('complet')) return 'ph-check-circle text-green-400';
        if (j.includes('bayar') || j.includes('payment'))  return 'ph-credit-card text-yellow-400';
        if (j.includes('tolak') || j.includes('reject'))   return 'ph-x-circle text-red-400';
        if (j.includes('proses') || j.includes('mulai'))   return 'ph-gear text-blue-400';
        return 'ph-bell text-[#f2994a]';
    }

    function timeAgo(dateStr) {
        const diff = Math.floor((Date.now() - new Date(dateStr)) / 1000);
        if (diff < 60)   return diff + 'd lalu';
        if (diff < 3600) return Math.floor(diff/60) + 'm lalu';
        if (diff < 86400) return Math.floor(diff/3600) + 'j lalu';
        return Math.floor(diff/86400) + ' hari lalu';
    }

    async function loadNotifikasi() {
        const list = document.getElementById('notif-list');
        try {
            const res  = await fetch('{{ route("api.notifikasi.index") }}', {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            });
            const json = await res.json();
            const data = json.data || [];
            notifLoaded = true;

            const unread = data.filter(n => !n.is_read).length;
            updateBadge(unread);

            if (data.length === 0) {
                list.innerHTML = `
                    <div class="flex flex-col items-center justify-center py-10 text-gray-600">
                        <i class="ph ph-bell-slash text-4xl mb-2"></i>
                        <span class="text-xs font-medium">Tidak ada notifikasi</span>
                    </div>`;
                return;
            }

            list.innerHTML = data.map(n => `
                <div id="notif-item-${n.id_notif}" class="flex items-start gap-3 px-5 py-4 transition-all ${n.is_read ? 'opacity-60' : 'bg-[#f2994a]/[0.03]'} hover:bg-white/[0.03] cursor-pointer group" onclick="handleNotifClick(${n.id_notif}, ${n.id_pesanan || 'null'}, '${(n.judul || '').replace(/'/g, "\\'")}')">
                    <div class="w-9 h-9 rounded-xl bg-white/5 flex items-center justify-center shrink-0 mt-0.5">
                        <i class="ph ${getIconByJudul(n.judul)} text-lg"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-2">
                            <p class="text-xs font-bold text-white truncate">${n.judul || 'Notifikasi'}</p>
                            ${!n.is_read ? '<span class="w-2 h-2 bg-[#f2994a] rounded-full shrink-0"></span>' : ''}
                        </div>
                        <p class="text-[11px] text-gray-400 leading-relaxed mt-0.5 line-clamp-2">${n.pesan || ''}</p>
                        <span class="text-[9px] text-gray-600 font-medium mt-1 block">${timeAgo(n.created_at)}</span>
                    </div>
                </div>
            `).join('');

        } catch (err) {
            list.innerHTML = `
                <div class="flex flex-col items-center justify-center py-10 text-gray-600">
                    <i class="ph ph-warning text-3xl mb-2 text-red-500/60"></i>
                    <span class="text-xs">Gagal memuat notifikasi</span>
                </div>`;
        }
    }

    async function handleNotifClick(id_notif, id_pesanan, judul = '') {
        const item = document.getElementById('notif-item-' + id_notif);
        if (item) {
            item.classList.remove('bg-[#f2994a]/[0.03]');
            item.classList.add('opacity-60');
            const dot = item.querySelector('.w-2.h-2.bg-\\[\\#f2994a\\]');
            if (dot) dot.remove();
        }

        try {
            await fetch(`{{ url('/api/notifikasi') }}/${id_notif}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            const unread = document.querySelectorAll('[id^="notif-item-"] .w-2.h-2.bg-\\[\\#f2994a\\]').length;
            updateBadge(unread);
        } catch(e) {}

        if (id_pesanan && id_pesanan !== 'null') {
            window.location.href = `{{ url('/pesanan') }}/${id_pesanan}`;
        } else if ((judul || '').toLowerCase().includes('booking')) {
            window.location.href = `{{ route('booking.index') }}`;
        }
    }

    async function markAsRead(id) {
        return handleNotifClick(id, null);
    }

    async function markAllRead() {
        try {
            await fetch('{{ route("api.notifikasi.markAllAsRead") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            notifLoaded = false;
            loadNotifikasi();
        } catch(e) {}
    }

    function updateBadge(count) {
        const badge = document.getElementById('notif-badge');
        const label = document.getElementById('notif-count-label');
        if (count > 0) {
            badge.textContent = count > 9 ? '9+' : count;
            badge.classList.remove('hidden');
            badge.classList.add('flex');
            if (label) {
                label.textContent = count + ' belum dibaca';
                label.classList.remove('hidden');
            }
        } else {
            badge.classList.add('hidden');
            badge.classList.remove('flex');
            if (label) label.classList.add('hidden');
        }
    }

    async function checkUnreadCount() {
        try {
            const res  = await fetch('{{ route("api.notifikasi.unread") }}', {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            });
            const json = await res.json();
            const total = json.data?.unread_count ?? (typeof json.data === 'number' ? json.data : 0);
            updateBadge(total);
        } catch(e) {}
    }

    document.addEventListener('DOMContentLoaded', function() {
        checkUnreadCount();
        setInterval(function() {
            if (!notifPanelOpen) checkUnreadCount();
            else { notifLoaded = false; loadNotifikasi(); }
        }, 30000);
    });
</script>

<!-- Floating Toast Notification Component -->
@if(session('toast_success'))
    <div id="floating-toast" class="fixed bottom-24 right-6 z-50 flex items-center gap-3 bg-[#0E0E10] border border-[#FF6B00] text-white px-6 py-4 rounded-2xl shadow-[0_10px_30px_rgba(255,107,0,0.3)] animate-bounce-short transition-all duration-500">
        <div class="w-8 h-8 rounded-full bg-[#FF6B00]/20 flex items-center justify-center text-[#FF6B00] shrink-0">
            <i class="ph-bold ph-check-circle text-lg"></i>
        </div>
        <p class="text-xs font-bold">{{ session('toast_success') }}</p>
        <button onclick="document.getElementById('floating-toast').remove()" class="text-[#8A8D93] hover:text-white ml-2">
            <i class="ph-bold ph-x text-sm"></i>
        </button>
    </div>
    <script>
        setTimeout(() => {
            const toast = document.getElementById('floating-toast');
            if(toast) {
                toast.classList.add('opacity-0', 'translate-y-10');
                setTimeout(() => toast.remove(), 500);
            }
        }, 5000);
    </script>
@endif

@if(session('toast_error'))
    <div id="floating-toast-error" class="fixed bottom-24 right-6 z-50 flex items-center gap-3 bg-[#121212] border border-red-500 text-white px-6 py-4 rounded-2xl shadow-[0_10px_30px_rgba(239,68,68,0.3)] animate-bounce-short transition-all duration-500">
        <div class="w-8 h-8 rounded-full bg-red-500/20 flex items-center justify-center text-red-500 shrink-0">
            <i class="ph-bold ph-warning-circle text-lg"></i>
        </div>
        <p class="text-xs font-bold">{{ session('toast_error') }}</p>
        <button onclick="document.getElementById('floating-toast-error').remove()" class="text-gray-400 hover:text-white ml-2">
            <i class="ph-bold ph-x text-sm"></i>
        </button>
    </div>
    <script>
        setTimeout(() => {
            const toast = document.getElementById('floating-toast-error');
            if(toast) {
                toast.classList.add('opacity-0', 'translate-y-10');
                setTimeout(() => toast.remove(), 500);
            }
        }, 5000);
    </script>
@endif
