<style>
    /* ==========================================================================
       MAIN SPLIT, PREVIEW TABLE & PRINT CENTER SCOPED STYLES
       ========================================================================== */

    /* 3. MAIN SPLIT SECTION (7/12 & 5/12) */
    .f-main-split {
        display: grid;
        grid-template-columns: 7fr 5fr;
        gap: 1.25rem;
        align-items: start;
    }
    @media (max-width: 1024px) {
        .f-main-split {
            grid-template-columns: 1fr;
        }
    }

    .f-card-panel {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 1rem;
        padding: 1.25rem 1.4rem;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
    }
    .dark .f-card-panel {
        background: #18181b;
        border-color: #27272a;
    }

    .f-section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
        padding-bottom: 0.65rem;
        border-bottom: 1px solid #f3f4f6;
    }
    .dark .f-section-header {
        border-bottom-color: #27272a;
    }
    .f-section-header-left {
        display: flex;
        align-items: center;
        gap: 0.65rem;
    }
    .f-section-icon-box {
        width: 2rem !important;
        height: 2rem !important;
        min-width: 2rem !important;
        min-height: 2rem !important;
        border-radius: 0.5rem;
        background: #fff7ed;
        color: #ea580c;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        flex-shrink: 0;
    }
    .dark .f-section-icon-box {
        background: rgba(234, 88, 12, 0.15);
        color: #fb923c;
    }
    .f-section-icon-box svg {
        width: 1.15rem !important;
        height: 1.15rem !important;
    }

    .f-section-title {
        font-size: 0.92rem;
        font-weight: 800;
        color: #111827;
        margin: 0;
        line-height: 1.2;
    }
    .dark .f-section-title {
        color: #ffffff;
    }
    .f-section-desc {
        font-size: 0.72rem;
        color: #6b7280;
        margin: 0.15rem 0 0 0;
    }
    .dark .f-section-desc {
        color: #9ca3af;
    }

    /* PREVIEW TABLE */
    .f-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.75rem;
        margin-top: 0.25rem;
    }
    .f-table th {
        text-align: left;
        padding: 0.6rem 0.75rem;
        font-size: 0.68rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #6b7280;
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
    }
    .dark .f-table th {
        background: #202023;
        border-bottom-color: #2e2e33;
        color: #9ca3af;
    }
    .f-table td {
        padding: 0.75rem;
        border-bottom: 1px solid #f3f4f6;
        vertical-align: middle;
    }
    .dark .f-table td {
        border-bottom-color: #27272a;
    }
    .f-table tr:hover td {
        background: #fcfcfc;
    }
    .dark .f-table tr:hover td {
        background: #1d1d20;
    }

    .f-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.2rem 0.5rem;
        border-radius: 0.375rem;
        font-size: 0.65rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }
    .f-badge-success {
        background: #dcfce7;
        color: #15803d;
    }
    .dark .f-badge-success {
        background: rgba(22, 163, 74, 0.2);
        color: #86efac;
    }
    .f-badge-info {
        background: #e0f2fe;
        color: #0369a1;
    }
    .dark .f-badge-info {
        background: rgba(2, 132, 199, 0.2);
        color: #7dd3fc;
    }
    .f-badge-warning {
        background: #fef3c7;
        color: #b45309;
    }
    .dark .f-badge-warning {
        background: rgba(217, 119, 6, 0.2);
        color: #fde68a;
    }

    /* EMPTY STATE */
    .f-empty-state {
        padding: 2.5rem 1.5rem;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    .f-empty-icon-wrap {
        width: 3.5rem !important;
        height: 3.5rem !important;
        min-width: 3.5rem !important;
        min-height: 3.5rem !important;
        border-radius: 1rem;
        background: #f3f4f6;
        color: #9ca3af;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        margin-bottom: 0.85rem;
    }
    .dark .f-empty-icon-wrap {
        background: #27272a;
        color: #71717a;
    }
    .f-empty-icon-wrap svg {
        width: 1.75rem !important;
        height: 1.75rem !important;
    }

    /* PRINT CENTER LIST */
    .f-print-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    .f-print-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.85rem 1rem;
        border-radius: 0.75rem;
        border: 1px solid #f3f4f6;
        background: #fafafa;
        transition: all 0.15s ease;
    }
    .dark .f-print-item {
        background: #202023;
        border-color: #2e2e33;
    }
    .f-print-item:hover {
        border-color: #FF6B00;
        background: #fffaf5;
    }
    .dark .f-print-item:hover {
        border-color: #FF6B00;
        background: rgba(255, 107, 0, 0.05);
    }

    .f-print-item-left {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .f-print-icon-box {
        width: 2.35rem !important;
        height: 2.35rem !important;
        min-width: 2.35rem !important;
        min-height: 2.35rem !important;
        border-radius: 0.6rem;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        flex-shrink: 0;
    }
    .dark .f-print-icon-box {
        background: rgba(234, 88, 12, 0.15);
    }
    .f-print-icon-box svg {
        width: 1.2rem !important;
        height: 1.2rem !important;
    }

    .f-print-item-title {
        font-size: 0.84rem;
        font-weight: 800;
        color: #111827;
        margin: 0;
        line-height: 1.2;
    }
    .dark .f-print-item-title {
        color: #ffffff;
    }
    .f-print-item-sub {
        font-size: 0.7rem;
        color: #6b7280;
        margin: 0.15rem 0 0 0;
    }
    .dark .f-print-item-sub {
        color: #9ca3af;
    }

    .f-btn-print {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 0.35rem !important;
        padding: 0.45rem 0.85rem !important;
        background: linear-gradient(135deg, #FF6B00 0%, #EA580C 100%) !important;
        color: #ffffff !important;
        border-radius: 0.6rem !important;
        font-size: 0.7rem !important;
        font-weight: 800 !important;
        letter-spacing: 0.03em !important;
        text-transform: uppercase !important;
        text-decoration: none !important;
        box-shadow: 0 2px 6px rgba(234, 88, 12, 0.2) !important;
        transition: all 0.15s ease !important;
        white-space: nowrap !important;
        flex-shrink: 0 !important;
    }
    .f-btn-print:hover {
        transform: translateY(-1px) !important;
        box-shadow: 0 4px 10px rgba(234, 88, 12, 0.3) !important;
    }
    .f-btn-print svg {
        width: 0.9rem !important;
        height: 0.9rem !important;
    }
</style>
