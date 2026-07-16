@props(['article'])

{{-- Dipakai di section articles-preview, halaman /artikel, dan blok "artikel lainnya" --}}
<a href="{{ route('articles.show', $article) }}"
   class="group flex h-full flex-col overflow-hidden rounded-xl border border-black/5 bg-white shadow-sm transition duration-300 hover:-translate-y-1.5 hover:shadow-xl">

    <div class="relative h-44 overflow-hidden bg-black/5">
        @if ($article->featured_image_url)
            <img src="{{ $article->featured_image_url }}" alt="{{ $article->title }}" loading="lazy"
                 class="h-full w-full object-cover transition duration-500 group-hover:scale-110">
        @endif

        @if ($article->category)
            <span class="absolute left-3 top-3 rounded-full bg-white/95 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide text-[var(--brand)] shadow">
                {{ $article->category }}
            </span>
        @endif
    </div>

    <div class="flex flex-1 flex-col p-5">
        @if ($article->published_at)
            <p class="mb-2 flex items-center gap-1.5 text-[11px] text-[#8B9490]">
                <i class="bx bx-calendar text-sm text-[var(--brand)]" aria-hidden="true"></i>
                {{ $article->published_at->translatedFormat('d F Y') }}
            </p>
        @endif

        <h3 class="font-serif text-base font-bold leading-snug text-[var(--brand-dark)] transition group-hover:text-[var(--brand)]">
            {{ $article->title }}
        </h3>

        @if ($article->excerpt)
            <p class="mt-2 text-xs leading-relaxed text-[#8B9490]">
                {{ \Illuminate\Support\Str::limit($article->excerpt, 110) }}
            </p>
        @endif

        <span class="mt-4 inline-flex items-center gap-1.5 text-xs font-semibold text-[var(--brand)]">
            Baca Selengkapnya
            <i class="bx bx-right-arrow-alt text-base transition-transform group-hover:translate-x-1" aria-hidden="true"></i>
        </span>
    </div>
</a>
