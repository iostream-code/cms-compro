@php
    $settings = \App\Models\Setting::current();
    $waNumber = preg_replace('/\D/', '', $settings->whatsapp_number ?? '');
    $waMessage = $settings->whatsapp_default_message ?? '';
@endphp

@if ($waNumber)
    <section class="section-cta-whatsapp relative overflow-hidden bg-[var(--brand-dark)] px-6 py-16" data-section-type="cta-whatsapp">

        {{-- Deretan lengkung mihrab -- mengesankan serambi masjid --}}
        <x-include.pattern variant="arch" class="text-[var(--brand)]" opacity="0.10" />

        <div class="relative mx-auto max-w-3xl text-center" data-reveal>
            @if (!empty($content['title']))
                <h2 class="font-serif text-2xl font-bold text-white md:text-3xl">{{ $content['title'] }}</h2>
            @endif

            @if (!empty($content['description']))
                <p class="mx-auto mt-3 max-w-xl text-sm leading-relaxed text-white/75">{{ $content['description'] }}</p>
            @endif

            <a href="https://wa.me/{{ $waNumber }}?text={{ urlencode($waMessage) }}"
               target="_blank" rel="noopener"
               class="mt-8 inline-flex items-center gap-2.5 rounded-md bg-[var(--brand)] px-8 py-3.5 text-sm font-semibold text-white shadow-lg shadow-black/25 transition hover:brightness-110 hover:shadow-xl">
                <i class="bx bxl-whatsapp text-xl" aria-hidden="true"></i>
                {{ $content['button_text'] ?? 'Chat via WhatsApp' }}
            </a>
        </div>
    </section>
@endif
