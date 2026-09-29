<style>
    /* ==========================================================================
       FINANCIAL HUB & INTERACTIVE PREVIEW - BULLETPROOF SCOPED STYLES
       ========================================================================== */

    .f-hub-container {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
        width: 100%;
    }

    /* STRICT GLOBAL SVG RESIZING (Prevents runaway SVG icon blowups) */
    .f-hub-container svg {
        display: inline-block !important;
        vertical-align: middle !important;
        max-width: 100% !important;
        max-height: 100% !important;
        flex-shrink: 0 !important;
    }

    /* 1. FILTER TOOLBAR */
    .f-filter-toolbar {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 1rem;
        padding: 1rem 1.25rem;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 0.85rem;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
    }
    .dark .f-filter-toolbar {
        background: #18181b;
        border-color: #27272a;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.25);
    }

    .f-pill-group {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        flex-wrap: wrap;
    }

    .f-toolbar-label {
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #6b7280;
        margin-right: 0.25rem;
    }
    .dark .f-toolbar-label {
        color: #9ca3af;
    }

    .f-pill-btn {
        padding: 0.35rem 0.8rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.15s ease;
        border: 1px solid #e5e7eb;
        background: #f9fafb;
        color: #4b5563;
        line-height: 1.2;
    }
    .dark .f-pill-btn {
        background: #27272a;
        border-color: #3f3f46;
        color: #d1d5db;
    }
    .f-pill-btn:hover {
        border-color: #FF6B00;
        color: #FF6B00;
    }
    .f-pill-btn.active {
        background: linear-gradient(135deg, #FF6B00 0%, #EA580C 100%);
        border-color: #EA580C;
        color: #ffffff !important;
        box-shadow: 0 2px 6px rgba(234, 88, 12, 0.25);
    }

    .f-filter-controls {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        flex-wrap: wrap;
    }

    .f-date-picker-wrap {
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }

    .f-input-control {
        padding: 0.38rem 0.65rem;
        border-radius: 0.6rem;
        border: 1px solid #d1d5db;
        background: #ffffff;
        font-size: 0.76rem;
        font-weight: 500;
        color: #111827;
        outline: none;
        transition: border-color 0.15s ease;
        height: 32px;
    }
    .dark .f-input-control {
        background: #27272a;
        border-color: #3f3f46;
        color: #f3f4f6;
    }
    .f-input-control:focus {
        border-color: #FF6B00;
        box-shadow: 0 0 0 2px rgba(255, 107, 0, 0.15);
    }

    /* 2. KPI STAT CARDS */
    .f-kpi-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 1rem;
    }
    @media (max-width: 900px) {
        .f-kpi-grid {
            grid-template-columns: 1fr;
        }
    }

    .f-kpi-card {
        border-radius: 1rem;
        padding: 1.25rem 1.4rem;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 120px;
    }
    .dark .f-kpi-card {
        background: #18181b;
        border-color: #27272a;
    }

    .f-kpi-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.5rem;
    }
    .f-kpi-card-title {
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.06em;
    }
    .f-kpi-icon-badge {
        width: 2rem !important;
        height: 2rem !important;
        min-width: 2rem !important;
        min-height: 2rem !important;
        border-radius: 0.5rem;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }
    .f-kpi-icon-badge svg {
        width: 1.15rem !important;
        height: 1.15rem !important;
    }

    .f-kpi-val {
        font-size: 1.65rem;
        font-weight: 900;
        letter-spacing: -0.02em;
        line-height: 1.15;
        margin: 0.2rem 0;
    }
    .f-kpi-subtext {
        font-size: 0.72rem;
        color: #6b7280;
        margin: 0;
        font-weight: 500;
    }
    .dark .f-kpi-subtext {
        color: #9ca3af;
    }
</style>
