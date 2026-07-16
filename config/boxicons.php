<?php

/**
 * Daftar ikon yang ditawarkan di picker CMS (field bertipe `icon`).
 *
 * Sengaja dikurasi, bukan seluruh ~1500 ikon Boxicons: admin travel tidak
 * perlu memilih dari ratusan ikon yang tidak relevan, dan daftar sependek
 * ini muat dirender sekaligus tanpa perlu paginasi/lazy load.
 *
 * Kalau ada ikon yang dibutuhkan tapi belum terdaftar, admin tetap bisa
 * mengetik nama kelasnya manual di picker (mis. "bx-rocket") -- lihat
 * resources/views/livewire/cms/partials/field-input.blade.php.
 *
 * PENTING: nama ikon di sini WAJIB benar-benar ada di Boxicons -- nama yang
 * salah tidak melempar error, cuma merender kotak kosong yang mudah lolos
 * dari review. Beberapa ikon hanya tersedia dalam varian solid (`bxs-`),
 * mis. pesawat: `bxs-plane-take-off` ADA, `bx-plane-take-off` TIDAK.
 * Verifikasi dengan mencocokkan ke node_modules/boxicons/css/boxicons.min.css
 * sebelum menambah entri baru.
 *
 * Nama lengkap ikon bisa dicari di https://boxicons.com
 */
return [
    'Perjalanan' => [
        'bxs-plane-take-off' => 'Pesawat berangkat',
        'bxs-plane-land' => 'Pesawat mendarat',
        'bxs-plane-alt' => 'Pesawat',
        'bx-bus' => 'Bus',
        'bx-car' => 'Mobil',
        'bx-train' => 'Kereta',
        'bx-map' => 'Peta',
        'bx-map-alt' => 'Peta alternatif',
        'bx-map-pin' => 'Penanda lokasi',
        'bx-compass' => 'Kompas',
        'bx-world' => 'Globe',
        'bx-briefcase-alt-2' => 'Koper / bagasi',
        'bx-trip' => 'Perjalanan',
    ],

    'Akomodasi' => [
        'bx-buildings' => 'Hotel / gedung',
        'bx-building-house' => 'Penginapan',
        'bx-bed' => 'Kamar / tempat tidur',
        'bx-restaurant' => 'Konsumsi',
        'bx-dish' => 'Hidangan',
        'bx-coffee' => 'Kopi',
        'bx-wifi' => 'WiFi',
    ],

    'Ibadah' => [
        'bx-moon' => 'Bulan sabit',
        'bx-star' => 'Bintang',
        'bx-book-open' => 'Kitab terbuka',
        'bx-book' => 'Buku',
        'bx-heart' => 'Hati',
        'bx-donate-heart' => 'Sedekah',
        'bxs-hand' => 'Tangan / doa',
    ],

    'Layanan & Kepercayaan' => [
        'bx-check-shield' => 'Terjamin',
        'bx-badge-check' => 'Terverifikasi',
        'bx-shield' => 'Perlindungan',
        'bx-certification' => 'Sertifikat',
        'bx-award' => 'Penghargaan',
        'bx-medal' => 'Medali',
        'bx-support' => 'Dukungan',
        'bx-headphone' => 'Layanan pelanggan',
        'bx-group' => 'Grup / rombongan',
        'bx-user-voice' => 'Pembimbing',
        'bx-user-check' => 'Jamaah terdaftar',
        'bx-time-five' => 'Jam / durasi',
        'bx-calendar' => 'Kalender',
    ],

    'Administrasi' => [
        'bx-id-card' => 'Kartu identitas / visa / paspor',
        'bx-file' => 'Dokumen',
        'bx-task' => 'Formulir',
        'bx-list-check' => 'Daftar periksa',
        'bx-clipboard' => 'Papan klip',
        'bx-receipt' => 'Kuitansi',
        'bx-wallet' => 'Dompet / biaya',
        'bx-money' => 'Uang',
        'bx-credit-card' => 'Kartu kredit',
        'bx-purchase-tag' => 'Label harga',
    ],

    'Umum' => [
        'bx-check' => 'Centang',
        'bx-check-circle' => 'Centang lingkaran',
        'bx-camera' => 'Kamera',
        'bx-image' => 'Gambar',
        'bx-phone' => 'Telepon',
        'bx-envelope' => 'Email',
        'bx-chat' => 'Obrolan',
        'bx-package' => 'Paket',
        'bx-gift' => 'Hadiah',
        'bx-info-circle' => 'Informasi',
        'bx-news' => 'Berita',
        'bx-home' => 'Beranda',
    ],
];
