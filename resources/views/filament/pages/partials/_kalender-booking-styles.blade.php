{{-- Scoped Styles for Guaranteed Layout & Sizing for Admin Booking Calendar --}}
<style>
    .calendar-page-wrapper {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
        font-family: inherit;
    }
    .cal-stats-grid {
        display: grid;
        grid-template-columns: repeat(1, minmax(0, 1fr));
        gap: 1rem;
    }
    @media (min-width: 768px) {
        .cal-stats-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }
    .cal-stat-card {
        padding: 1.25rem;
        border-radius: 1rem;
        background-color: #ffffff;
        border: 1px solid #e5e7eb;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .dark .cal-stat-card {
        background-color: #111827;
        border-color: #1f2937;
    }
    .cal-icon-box {
        width: 48px;
        height: 48px;
        min-width: 48px;
        min-height: 48px;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .cal-icon-box svg {
        width: 24px !important;
        height: 24px !important;
        max-width: 24px !important;
        max-height: 24px !important;
        min-width: 24px !important;
        min-height: 24px !important;
    }
    .cal-main-box {
        padding: 1.5rem;
        border-radius: 1.5rem;
        background-color: #ffffff;
        border: 1px solid #e5e7eb;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }
    .dark .cal-main-box {
        background-color: #111827;
        border-color: #1f2937;
    }
    .cal-controls-row {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        border-bottom: 1px solid #f3f4f6;
        padding-bottom: 1rem;
    }
    .dark .cal-controls-row {
        border-bottom-color: #1f2937;
    }
    .cal-btn {
        padding: 0.4rem 0.85rem;
        border-radius: 0.75rem;
        border: 1px solid #d1d5db;
        background-color: #ffffff;
        color: #374151;
        font-size: 0.75rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .cal-btn:hover {
        background-color: #f9fafb;
        border-color: #9ca3af;
    }
    .dark .cal-btn {
        background-color: #1f2937;
        border-color: #374151;
        color: #d1d5db;
    }
    .dark .cal-btn:hover {
        background-color: #374151;
    }
    .cal-btn-primary {
        background-color: #fff7ed;
        border-color: #fed7aa;
        color: #ea580c;
    }
    .cal-btn-primary:hover {
        background-color: #ffedd5;
    }
    .dark .cal-btn-primary {
        background-color: rgba(234, 88, 12, 0.15);
        border-color: rgba(234, 88, 12, 0.3);
        color: #fb923c;
    }
    .cal-grid-7 {
        display: grid;
        grid-template-columns: repeat(7, minmax(0, 1fr));
        gap: 0.5rem;
    }
    .cal-day-cell {
        height: 85px;
        border-radius: 0.85rem;
        border: 1px solid #e5e7eb;
        padding: 0.5rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        cursor: pointer;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
        background-color: #ffffff;
    }
    .cal-day-cell:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.08);
    }
    .cal-day-cell-empty {
        height: 85px;
        border-radius: 0.85rem;
        background-color: #f9fafb;
        opacity: 0.5;
    }
    .dark .cal-day-cell-empty {
        background-color: #1f2937;
    }
    .cal-cell-available {
        border-color: #a7f3d0;
        background-color: #f0fdf4;
        color: #065f46;
    }
    .dark .cal-cell-available {
        border-color: rgba(16, 185, 129, 0.3);
        background-color: rgba(16, 185, 129, 0.1);
        color: #6ee7b7;
    }
    .cal-cell-warning {
        border-color: #fde68a;
        background-color: #fffbeb;
        color: #92400e;
    }
    .dark .cal-cell-warning {
        border-color: rgba(245, 158, 11, 0.3);
        background-color: rgba(245, 158, 11, 0.1);
        color: #fcd34d;
    }
    .cal-cell-full {
        border-color: #fecdd3;
        background-color: #fff1f2;
        color: #9f1239;
    }
    .dark .cal-cell-full {
        border-color: rgba(244, 63, 94, 0.3);
        background-color: rgba(244, 63, 94, 0.1);
        color: #fda4af;
    }
    .cal-cell-blocked {
        border-color: #e5e7eb;
        background-color: #f3f4f6;
        color: #9ca3af;
    }
    .dark .cal-cell-blocked {
        border-color: #374151;
        background-color: #1f2937;
        color: #6b7280;
    }
    .cal-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(4px);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }
    .cal-modal-box {
        background-color: #ffffff;
        border-radius: 1.25rem;
        max-width: 560px;
        width: 100%;
        padding: 1.5rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.15);
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }
    .dark .cal-modal-box {
        background-color: #111827;
        border: 1px solid #1f2937;
    }
</style>
