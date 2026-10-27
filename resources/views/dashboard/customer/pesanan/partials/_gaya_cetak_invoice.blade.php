<style>
    /* ── TABLE CARD ─────────────────────────────────── */
    .table-card {
        background: #111;
        border: 1px solid rgba(255,255,255,0.06);
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 16px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    thead th {
        background: rgba(255,255,255,0.02);
        font-size: 9px;
        font-weight: 700;
        color: #555;
        letter-spacing: 2px;
        text-transform: uppercase;
        padding: 16px 24px;
        text-align: right;
        border-bottom: 1px solid rgba(255,255,255,0.05);
    }
    thead th:first-child { text-align: left; }
    tbody td {
        padding: 18px 24px;
        font-size: 12px;
        border-bottom: 1px solid rgba(255,255,255,0.03);
        text-align: right;
    }
    tbody td:first-child { text-align: left; }
    tbody tr:last-child td { border-bottom: none; }
    .td-name {
        font-weight: 700;
        color: #fff;
        font-size: 13px;
    }
    .td-note {
        font-size: 10px;
        color: #555;
        margin-top: 3px;
    }
    .td-price { color: #888; font-weight: 500; }
    .td-subtotal { color: #fff; font-weight: 800; }

    /* ── TOTALS ─────────────────────────────────────── */
    .totals-section {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 16px;
    }
    .totals-box {
        background: #111;
        border: 1px solid rgba(255,255,255,0.06);
        border-radius: 16px;
        padding: 24px;
        width: 320px;
    }
    .total-row {
        display: flex;
        justify-content: space-between;
        font-size: 12px;
        padding: 8px 0;
        border-bottom: 1px solid rgba(255,255,255,0.05);
    }
    .total-row:last-of-type { border-bottom: none; }
    .total-row span:first-child { color: #666; }
    .total-row span:last-child { color: #ccc; font-weight: 600; }
    .total-grand {
        background: linear-gradient(135deg, #0f0f0f, #1a1a1a);
        border: 1px solid rgba(242,153,74,0.2);
        border-radius: 12px;
        padding: 16px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 14px;
    }
    .total-grand-label {
        font-size: 9px;
        font-weight: 800;
        color: #555;
        letter-spacing: 2px;
        text-transform: uppercase;
    }
    .total-grand-amount {
        font-size: 22px;
        font-weight: 900;
        color: #f2994a;
    }

    /* ── FOOTER ─────────────────────────────────────── */
    .invoice-footer {
        background: #0a0a0a;
        border: 1px solid rgba(255,255,255,0.05);
        border-radius: 16px;
        padding: 20px 28px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }
    .footer-printed { font-size: 10px; color: #444; }
    .footer-note { font-size: 9px; color: #333; text-align: center; }
    .footer-brand { font-size: 16px; font-weight: 900; color: #f2994a; text-align: right; }
    .footer-brand-sub { font-size: 9px; color: #444; letter-spacing: 2px; text-transform: uppercase; }

    /* ── PRINT STYLES ───────────────────────────────── */
    @media print {
        @page { size: A4; margin: 10mm 12mm; }
        body { background: #0a0a0a !important; color: #fff !important; print-color-adjust: exact !important; -webkit-print-color-adjust: exact !important; }
        .action-bar { display: none !important; }
        .invoice-wrapper { margin: 0 auto; padding: 0; max-width: 100%; }
    }
</style>
