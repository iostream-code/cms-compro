@php
    $articles = \App\Models\Article::query()
        ->where('is_published', true)
        ->when($content['category_filter'] ?? null, fn ($q, $category) => $q->where('category', $category))
        ->orderByDesc('published_at')
        ->limit($content['limit'] ?? 3)
        ->get();
@endphp

@if ($articles->isNotEmpty())
    <section class="section-articles-preview px-6 py-16" data-section-type="articles-preview">
        <div class="mx-auto max-w-6xl">
            <div data-reveal>
                <x-include.section-heading
                    :eyebrow="$content['eyebrow'] ?? 'Berita Terbaru'"
                    :title="$content['title'] ?? null" />
            </div>

            <div class="grid gap-5 md:grid-cols-3">
                @foreach ($articles as $index => $article)
                    <div class="h-full" data-reveal style="--reveal-delay: {{ $index * 100 }}ms">
                        <x-include.article-card :article="$article" />
                    </div>
                @endforeach
            </div>

            <div class="mt-10 text-center" data-reveal>
                <a href="{{ route('articles.index') }}"
                   class="group inline-flex items-center gap-2 rounded-md border-2 border-[var(--brand)] px-7 py-2.5 text-sm font-semibold text-[var(--brand)] transition hover:bg-[var(--brand)] hover:text-white">
                    Baca Semua Artikel
                    <i class="bx bx-right-arrow-alt text-lg transition-transform group-hover:translate-x-1" aria-hidden="true"></i>
                </a>
            </div>
        </div>
    </section>
@endif
