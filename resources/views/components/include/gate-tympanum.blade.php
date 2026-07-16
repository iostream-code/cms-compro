{{--
    Tympanum: bidang emas berkerawang di antara atap dan bukaan gerbang,
    dengan BUKAAN berbentuk lengkung bawang (ogee) di tengahnya.

    Bukaannya TRANSPARAN (bukan diisi warna) -- komponen ini ditaruh sebagai
    anak pertama panel hijau, jadi warna panel yang terlihat lewat bukaan,
    dan konten form bisa ditarik naik ke dalam bukaan itu (lihat login.blade).

    Sisi emasnya menyentuh tepi svg di pojok bawah, menyambung ke pilar emas
    milik pembungkus (px-[5%]) -- jadi emasnya kontinu dari atap ke kaki.
--}}
<svg viewBox="0 0 400 190" class="pointer-events-none block w-full" aria-hidden="true" focusable="false">
    <defs>
        <linearGradient id="tympGold" x1="0" y1="0" x2="0.6" y2="1">
            <stop offset="0%" stop-color="var(--gold-bright)" />
            <stop offset="55%" stop-color="var(--gold)" />
            <stop offset="100%" stop-color="var(--gold-deep)" />
        </linearGradient>

        {{-- Kerawang: kisi belah ketupat + lingkaran, seperti krawangan masjid --}}
        <pattern id="tympKerawang" width="26" height="26" patternUnits="userSpaceOnUse">
            <g fill="none" stroke="#5E4210" stroke-width="1.1" opacity="0.45">
                <path d="M13 1 L25 13 L13 25 L1 13 Z" />
                <circle cx="13" cy="13" r="3.5" />
            </g>
        </pattern>

        {{--
            Daerah emas = rect DIKURANGI bukaan lengkung bawang. Bukaan inilah
            yang membuat tepi ATAS bidang hijau melengkung mengikuti gerbang,
            bukan terpotong lurus.

            Dua hal yang WAJIB benar di sini, dan dua-duanya pernah salah:

            1. Pakai `clip-rule`, BUKAN `fill-rule`. Di dalam <clipPath>,
               fill-rule tidak berlaku sama sekali -- diabaikan diam-diam,
               lalu jatuh ke nonzero.

            2. Subpath lubang digambar BERLAWANAN arah dengan rect-nya
               (rect kiri->kanan, lubang kanan->kiri). Dengan arah yang sama,
               aturan nonzero menghitung area lubang = 2 (bukan 0), sehingga
               lubangnya tidak terpotong dan emas menutupi seluruh kotak.

            Dengan arah berlawanan + clip-rule evenodd, lubangnya benar di
            kedua aturan -- tidak bergantung pada satu asumsi saja.

            Bukaannya melebar sampai x=0..400 tepat di y=190 (tepi bawah svg),
            jadi hijau di dalam lengkung menyatu mulus dengan panel di bawahnya
            tanpa garis batas.
        --}}
        <clipPath id="tympArea">
            <path clip-rule="evenodd"
                  d="M0 0 H400 V190 H0 Z
                     M400 190 C400 118 304 112 252 86 C218 68 206 44 200 20 C194 44 182 68 148 86 C96 112 0 118 0 190 Z" />
        </clipPath>
    </defs>

    <g clip-path="url(#tympArea)">
        <rect width="400" height="190" fill="url(#tympGold)" />
        <rect width="400" height="190" fill="url(#tympKerawang)" />
    </g>

    {{-- Rim bukaan: garis emas terang tebal + bayangan tipis di dalamnya,
         memberi kesan tepian berprofil, bukan sekadar garis. --}}
    <path d="M0 190 C0 118 96 112 148 86 C182 68 194 44 200 20 C206 44 218 68 252 86 C304 112 400 118 400 190"
          fill="none" stroke="var(--gold-bright)" stroke-width="4" />
    <path d="M8 190 C8 124 102 118 152 92 C186 74 196 50 200 30 C204 50 214 74 248 92 C298 118 392 124 392 190"
          fill="none" stroke="#052823" stroke-width="1.5" opacity="0.5" />
</svg>
