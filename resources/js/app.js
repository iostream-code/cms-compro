/**
 * Bundle untuk halaman CMS (admin).
 *
 * Alpine SENGAJA tidak di-import di sini -- @livewireScripts di
 * layouts/app.blade.php sudah membawa Alpine beserta plugin-nya
 * (collapse, focus, dll). Meng-import Alpine lagi di sini akan bikin
 * dua instance dalam satu halaman dan saling bentrok.
 *
 * Kebutuhan halaman visitor (Alpine, Swiper, animasi) ada di public.js.
 */

import Sortable from 'sortablejs';
window.Sortable = Sortable;
