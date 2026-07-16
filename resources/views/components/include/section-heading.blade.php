@props(['eyebrow' => null, 'title' => null, 'subtitle' => null, 'align' => 'center'])

@if ($eyebrow || $title || $subtitle)
    <div class="mb-11 {{ $align === 'center' ? 'text-center' : '' }}">
        @if ($eyebrow)
            <p class="mb-2 flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.2em] text-[var(--brand)]
                      {{ $align === 'center' ? 'justify-center' : '' }}">
                <span aria-hidden="true" class="h-1.5 w-1.5 rotate-45 bg-[var(--brand)]"></span>
                {{ $eyebrow }}
            </p>
        @endif

        @if ($title)
            <h2 class="font-serif text-2xl font-bold text-[var(--brand-dark)] md:text-3xl">{{ $title }}</h2>

            {{-- Divider bertitik -- konsisten dengan page-hero --}}
            <div class="mt-3 flex items-center gap-2 {{ $align === 'center' ? 'justify-center' : '' }}">
                <span aria-hidden="true" class="h-[3px] w-10 rounded-full bg-[var(--brand)]"></span>
                <span aria-hidden="true" class="h-1.5 w-1.5 rotate-45 bg-[var(--brand)]/50"></span>
            </div>
        @endif

        @if ($subtitle)
            <p class="mt-4 text-sm leading-relaxed text-[#5B6663] {{ $align === 'center' ? 'mx-auto max-w-2xl' : '' }}">
                {{ $subtitle }}
            </p>
        @endif
    </div>
@endif
