@props(['icon' => 'bx-detail', 'title'])

{{-- Judul blok di halaman detail (paket/artikel) -- lebih kecil dari
     section-heading yang dipakai di homepage. --}}
<div class="mb-4 flex items-center gap-2.5">
    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-[var(--brand)]/10 text-[var(--brand)]">
        <i class="bx {{ $icon }} text-lg" aria-hidden="true"></i>
    </div>

    <h2 class="font-serif text-lg font-bold text-[var(--brand-dark)]">{{ $title }}</h2>

    {{-- Garis tipis mengisi sisa lebar, memberi kesan "divider berjudul" --}}
    <span aria-hidden="true" class="h-px flex-1 bg-gradient-to-r from-[var(--brand)]/25 to-transparent"></span>
</div>
