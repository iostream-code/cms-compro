@props(['title', 'subtitle' => null])

{{-- Banner judul untuk halaman-halaman dalam (bukan homepage) --}}
<div class="relative overflow-hidden bg-[var(--brand-dark)] px-6 py-16 text-center">

    {{-- Motif bintang delapan (khatim) sebagai latar ornamen --}}
    <x-include.pattern variant="star" class="text-[var(--brand)]" opacity="0.10" />

    <span aria-hidden="true" class="absolute inset-x-0 bottom-0 h-px bg-gradient-to-r from-transparent via-[var(--brand)]/45 to-transparent"></span>

    <div class="relative">
        <h1 class="font-serif text-3xl font-bold text-white md:text-4xl">{{ $title }}</h1>

        {{-- Divider dengan titik di tengah -- detail kecil yang membedakan
             dari garis polos, dipakai konsisten dengan section-heading. --}}
        <div class="mx-auto mt-4 flex items-center justify-center gap-2">
            <span aria-hidden="true" class="h-px w-8 bg-[var(--brand)]/50"></span>
            <span aria-hidden="true" class="h-1.5 w-1.5 rotate-45 bg-[var(--brand)]"></span>
            <span aria-hidden="true" class="h-px w-8 bg-[var(--brand)]/50"></span>
        </div>

        @if ($subtitle)
            <p class="mx-auto mt-4 max-w-2xl text-sm leading-relaxed text-white/70">{{ $subtitle }}</p>
        @endif
    </div>
</div>
