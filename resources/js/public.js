/**
 * Bundle untuk halaman VISITOR (public).
 *
 * Dipisah dari app.js (CMS) dengan sengaja:
 *  - Halaman publik tidak memuat @livewireScripts, jadi Alpine TIDAK ikut
 *    ter-load dari Livewire. Tanpa file ini, semua x-data/x-show di header
 *    (mis. menu mobile) mati total.
 *  - Sebaliknya, CMS tidak butuh Swiper, dan publik tidak butuh SortableJS.
 *    Memisah entry point bikin kedua bundle lebih kecil.
 *
 * JANGAN import Alpine di app.js -- Livewire sudah membawa Alpine-nya
 * sendiri di sana, dua instance Alpine dalam satu halaman akan bentrok.
 */

import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';

import Swiper from 'swiper';
import { Navigation, Pagination, Autoplay } from 'swiper/modules';
import 'swiper/css/bundle';

Alpine.plugin(collapse);
window.Alpine = Alpine;
Alpine.start();

// Dipakai oleh x-section.hero-slider.
window.Swiper = Swiper;
window.SwiperModules = { Navigation, Pagination, Autoplay };

const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

/**
 * Scroll reveal: tampilkan elemen [data-reveal] saat masuk viewport.
 *
 * Sengaja bukan library: efeknya sesederhana toggle satu class, dan
 * IntersectionObserver sudah didukung semua browser target. Elemen yang
 * sudah tampil langsung di-unobserve, jadi tidak ada kerja sia-sia saat
 * user scroll balik ke atas.
 */
function initScrollReveal() {
    const targets = document.querySelectorAll('[data-reveal]');
    if (!targets.length) return;

    // Kalau user minta animasi dikurangi, langsung tampilkan semuanya --
    // jangan sampai kontennya malah tidak pernah muncul.
    if (prefersReducedMotion || !('IntersectionObserver' in window)) {
        targets.forEach((el) => el.classList.add('is-revealed'));
        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                entry.target.classList.add('is-revealed');
                observer.unobserve(entry.target);
            });
        },
        { threshold: 0.12, rootMargin: '0px 0px -40px 0px' }
    );

    targets.forEach((el) => observer.observe(el));
}

/**
 * Baca angka yang ditulis admin dengan konvensi Indonesia, di mana titik
 * adalah pemisah RIBUAN dan koma pemisah DESIMAL -- kebalikan dari JS.
 *
 * Titik ambigu ("4.9" bisa berarti 4,9 atau 49 ribu yang salah ketik), jadi
 * dibedakan lewat pola: titik yang diikuti tepat 3 digit dianggap pemisah
 * ribuan ("12.000"), selain itu dianggap desimal ("4.9").
 *
 * @returns {{target: number, hasDecimal: boolean}}
 */
function parseLocalizedNumber(raw) {
    const cleaned = String(raw).trim();

    // Koma selalu desimal: buang titik (ribuan), koma jadi titik desimal.
    if (cleaned.includes(',')) {
        return {
            target: parseFloat(cleaned.replace(/\./g, '').replace(',', '.').replace(/[^\d.-]/g, '')),
            hasDecimal: true,
        };
    }

    const isThousandsGrouped = /^\d{1,3}(\.\d{3})+$/.test(cleaned.replace(/[^\d.]/g, ''));

    return {
        target: parseFloat(
            isThousandsGrouped ? cleaned.replace(/\./g, '').replace(/[^\d-]/g, '') : cleaned.replace(/[^\d.-]/g, '')
        ),
        hasDecimal: !isThousandsGrouped && cleaned.includes('.'),
    };
}

/**
 * Hitung naik angka statistik saat section-nya kelihatan.
 * Format ribuan Indonesia (12.000) dipertahankan lewat toLocaleString('id-ID').
 */
function initCounters() {
    const counters = document.querySelectorAll('[data-counter]');
    if (!counters.length) return;

    if (prefersReducedMotion || !('IntersectionObserver' in window)) {
        counters.forEach((el) => {
            el.textContent = el.dataset.counter;
        });
        return;
    }

    const run = (el) => {
        const raw = el.dataset.counter;
        const { target, hasDecimal } = parseLocalizedNumber(raw);

        if (Number.isNaN(target)) {
            // Nilai bebas yang bukan angka (mis. "24/7") -- tampilkan apa adanya
            el.textContent = raw;
            return;
        }

        const duration = 1400;
        const start = performance.now();

        const tick = (now) => {
            const progress = Math.min((now - start) / duration, 1);
            // easeOutExpo -- cepat di awal lalu melambat, terasa lebih hidup
            const eased = progress === 1 ? 1 : 1 - Math.pow(2, -10 * progress);
            const value = target * eased;

            el.textContent = hasDecimal
                ? value.toFixed(1).replace('.', ',')
                : Math.round(value).toLocaleString('id-ID');

            if (progress < 1) requestAnimationFrame(tick);
        };

        requestAnimationFrame(tick);
    };

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                run(entry.target);
                observer.unobserve(entry.target);
            });
        },
        { threshold: 0.5 }
    );

    counters.forEach((el) => observer.observe(el));
}

/**
 * Header menyusut saat halaman di-scroll: topbar dilipat supaya viewport
 * lebih lega, nav utama tetap sticky.
 */
function initHeaderScroll() {
    const header = document.querySelector('[data-site-header]');
    if (!header) return;

    const onScroll = () => {
        header.classList.toggle('is-scrolled', window.scrollY > 60);
    };

    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
}

/**
 * Tombol kembali ke atas: muncul setelah user scroll cukup jauh.
 * Ambang 400px dipilih supaya tombol tidak berkedip muncul-hilang di
 * halaman pendek yang cuma discroll sedikit.
 */
function initScrollTop() {
    const btn = document.querySelector('[data-scroll-top]');
    if (!btn) return;

    const onScroll = () => {
        btn.classList.toggle('is-visible', window.scrollY > 400);
    };

    btn.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            // Hormati preferensi user: scroll instan kalau dia minta
            // animasi dikurangi.
            behavior: prefersReducedMotion ? 'auto' : 'smooth',
        });
    });

    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
}

/**
 * Banner identitas pembuat di console browser.
 *
 * Catatan soal isi: yang ditampilkan hanya nama, URL, versi rilis, dan zona
 * waktu browser (dibaca lokal, tidak dikirim ke mana pun). SENGAJA tidak
 * menampilkan nama/IP server internal maupun fingerprint browser pengunjung:
 * yang pertama membocorkan topologi infrastruktur ke siapa pun yang membuka
 * DevTools, yang kedua melacak pengunjung tanpa alasan yang bisa
 * dipertanggungjawabkan. Keduanya tidak menambah nilai sebagai atribusi.
 */
function printBranding() {
    const b = window.__branding;
    if (!b) return;

    // Huruf blok 5 baris. Dirakit dari string biasa (bukan gambar) supaya
    // tetap bisa di-copy dari console dan nol biaya jaringan.
    const art = [
        '█  █  ██  ███  ████ ███  ███  █  █ ███   ██ ',
        '█ █  █  █ █  █ █    █  █  █   ██ █ █  █ █  █',
        '██   █  █ ███  ███  ███   █   █ ██ █  █ █  █',
        '█ █  █  █ █    █    █ █   █   █  █ █  █ █  █',
        '█  █  ██  █    ████ █  █ ███  █  █ ███   ██ ',
    ].join('\n');

    const accent = `color:${b.color};font-weight:bold`;
    const muted = 'color:#9AA5A1';

    console.log(`%c${art}`, `${accent};line-height:1.1`);
    console.log(`%c${b.tagline}`, muted);
    console.log(`%cversi   %c${b.version}`, muted, accent);
    console.log(
        `%czona    %c${Intl.DateTimeFormat().resolvedOptions().timeZone}`,
        muted,
        accent
    );
    console.log(`%cdibuat oleh %c${b.name} %c${b.url}`, muted, accent, muted);
}

document.addEventListener('DOMContentLoaded', () => {
    initScrollReveal();
    initCounters();
    initHeaderScroll();
    initScrollTop();
    printBranding();
});
