{{--
    Atap tumpang + mustaka bulan sabit.

    LEBAR ATAP vs KOTAK -- dihitung, bukan dikira:
    viewBox 480 dengan atap x=40..440 (400 unit) => atap = 83.3% lebar viewBox.
    Lebar atap terhadap kotak = 0.833 x (persen render).
      w-[122%] -> 102% kotak   (dipakai di HP)
      w-[142%] -> 118% kotak   (dipakai sm ke atas)
    Versi lama viewBox 540 menyisakan 130 unit ruang kosong di kiri-kanan,
    sehingga pada w-[132%] atapnya justru cuma 98% -- LEBIH SEMPIT dari kotak.
    Merapatkan viewBox lebih baik daripada memperbesar render: lebar SVG
    (dan overflow-nya) tidak ikut membengkak.

    Kenapa responsif: ujung tritisan kiri ada di x=21. Di layar 375px,
    w-[132%] menaruhnya di x=-14 (terpotong tepi layar); w-[122%] di x=+1
    (aman). Di sm ke atas ruangnya cukup untuk 142%.

    z-10: atap harus berada di LAYER PALING DEPAN supaya tritisannya
    menaungi badan gerbang, bukan tertimpa olehnya.

    -mt-2 di HP: menaikkan atap sedikit sekaligus memangkas jejak vertikal,
    supaya di layar pendek gerbang tidak perlu di-scroll. Di sm ke atas
    ruangnya lega, jadi tidak perlu ditarik.
--}}
<div class="gate-roof relative z-10 -ml-[11%] -mt-2 w-[122%] sm:-ml-[21%] sm:mt-0 sm:w-[142%]">
    <svg viewBox="0 0 480 196" class="block w-full" aria-hidden="true" focusable="false">
        <defs>
            {{-- Terang di kiri-atas, gelap di kanan-bawah: memberi kesan
                 volume tanpa perlu bayangan terpisah. --}}
            <linearGradient id="roofFace" x1="0" y1="0" x2="0.75" y2="1">
                <stop offset="0%" stop-color="var(--gold-bright)" />
                <stop offset="45%" stop-color="var(--gold)" />
                <stop offset="100%" stop-color="var(--gold-deep)" />
            </linearGradient>

            {{--
                Sabit dibentuk lewat MASK: lingkaran putih (tampil) dilubangi
                lingkaran hitam yang digeser ke kanan.

                Sengaja TIDAK memakai dua perintah busur (A) seperti percobaan
                sebelumnya: di sana radius busur dalam lebih pendek dari separuh
                jarak ujungnya, sehingga SVG otomatis menskalakan radiusnya --
                kedua busur jadi setengah lingkaran identik yang saling
                meniadakan, luasnya nol, dan sabitnya TIDAK PERNAH tergambar.
                Mask tidak bergantung pada aritmetika busur, jadi tidak bisa
                gagal diam-diam seperti itu.
            --}}
            <mask id="crescentMask" maskUnits="userSpaceOnUse" x="208" y="0" width="64" height="56">
                <rect x="208" y="0" width="64" height="56" fill="black" />
                <circle cx="235" cy="24" r="18" fill="white" />
                <circle cx="245" cy="24" r="15" fill="black" />
            </mask>
        </defs>

        {{-- ---- Mustaka: bulan sabit & bintang ---- --}}
        <g class="mustaka-glow">
            <circle cx="235" cy="24" r="18" fill="var(--gold-bright)" mask="url(#crescentMask)" />

            {{-- Bintang duduk di rongga sabit (sabit membuka ke kanan) --}}
            <polygon points="253,17 254.6,21.9 259.8,22 255.6,25.1 257.1,30 253,27 248.9,30 250.4,25.1 246.2,22 251.4,21.9"
                     fill="var(--gold-bright)" />
        </g>

        <circle cx="240" cy="48" r="4.5" fill="var(--gold-bright)" />
        <path d="M240 50 V68" stroke="var(--gold)" stroke-width="3.5" stroke-linecap="round" fill="none" />

        {{-- ---- Atap tumpang 3 tingkat ---- --}}
        <g fill="url(#roofFace)">
            <polygon points="240,70 304,104 176,104" />
            <polygon points="174,108 306,108 362,148 118,148" />
            <polygon points="116,152 364,152 440,192 40,192" />
        </g>

        {{-- Pita tritisan tiap tingkat --}}
        <g stroke="var(--gold-deep)" stroke-width="1.4" opacity="0.45">
            <line x1="176" y1="104" x2="304" y2="104" />
            <line x1="118" y1="148" x2="362" y2="148" />
            <line x1="40" y1="192" x2="440" y2="192" />
        </g>

        {{-- Ujung tritisan menekuk ke atas -- ciri atap Jawa --}}
        <g stroke="var(--gold)" stroke-width="3.5" fill="none" stroke-linecap="round">
            <path d="M40 192 Q26 189 21 180" />
            <path d="M440 192 Q454 189 459 180" />
        </g>
    </svg>
</div>
