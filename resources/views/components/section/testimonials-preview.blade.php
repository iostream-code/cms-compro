@php
    $testimonials = \App\Models\Testimonial::query()
        ->where('is_published', true)
        ->orderBy('order')
        ->limit($content['limit'] ?? 3)
        ->get();
@endphp

@if ($testimonials->isNotEmpty())
    <section class="section-testimonials-preview px-6 py-16" data-section-type="testimonials-preview">
        <div class="mx-auto max-w-6xl">
            <div data-reveal>
                <x-include.section-heading
                    :eyebrow="$content['eyebrow'] ?? 'Testimoni'"
                    :title="$content['title'] ?? null" />
            </div>

            <div class="grid gap-5 md:grid-cols-3">
                @foreach ($testimonials as $index => $testimonial)
                    <div class="h-full" data-reveal style="--reveal-delay: {{ $index * 100 }}ms">
                        <x-include.testimonial-card :testimonial="$testimonial" />
                    </div>
                @endforeach
            </div>

            <div class="mt-10 text-center" data-reveal>
                <a href="{{ route('testimonials') }}"
                   class="group inline-flex items-center gap-2 rounded-md border-2 border-[var(--brand)] px-7 py-2.5 text-sm font-semibold text-[var(--brand)] transition hover:bg-[var(--brand)] hover:text-white">
                    Semua Testimoni
                    <i class="bx bx-right-arrow-alt text-lg transition-transform group-hover:translate-x-1" aria-hidden="true"></i>
                </a>
            </div>
        </div>
    </section>
@endif
