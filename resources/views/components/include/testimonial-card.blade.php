@props(['testimonial'])

{{-- Dipakai di section testimonials-preview dan halaman /testimoni --}}
<figure class="group relative flex h-full flex-col overflow-hidden rounded-xl border border-black/5 bg-white p-6 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg">

    {{-- Ornamen kutip di sudut, memudar saat hover --}}
    <i class="bx bxs-quote-alt-left pointer-events-none absolute -right-2 -top-2 text-6xl text-[var(--brand)]/8 transition group-hover:text-[var(--brand)]/15"
       aria-hidden="true"></i>

    <div class="relative mb-3 flex gap-0.5 text-[var(--brand)]" aria-label="Rating {{ $testimonial->rating }} dari 5">
        @for ($i = 1; $i <= 5; $i++)
            <i class="bx {{ $i <= $testimonial->rating ? 'bxs-star' : 'bx-star text-black/15' }} text-sm" aria-hidden="true"></i>
        @endfor
    </div>

    <blockquote class="relative flex-1 text-sm leading-relaxed text-[#5B6663]">
        &ldquo;{{ $testimonial->content }}&rdquo;
    </blockquote>

    <figcaption class="relative mt-5 flex items-center gap-3 border-t border-black/5 pt-4">
        @if ($testimonial->jamaah_photo_url)
            <img src="{{ $testimonial->jamaah_photo_url }}" alt="{{ $testimonial->jamaah_name }}" loading="lazy"
                 class="h-10 w-10 rounded-full object-cover ring-2 ring-[var(--brand)]/20">
        @else
            {{-- Inisial sebagai avatar cadangan supaya baris identitas tidak pincang --}}
            <span aria-hidden="true"
                  class="flex h-10 w-10 items-center justify-center rounded-full bg-[var(--brand)]/12 text-sm font-semibold text-[var(--brand)]">
                {{ \Illuminate\Support\Str::substr($testimonial->jamaah_name, 0, 1) }}
            </span>
        @endif

        <div>
            <p class="text-sm font-semibold text-[var(--brand-dark)]">{{ $testimonial->jamaah_name }}</p>
            <p class="text-xs text-[#8B9490]">
                {{ collect([$testimonial->jamaah_city, $testimonial->year])->filter()->implode(' · ') }}
            </p>
        </div>
    </figcaption>
</figure>
