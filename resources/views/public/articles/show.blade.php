@php $settings = \App\Models\Setting::current(); @endphp

<x-layouts.public :title="$article->title . ' - ' . $settings->company_name">

    <article class="mx-auto max-w-3xl px-6 py-12">
        <a href="{{ route('articles.index') }}"
           class="group inline-flex items-center gap-1.5 text-xs text-[#8B9490] transition hover:text-[var(--brand)]">
            <i class="bx bx-left-arrow-alt text-base transition-transform group-hover:-translate-x-1" aria-hidden="true"></i>
            Semua Artikel
        </a>

        <div class="mt-5 flex flex-wrap items-center gap-3">
            @if ($article->category)
                <span class="rounded-full bg-[var(--brand)]/10 px-3 py-1 text-[11px] font-bold uppercase tracking-wide text-[var(--brand)]">
                    {{ $article->category }}
                </span>
            @endif

            @if ($article->published_at)
                <span class="flex items-center gap-1.5 text-xs text-[#8B9490]">
                    <i class="bx bx-calendar text-sm" aria-hidden="true"></i>
                    {{ $article->published_at->translatedFormat('d F Y') }}
                </span>
            @endif
        </div>

        <h1 class="mt-3 font-serif text-2xl font-bold leading-tight text-[var(--brand-dark)] md:text-4xl">
            {{ $article->title }}
        </h1>

        @if ($article->featured_image_url)
            <img src="{{ $article->featured_image_url }}" alt="{{ $article->title }}"
                 class="mt-7 aspect-video w-full rounded-xl object-cover shadow-md">
        @endif

        <div class="mt-8 space-y-4 text-[15px] leading-[1.8] text-[#4A5551]">
            {!! nl2br(e($article->body)) !!}
        </div>

        {{-- Bagikan: pakai Web Share API kalau tersedia (mobile), jatuh ke
             link share biasa kalau tidak. --}}
        @php $shareUrl = url()->current(); @endphp
        <div class="mt-10 flex flex-wrap items-center gap-3 border-t border-black/5 pt-6">
            <span class="text-xs font-semibold uppercase tracking-wide text-[#8B9490]">Bagikan</span>

            <a href="https://wa.me/?text={{ urlencode($article->title . ' ' . $shareUrl) }}" target="_blank" rel="noopener"
               aria-label="Bagikan ke WhatsApp"
               class="flex h-9 w-9 items-center justify-center rounded-full border border-black/10 text-[#5B6663] transition hover:border-[var(--brand)] hover:bg-[var(--brand)] hover:text-white">
                <i class="bx bxl-whatsapp text-lg" aria-hidden="true"></i>
            </a>

            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shareUrl) }}" target="_blank" rel="noopener"
               aria-label="Bagikan ke Facebook"
               class="flex h-9 w-9 items-center justify-center rounded-full border border-black/10 text-[#5B6663] transition hover:border-[var(--brand)] hover:bg-[var(--brand)] hover:text-white">
                <i class="bx bxl-facebook text-lg" aria-hidden="true"></i>
            </a>

            <a href="https://twitter.com/intent/tweet?url={{ urlencode($shareUrl) }}&text={{ urlencode($article->title) }}"
               target="_blank" rel="noopener" aria-label="Bagikan ke Twitter"
               class="flex h-9 w-9 items-center justify-center rounded-full border border-black/10 text-[#5B6663] transition hover:border-[var(--brand)] hover:bg-[var(--brand)] hover:text-white">
                <i class="bx bxl-twitter text-lg" aria-hidden="true"></i>
            </a>
        </div>
    </article>

    @if ($related->isNotEmpty())
        <section class="bg-[#FAF8F4] px-6 py-14">
            <div class="mx-auto max-w-6xl">
                <h2 class="mb-8 text-center font-serif text-xl font-bold text-[var(--brand-dark)]">Artikel Lainnya</h2>

                <div class="grid gap-5 md:grid-cols-3">
                    @foreach ($related as $index => $item)
                        <div class="h-full" data-reveal style="--reveal-delay: {{ $index * 90 }}ms">
                            <x-include.article-card :article="$item" />
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

</x-layouts.public>
