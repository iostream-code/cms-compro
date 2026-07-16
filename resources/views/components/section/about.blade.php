@if (!empty($content['title']) || !empty($content['content']))
    <section class="section-about px-6 py-16" data-section-type="about">
        <div class="mx-auto grid max-w-6xl items-center gap-12 md:grid-cols-2">

            @if (!empty($content['image_url']))
                <div class="relative" data-reveal>
                    <img src="{{ $content['image_url'] }}" alt="{{ $content['title'] ?? '' }}" loading="lazy"
                         class="relative z-10 aspect-4/3 w-full rounded-xl object-cover shadow-xl">

                    {{-- Bingkai garis brand yang menyembul di belakang foto --}}
                    <span aria-hidden="true"
                          class="absolute -bottom-4 -left-4 z-0 h-full w-full rounded-xl border-2 border-[var(--brand)]/40"></span>
                </div>
            @endif

            <div data-reveal style="--reveal-delay: 120ms">
                <x-include.section-heading
                    :eyebrow="$content['eyebrow'] ?? 'Tentang Kami'"
                    :title="$content['title'] ?? null"
                    align="left" />

                @if (!empty($content['content']))
                    <div class="space-y-3 text-sm leading-relaxed text-[#5B6663]">
                        {!! nl2br(e($content['content'])) !!}
                    </div>
                @endif

                @if (!empty($content['cta_link']))
                    <a href="{{ $content['cta_link'] }}"
                       class="group mt-7 inline-flex items-center gap-2 rounded-md bg-[var(--brand)] px-7 py-3 text-sm font-semibold text-white transition hover:brightness-110">
                        {{ $content['cta_text'] ?? 'Selengkapnya' }}
                        <i class="bx bx-right-arrow-alt text-lg transition-transform group-hover:translate-x-1" aria-hidden="true"></i>
                    </a>
                @endif
            </div>
        </div>
    </section>
@endif
