@props(['variant' => 'star', 'class' => '', 'opacity' => '0.07'])

@php
    // Tiap instance butuh id unik: kalau dua <pattern> di satu halaman punya id
    // sama, semua rect akan memakai definisi yang pertama saja.
    $id = 'pat-' . \Illuminate\Support\Str::random(6);
@endphp

<svg aria-hidden="true" focusable="false"
     {{ $attributes->merge(['class' => 'pointer-events-none absolute inset-0 h-full w-full ' . $class]) }}
     style="opacity: {{ $opacity }}">
    <defs>
        @if ($variant === 'star')
            {{--
                Khatim / Rub el Hizb -- bintang delapan dari dua persegi yang
                saling tumpang tindih 45°. Motif ini dipakai luas di ornamen
                masjid, dari Timur Tengah sampai Nusantara (mis. ukiran Demak),
                jadi terbaca islami tanpa terikat satu daerah tertentu.
            --}}
            <pattern id="{{ $id }}" width="72" height="72" patternUnits="userSpaceOnUse">
                <g fill="none" stroke="currentColor" stroke-width="1">
                    <rect x="18" y="18" width="36" height="36" />
                    <rect x="18" y="18" width="36" height="36" transform="rotate(45 36 36)" />
                    <circle cx="36" cy="36" r="3" />
                </g>
            </pattern>
        @elseif ($variant === 'lattice')
            {{-- Jali/kisi-kisi: anyaman geometris seperti kerawang masjid --}}
            <pattern id="{{ $id }}" width="48" height="48" patternUnits="userSpaceOnUse">
                <g fill="none" stroke="currentColor" stroke-width="1">
                    <path d="M0 24 L24 0 L48 24 L24 48 Z" />
                    <path d="M24 0 L24 48 M0 24 L48 24" />
                </g>
            </pattern>
        @else
            {{-- Arch/mihrab: deretan lengkung runcing khas serambi masjid --}}
            <pattern id="{{ $id }}" width="60" height="70" patternUnits="userSpaceOnUse">
                <path d="M30 8 Q52 30 52 62 L8 62 Q8 30 30 8 Z"
                      fill="none" stroke="currentColor" stroke-width="1" />
            </pattern>
        @endif
    </defs>

    <rect width="100%" height="100%" fill="url(#{{ $id }})" />
</svg>
