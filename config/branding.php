<?php

/**
 * Identitas pembuat aplikasi (bukan identitas tenant).
 *
 * Tampil sebagai banner di console browser pengunjung -- lihat
 * resources/js/public.js. Nilainya dikirim ke JS lewat
 * resources/views/components/layouts/public.blade.php.
 */
return [
    'name' => env('BRANDING_NAME', 'KOPERINDO'),
    'url' => env('BRANDING_URL', 'https://koperindo.id'),
    'tagline' => env('BRANDING_TAGLINE', 'Company Profile & CMS untuk Travel Haji & Umrah'),

    // Warna banner console (oranye khas KOPERINDO).
    'color' => env('BRANDING_COLOR', '#F97316'),

    // Ditampilkan di baris "versi". Diisi dari APP_VERSION kalau ada, kalau
    // tidak jatuh ke '-' -- jangan pakai commit hash otomatis, itu membocorkan
    // detail repo ke publik tanpa manfaat untuk pengunjung.
    'version' => env('APP_VERSION', '1.0.0'),
];
