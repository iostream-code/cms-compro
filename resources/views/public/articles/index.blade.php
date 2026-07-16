@php $settings = \App\Models\Setting::current(); @endphp

<x-layouts.public :title="'Artikel - ' . $settings->company_name">

    <x-include.page-hero
        title="Berita & Artikel"
        subtitle="Informasi seputar umrah, haji, dan panduan ibadah." />

    <div class="mx-auto max-w-6xl px-6 py-12">

        @if ($categories->isNotEmpty())
            <div class="mb-9 flex flex-wrap justify-center gap-2">
                <a href="{{ route('articles.index') }}"
                   class="rounded-full px-5 py-2 text-sm font-medium transition
                          {{ !request('kategori') ? 'bg-[var(--brand)] text-white shadow' : 'bg-[#F4F1EB] text-[#5B6663] hover:bg-[var(--brand)]/12 hover:text-[var(--brand)]' }}">
                    Semua
                </a>

                @foreach ($categories as $category)
                    <a href="{{ route('articles.index', ['kategori' => $category]) }}"
                       class="rounded-full px-5 py-2 text-sm font-medium transition
                              {{ request('kategori') === $category ? 'bg-[var(--brand)] text-white shadow' : 'bg-[#F4F1EB] text-[#5B6663] hover:bg-[var(--brand)]/12 hover:text-[var(--brand)]' }}">
                        {{ $category }}
                    </a>
                @endforeach
            </div>
        @endif

        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($articles as $index => $article)
                <div class="h-full" data-reveal style="--reveal-delay: {{ ($index % 3) * 90 }}ms">
                    <x-include.article-card :article="$article" />
                </div>
            @empty
                <div class="col-span-full">
                    <x-include.empty-state
                        icon="bx-news"
                        message="Belum ada artikel untuk kategori ini." />
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $articles->links() }}
        </div>
    </div>

</x-layouts.public>
