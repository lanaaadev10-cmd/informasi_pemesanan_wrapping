<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
        font-family: 'Inter', Arial, sans-serif;
        background: #0a0a0a;
        color: #fff;
        min-height: 100vh;
    }

    /* ── Floating Action Bar (screen only) ──────────── */
    .action-bar {
        position: fixed;
        top: 0; left: 0; right: 0;
        z-index: 999;
        background: rgba(10,10,10,0.95);
        backdrop-filter: blur(12px);
        border-bottom: 1px solid rgba(255,255,255,0.06);
        padding: 14px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }
    .action-bar a {
        color: #888;
        text-decoration: none;
        font-size: 12px;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: color .2s;
    }
    .action-bar a:hover { color: #fff; }
    .btn-print {
        background: #f2994a;
        color: #000;
        border: none;
        font-family: 'Inter', sans-serif;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 1px;
        text-transform: uppercase;
        padding: 10px 22px;
        border-radius: 10px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: background .2s, transform .1s;
        box-shadow: 0 4px 20px rgba(242,153,74,0.35);
    }
    .btn-print:hover { background: #e28a44; }
    .btn-print:active { transform: scale(0.97); }

    /* ── Invoice Wrapper ────────────────────────────── */
    .invoice-wrapper {
        max-width: 860px;
        margin: 80px auto 60px;
        padding: 0 16px;
    }

    /* ── CARD base ──────────────────────────────────── */
    .card {
        background: #111;
        border: 1px solid rgba(255,255,255,0.06);
        border-radius: 20px;
        overflow: hidden;
    }

    /* ── HEADER CARD ────────────────────────────────── */
    .invoice-header {
        background: linear-gradient(135deg, #0f0f0f 0%, #1a1a1a 100%);
        border-radius: 20px;
        padding: 40px;
        margin-bottom: 16px;
        border: 1px solid rgba(242,153,74,0.15);
        position: relative;
        overflow: hidden;
    }
    .invoice-header::before {
        content: '';
        position: absolute;
        top: -80px; right: -80px;
        width: 300px; height: 300px;
        background: rgba(242,153,74,0.06);
        border-radius: 50%;
    }
    .header-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 20px;
        margin-bottom: 32px;
    }
    .brand-name {
        font-size: 28px;
        font-weight: 900;
        color: #f2994a;
        letter-spacing: -0.5px;
    }
    .brand-sub {
        font-size: 11px;
        color: #555;
        letter-spacing: 3px;
        text-transform: uppercase;
        margin-top: 2px;
    }
    .badge-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 9999px;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 1px;
        text-transform: uppercase;
    }
    .badge-lunas {
        background: rgba(34,197,94,0.12);
        color: #22c55e;
        border: 1px solid rgba(34,197,94,0.3);
    }
    .badge-pending {
        background: rgba(242,153,74,0.12);
        color: #f2994a;
        border: 1px solid rgba(242,153,74,0.3);
    }
    .badge-dot {
        width: 6px; height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    .header-meta {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 20px;
        padding-top: 24px;
        border-top: 1px solid rgba(255,255,255,0.06);
    }
    .stat-label {
        font-size: 9px;
        color: #555;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        margin-bottom: 4px;
    }
    .stat-value {
        font-size: 12px;
        font-weight: 700;
        color: #ddd;
    }

    /* ── BILLED TO & SPECS ──────────────────────────── */
    .info-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-bottom: 16px;
    }
    @media (max-width: 600px) { .info-row { grid-template-columns: 1fr; } }
    .info-card {
        background: #111;
        border: 1px solid rgba(255,255,255,0.06);
        border-radius: 16px;
        padding: 24px;
    }
    .info-section-label {
        font-size: 9px;
        color: #f2994a;
        letter-spacing: 2px;
        text-transform: uppercase;
        font-weight: 700;
        margin-bottom: 12px;
    }
    .info-name {
        font-size: 15px;
        font-weight: 800;
        color: #fff;
        margin-bottom: 6px;
    }
    .info-detail {
        font-size: 11px;
        color: #666;
        line-height: 1.7;
    }
</style>
