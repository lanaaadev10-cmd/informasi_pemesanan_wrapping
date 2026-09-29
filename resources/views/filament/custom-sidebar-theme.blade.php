<style>
    /* ==========================================================================
       CUSTOM ORANGE THEME FOR FILAMENT SIDEBAR
       ========================================================================== */

    /* 1. Main Sidebar Container */
    aside.fi-sidebar,
    .fi-sidebar,
    .fi-sidebar.fi-main-sidebar,
    .fi-body-has-topbar .fi-sidebar,
    .dark aside.fi-sidebar,
    .dark .fi-sidebar {
        background: linear-gradient(180deg, #FF6B00 0%, #FA5D00 45%, #E64A00 100%) !important;
        border-right: 1px solid rgba(255, 255, 255, 0.15) !important;
        box-shadow: 4px 0 24px -4px rgba(230, 74, 0, 0.22) !important;
    }

    /* Subtle decorative glow at the top edge of sidebar */
    .fi-sidebar::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 120px;
        background: radial-gradient(ellipse at top, rgba(255, 255, 255, 0.18), transparent 70%);
        pointer-events: none;
        z-index: 1;
    }

    /* 2. Sidebar Header (Mobile & Drawer) */
    .fi-sidebar-header-ctn,
    .fi-sidebar-header,
    .fi-body-has-topbar .fi-sidebar-header,
    .dark .fi-sidebar-header {
        background: transparent !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.12) !important;
        box-shadow: none !important;
    }

    .fi-sidebar-header .fi-logo,
    .fi-sidebar-header-logo-ctn a,
    .fi-sidebar-header-logo-ctn span,
    .fi-sidebar-header-logo-ctn h1,
    .fi-sidebar-header-logo-ctn div {
        color: #ffffff !important;
        font-weight: 800 !important;
        letter-spacing: 0.02em !important;
    }

    .fi-sidebar-header button,
    .fi-sidebar-close-collapse-sidebar-btn,
    .fi-sidebar-open-collapse-sidebar-btn {
        color: rgba(255, 255, 255, 0.9) !important;
        border-radius: 0.5rem !important;
    }

    .fi-sidebar-header button:hover {
        background-color: rgba(255, 255, 255, 0.2) !important;
        color: #ffffff !important;
    }

    /* 3. Navigation Scrollbar & Spacing */
    .fi-sidebar-nav {
        scrollbar-width: thin;
        scrollbar-color: rgba(255, 255, 255, 0.25) transparent;
        position: relative;
        z-index: 2;
    }

    .fi-sidebar-nav::-webkit-scrollbar {
        width: 5px;
    }

    .fi-sidebar-nav::-webkit-scrollbar-track {
        background: transparent;
    }

    .fi-sidebar-nav::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.25);
        border-radius: 9999px;
    }

    .fi-sidebar-nav::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.45);
    }

    /* 4. Navigation Group Headers */
    .fi-sidebar .fi-sidebar-group-btn {
        cursor: pointer;
    }

    .fi-sidebar .fi-sidebar-group-label {
        color: rgba(255, 255, 255, 0.72) !important;
        font-size: 0.68rem !important;
        font-weight: 700 !important;
        letter-spacing: 0.08em !important;
        text-transform: uppercase !important;
    }

    .fi-sidebar .fi-sidebar-group-collapse-btn {
        color: rgba(255, 255, 255, 0.7) !important;
    }

    .fi-sidebar .fi-sidebar-group-collapse-btn:hover {
        color: #ffffff !important;
        background-color: rgba(255, 255, 255, 0.15) !important;
    }

    /* 5. Navigation Items (Normal / Inactive) */
    .fi-sidebar .fi-sidebar-item-btn {
        color: rgba(255, 255, 255, 0.9) !important;
        border-radius: 0.75rem !important;
        transition: all 0.18s cubic-bezier(0.4, 0, 0.2, 1) !important;
        padding-top: 0.5rem !important;
        padding-bottom: 0.5rem !important;
    }

    .fi-sidebar .fi-sidebar-item-label {
        color: inherit !important;
        font-weight: 500 !important;
        font-size: 0.85rem !important;
    }

    .fi-sidebar .fi-sidebar-item-btn .fi-sidebar-item-icon,
    .fi-sidebar .fi-sidebar-item-btn svg.fi-icon,
    .fi-sidebar .fi-sidebar-item-btn > svg {
        color: rgba(255, 255, 255, 0.85) !important;
        transition: color 0.18s ease-in-out !important;
    }

    /* Inactive Item Hover */
    .fi-sidebar .fi-sidebar-item:not(.fi-active) > .fi-sidebar-item-btn:hover,
    .fi-sidebar .fi-sidebar-item:not(.fi-active) > .fi-sidebar-item-btn:focus-visible {
        background-color: rgba(255, 255, 255, 0.16) !important;
        color: #ffffff !important;
        transform: translateX(3px);
    }

    .fi-sidebar .fi-sidebar-item:not(.fi-active) > .fi-sidebar-item-btn:hover .fi-sidebar-item-icon,
    .fi-sidebar .fi-sidebar-item:not(.fi-active) > .fi-sidebar-item-btn:hover svg.fi-icon,
    .fi-sidebar .fi-sidebar-item:not(.fi-active) > .fi-sidebar-item-btn:hover > svg {
        color: #ffffff !important;
    }

    /* 6. Active Item (Standout White Pill with Orange Accent) */
    .fi-sidebar .fi-sidebar-item.fi-active > .fi-sidebar-item-btn,
    .dark .fi-sidebar .fi-sidebar-item.fi-active > .fi-sidebar-item-btn {
        background-color: #ffffff !important;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.14), 0 1px 3px rgba(0, 0, 0, 0.08) !important;
        border-radius: 0.75rem !important;
        transform: none !important;
    }

    .fi-sidebar .fi-sidebar-item.fi-active > .fi-sidebar-item-btn .fi-sidebar-item-label,
    .dark .fi-sidebar .fi-sidebar-item.fi-active > .fi-sidebar-item-btn .fi-sidebar-item-label {
        color: #E64A00 !important;
        font-weight: 700 !important;
    }

    .fi-sidebar .fi-sidebar-item.fi-active > .fi-sidebar-item-btn .fi-sidebar-item-icon,
    .fi-sidebar .fi-sidebar-item.fi-active > .fi-sidebar-item-btn svg.fi-icon,
    .fi-sidebar .fi-sidebar-item.fi-active > .fi-sidebar-item-btn > svg,
    .dark .fi-sidebar .fi-sidebar-item.fi-active > .fi-sidebar-item-btn .fi-sidebar-item-icon,
    .dark .fi-sidebar .fi-sidebar-item.fi-active > .fi-sidebar-item-btn svg.fi-icon,
    .dark .fi-sidebar .fi-sidebar-item.fi-active > .fi-sidebar-item-btn > svg {
        color: #E64A00 !important;
    }

    /* Active grouped item border & dot */
    .fi-sidebar .fi-sidebar-item.fi-active > .fi-sidebar-item-btn .fi-sidebar-item-grouped-border-part {
        background-color: #E64A00 !important;
    }

    /* 7. Sub-item Tree Lines & Indent */
    .fi-sidebar .fi-sidebar-item-grouped-border-part-not-first,
    .fi-sidebar .fi-sidebar-item-grouped-border-part-not-last {
        background-color: rgba(255, 255, 255, 0.28) !important;
    }

    .fi-sidebar .fi-sidebar-item-grouped-border-part {
        background-color: rgba(255, 255, 255, 0.65) !important;
    }

    /* 8. Badges */
    .fi-sidebar .fi-sidebar-item:not(.fi-active) .fi-badge {
        background-color: rgba(255, 255, 255, 0.22) !important;
        color: #ffffff !important;
        border: 1px solid rgba(255, 255, 255, 0.35) !important;
    }

    .fi-sidebar .fi-sidebar-item.fi-active .fi-badge {
        background-color: #E64A00 !important;
        color: #ffffff !important;
    }

    /* 9. Sidebar Footer & Notifications Button */
    .fi-sidebar .fi-sidebar-footer {
        border-top: 1px solid rgba(255, 255, 255, 0.15) !important;
        padding-top: 0.75rem !important;
    }

    .fi-sidebar .fi-sidebar-database-notifications-btn {
        color: rgba(255, 255, 255, 0.9) !important;
        background-color: rgba(255, 255, 255, 0.1) !important;
        border-radius: 0.75rem !important;
    }

    .fi-sidebar .fi-sidebar-database-notifications-btn:hover {
        background-color: rgba(255, 255, 255, 0.2) !important;
        color: #ffffff !important;
    }

    .fi-sidebar .fi-sidebar-database-notifications-btn .fi-icon {
        color: rgba(255, 255, 255, 0.85) !important;
    }
</style>
