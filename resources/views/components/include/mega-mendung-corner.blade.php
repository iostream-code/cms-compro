@props(['side' => 'left'])

{{--
    Gugus mega mendung untuk sudut bawah -- aset FOREGROUND, menimpa bagian
    bawah gerbang.

    SILUETNYA MURNI KURVA, tanpa satu pun perintah V/H/L di tepi yang terlihat.
    Versi sebelumnya memakai "V" (garis lurus vertikal) di pangkal tiap lobe,
    dan itulah yang membuat badan hijaunya terlihat kotak. Satu-satunya garis
    lurus di sini adalah alas y=250 -- dan itu tepat di tepi bawah viewport,
    jadi tidak pernah terlihat.

    Ciri mega mendung yang dijaga: (1) gelombang lobe yang TIDAK seragam
    tingginya, dan (2) IKAL SPIRAL di badan awan. Spiralnya dari busur
    setengah lingkaran berselang radius mengecil -- arah putarnya konsisten
    sehingga menggulung ke dalam.

    Cermin diterapkan di <svg>, BUKAN di div luar: transform div luar dipakai
    animasi masuk (translateY); kalau scale-x ditaruh di sana juga, keduanya
    rebutan properti transform dan animasinya ikut membalik.

    pointer-events-none WAJIB: awan melayang di atas gerbang; tanpa ini ia
    memblokir klik ke field form di baliknya.
--}}
<div aria-hidden="true"
     class="mendung-corner pointer-events-none absolute bottom-0 z-20 w-[48%] max-w-[36rem] min-w-[20rem]
            {{ $side === 'left' ? 'left-0' : 'right-0' }}">

    <svg viewBox="0 0 480 250" class="block w-full {{ $side === 'right' ? 'scale-x-[-1]' : '' }}"
         fill="none" focusable="false">
        <defs>
            <linearGradient id="cloudBack{{ $side }}" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0%" stop-color="#0C4739" />
                <stop offset="100%" stop-color="#052C25" />
            </linearGradient>
            <linearGradient id="cloudFront{{ $side }}" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0%" stop-color="#115A48" />
                <stop offset="100%" stop-color="#073A2F" />
            </linearGradient>
        </defs>

        {{-- ===== Awan belakang: lebih besar, lobe tinggi-rendah bergantian ===== --}}
        <path d="M-30 250
                 C-30 208 8 182 50 194
                 C66 150 126 142 152 188
                 C172 136 240 130 266 182
                 C288 146 342 152 358 194
                 C378 170 428 180 444 220
                 C456 238 468 248 480 250
                 Z"
              fill="url(#cloudBack{{ $side }})" stroke="var(--gold)" stroke-width="3" />

        {{-- Kontur dalam: gema siluet, ciri khas batik --}}
        <path d="M-16 250
                 C-16 216 12 196 52 208
                 C68 172 124 166 150 204
                 C170 158 236 154 262 200
                 C284 170 338 176 354 210
                 C374 190 422 200 438 232"
              stroke="var(--gold)" stroke-width="1.6" opacity="0.5" />

        {{-- Ikal spiral di badan awan belakang --}}
        <g stroke="var(--gold)" stroke-width="2.2" opacity="0.9">
            <path d="M88 200 a22 22 0 0 1 44 0 a16.5 16.5 0 0 1 -33 0 a11 11 0 0 1 22 0 a5.5 5.5 0 0 1 -11 0" />
            <path d="M182 192 a24 24 0 0 1 48 0 a18 18 0 0 1 -36 0 a12 12 0 0 1 24 0 a6 6 0 0 1 -12 0" />
            <path d="M292 206 a20 20 0 0 1 40 0 a15 15 0 0 1 -30 0 a10 10 0 0 1 20 0 a5 5 0 0 1 -10 0" />
            <path d="M390 224 a16 16 0 0 1 32 0 a12 12 0 0 1 -24 0 a8 8 0 0 1 16 0 a4 4 0 0 1 -8 0" />
        </g>

        {{-- ===== Awan depan: lebih kecil & lebih terang, menimpa ===== --}}
        <path d="M-10 250
                 C-10 224 30 206 68 218
                 C84 186 140 180 162 216
                 C182 190 232 194 250 224
                 C268 208 306 214 322 244
                 C328 249 336 250 344 250
                 Z"
              fill="url(#cloudFront{{ $side }})" stroke="var(--gold)" stroke-width="3" />

        <path d="M2 250
                 C2 230 34 216 70 228
                 C86 202 138 198 158 228
                 C178 208 226 212 244 236"
              stroke="var(--gold)" stroke-width="1.5" opacity="0.5" />

        <g stroke="var(--gold)" stroke-width="2" opacity="0.9">
            <path d="M92 228 a18 18 0 0 1 36 0 a13.5 13.5 0 0 1 -27 0 a9 9 0 0 1 18 0 a4.5 4.5 0 0 1 -9 0" />
            <path d="M186 232 a17 17 0 0 1 34 0 a12.75 12.75 0 0 1 -25.5 0 a8.5 8.5 0 0 1 17 0 a4.25 4.25 0 0 1 -8.5 0" />
            <path d="M272 242 a13 13 0 0 1 26 0 a9.75 9.75 0 0 1 -19.5 0 a6.5 6.5 0 0 1 13 0" />
        </g>
    </svg>
</div>
