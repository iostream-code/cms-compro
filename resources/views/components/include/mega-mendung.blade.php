{{--
    Mega mendung -- motif awan khas batik Cirebon: kontur awan berlapis-lapis
    yang saling bersarang. Dipakai sebagai latar bergerak.

    Tiap lapis digandakan lalu digeser -50%: itu satu-satunya cara membuat
    animasi bergulir yang menyambung mulus tanpa lompatan di titik ulang.
    Kecepatan tiap lapis dibedakan supaya terasa berkedalaman (parallax),
    bukan satu bidang datar yang bergeser.

    Semua animasinya otomatis mati kalau user minta gerakan dikurangi --
    lihat blok prefers-reduced-motion di app.css.
--}}
@php
    // Tiap lapis: [kelas kecepatan, posisi vertikal, tinggi, opacity, skala]
    $layers = [
        ['mendung-slow', 'top-[6%]', 'h-40', '0.14', 'scale-100'],
        ['mendung-mid', 'top-[26%]', 'h-32', '0.10', 'scale-90'],
        ['mendung-fast', 'bottom-[8%]', 'h-36', '0.08', 'scale-110'],
    ];
@endphp

<div aria-hidden="true" class="pointer-events-none absolute inset-0 overflow-hidden">
    @foreach ($layers as [$speed, $pos, $height, $opacity, $scale])
        <div class="{{ $speed }} absolute {{ $pos }} left-0 flex w-[200%] {{ $height }} {{ $scale }}"
             style="opacity: {{ $opacity }}">

            {{-- Dua salinan identik bersebelahan -> gulirannya menyambung --}}
            @for ($i = 0; $i < 2; $i++)
                <svg viewBox="0 0 600 100" class="h-full w-1/2 shrink-0 text-[var(--brand)]"
                     fill="none" preserveAspectRatio="none" focusable="false">
                    <g stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                        {{-- Satu gugus awan = beberapa kontur bersarang, ciri
                             utama mega mendung. --}}
                        <path d="M10 92 Q10 56 52 56 Q52 20 96 20 Q140 20 140 62" />
                        <path d="M28 92 Q28 68 62 68 Q62 36 96 36 Q124 36 124 66" />
                        <path d="M46 92 Q46 80 74 80 Q74 52 96 52 Q110 52 110 70" />

                        <path d="M210 92 Q210 50 258 50 Q258 14 306 14 Q356 14 356 60" />
                        <path d="M230 92 Q230 64 270 64 Q270 32 306 32 Q338 32 338 64" />
                        <path d="M250 92 Q250 78 282 78 Q282 48 306 48 Q322 48 322 68" />

                        <path d="M410 92 Q410 58 450 58 Q450 24 492 24 Q534 24 534 64" />
                        <path d="M428 92 Q428 70 460 70 Q460 40 492 40 Q518 40 518 68" />
                        <path d="M446 92 Q446 82 472 82 Q472 56 492 56 Q506 56 506 72" />
                    </g>
                </svg>
            @endfor
        </div>
    @endforeach
</div>
