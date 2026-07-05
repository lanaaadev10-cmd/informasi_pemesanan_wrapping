{{-- ============================================
    BAGIAN: Styling Layanan
    Deskripsi: Custom CSS styling khusus halaman layanan
============================================ --}}
<style>
    :root { --accent: {{ $accentColor }}; }
    .layanan-accent { color: var(--accent); }
    .layanan-hero-desc,
    .layanan-hero-desc * {
        color: var(--accent) !important;
    }
    .layanan-page {
        background: #0a0a0a;
        min-height: 100vh;
    }

    /* ── Service Card ── */
    .svc-card {
        background: linear-gradient(180deg, #161616 0%, #0f0f0f 100%);
        border: 1px solid rgba(255,255,255,0.06);
        border-radius: 20px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: transform .4s cubic-bezier(.22,.61,.36,1), box-shadow .4s ease, border-color .4s ease;
    }
    .svc-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 50px -12px rgba(242, 153, 74, 0.15);
        border-color: rgba(242, 153, 74, 0.25);
    }
    .svc-card:hover .svc-card-img {
        transform: scale(1.06);
    }
    .svc-card-img {
        transition: transform .6s cubic-bezier(.22,.61,.36,1);
    }

    /* ── Badge ── */
    .svc-badge {
        font-size: .6rem;
        font-weight: 700;
        letter-spacing: .12em;
        text-transform: uppercase;
        padding: 5px 12px;
        border-radius: 999px;
        backdrop-filter: blur(8px);
        border: 1px solid rgba(242, 153, 74, 0.25);
    }

    /* ── Feature Bullet ── */
    .svc-feat {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: .78rem;
        color: rgba(255,255,255,0.55);
    }
    .svc-feat-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: var(--accent);
        flex-shrink: 0;
        opacity: .7;
    }

    /* ── CTA Button ── */
    .svc-btn {
        display: block;
        text-align: center;
        background: var(--accent);
        color: #0a0a0a;
        font-weight: 800;
        font-size: .72rem;
        letter-spacing: .1em;
        text-transform: uppercase;
        padding: 14px 20px;
        border-radius: 12px;
        transition: opacity .2s, transform .2s;
    }
    .svc-btn:hover {
        opacity: .88;
        transform: scale(1.02);
    }

    /* ── Benefit Tag ── */
    .benefit-tag {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        border-radius: 999px;
        font-size: .78rem;
        font-weight: 600;
        color: rgba(255,255,255,0.8);
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.08);
    }
    .benefit-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: var(--accent);
    }

    /* ── Garansi Card ── */
    .garansi-card {
        background: linear-gradient(145deg, rgba(242,153,74,0.08) 0%, #111111 100%);
        border: 1px solid rgba(242,153,74,0.2);
        border-radius: 24px;
        padding: 2.5rem;
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    /* ── Ambient Glow ── */
    .glow-ambient-top {
        background: radial-gradient(ellipse 55% 35% at 50% 0%, rgba(242,153,74,0.08), transparent 70%);
        pointer-events: none;
    }
</style>
