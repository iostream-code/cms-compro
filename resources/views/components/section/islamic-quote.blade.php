@if (!empty($content['quote_text']))
    <section class="section-islamic-quote relative overflow-hidden bg-[#FAF8F4] px-6 py-20" data-section-type="islamic-quote">

        <x-include.pattern variant="star" class="text-[var(--brand)]" opacity="0.06" />

        <div class="relative mx-auto max-w-3xl" data-reveal>

            {{-- Bingkai lengkung mihrab: ornamen paling khas serambi masjid,
                 dipakai sebagai wadah kutipan supaya terasa sakral, bukan
                 sekadar blockquote biasa. --}}
            <div class="relative rounded-t-[10rem] rounded-b-2xl border border-[var(--brand)]/25 bg-white/60 px-8 py-14 text-center backdrop-blur-sm md:px-16">

                {{-- Rub el Hizb -- lambang pembagian juz Al-Qur'an --}}
                <div class="mx-auto mb-6 flex h-11 w-11 items-center justify-center">
                    <svg viewBox="0 0 40 40" class="h-full w-full text-[var(--brand)]" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                        <rect x="9" y="9" width="22" height="22" />
                        <rect x="9" y="9" width="22" height="22" transform="rotate(45 20 20)" />
                    </svg>
                </div>

                @if (!empty($content['arabic_text']))
                    <p dir="rtl" lang="ar" class="mb-6 font-serif text-2xl leading-[2.2] text-[var(--brand-dark)] md:text-3xl">
                        {{ $content['arabic_text'] }}
                    </p>
                @endif

                <blockquote class="font-serif text-lg italic leading-relaxed text-[var(--brand-dark)] md:text-xl">
                    {{ $content['quote_text'] }}
                </blockquote>

                @if (!empty($content['source']))
                    <div class="mt-7 flex items-center justify-center gap-3">
                        <span aria-hidden="true" class="h-px w-10 bg-[var(--brand)]/40"></span>
                        <cite class="text-xs font-semibold uppercase not-italic tracking-[0.2em] text-[var(--brand)]">
                            {{ $content['source'] }}
                        </cite>
                        <span aria-hidden="true" class="h-px w-10 bg-[var(--brand)]/40"></span>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endif
