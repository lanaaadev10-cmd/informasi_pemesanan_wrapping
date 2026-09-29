/**
 * app.js — Entry Point Aplikasi Frontend
 *
 * Urutan Inisialisasi:
 * 1. Bootstrap (Axios + CSRF token setup)
 * 2. Alpine.js — reactive UI framework
 * 3. API Client — HTTP wrapper terpusat ke /api/*
 * 4. Utilities — UI toast, formatter, storage, cart
 *
 * Semua modul diekspos ke `window.*` agar bisa diakses
 * dari template Blade dan skrip inline halaman.
 */

import './bootstrap';

// ===================================================
//  1. Alpine.js — Reactive UI
// ===================================================
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

// ===================================================
//  2. API Client — Centralized HTTP to /api/*
// ===================================================
import './api';

// ===================================================
//  3. Utilities — Format, UI, Storage, Cart
//
//  Sumber: resources/js/utils/ & resources/js/components/
//  Alasan ekspos ke window: template Blade dan skrip inline
//  pada halaman individual dapat menggunakannya tanpa bundler.
// ===================================================
import { formatters }    from './utils/formatting';
import { UI }            from './utils/ui';
import { storage }       from './utils/storage';
import { CartComponent } from './components/cart';

window.formatters    = formatters;
window.UI            = UI;
window.storage       = storage;
window.CartComponent = CartComponent;
