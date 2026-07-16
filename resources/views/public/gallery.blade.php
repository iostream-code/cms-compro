@php $settings = \App\Models\Setting::current(); @endphp

<x-layouts.public :title="'Dokumentasi - ' . $settings->company_name">

    <x-include.page-hero
        title="Galeri Dokumentasi"
        subtitle="Momen perjalanan ibadah para jamaah bersama kami." />

    <div class="mx-auto max-w-6xl px-6 py-12">
        @if ($images->isNotEmpty())
            <div class="grid grid-cols-2 gap-3 md:grid-cols-3 lg:grid-cols-4">
                @foreach ($images as $index => $image)
                    <figure class="group relative overflow-hidden rounded-xl bg-black/5"
                            data-reveal style="--reveal-delay: {{ ($index % 4) * 70 }}ms">

                        <img src="{{ $image['image_url'] }}" alt="{{ $image['caption'] ?? '' }}" loading="lazy"
                             class="h-48 w-full object-cover transition duration-500 group-hover:scale-110">

                        <span aria-hidden="true"
                              class="absolute inset-0 bg-[var(--brand-dark)]/0 transition duration-300 group-hover:bg-[var(--brand-dark)]/35"></span>

                        @if (!empty($image['caption']))
                            <figcaption class="absolute inset-x-0 bottom-0 translate-y-1 bg-gradient-to-t from-black/80 to-transparent px-3 pb-2.5 pt-8 text-xs font-medium text-white opacity-0 transition duration-300 group-hover:translate-y-0 group-hover:opacity-100">
                                {{ $image['caption'] }}
                            </figcaption>
                        @endif
                    </figure>
                @endforeach
            </div>
        @else
            <x-include.empty-state
                icon="bx-images"
                message="Belum ada foto dokumentasi." />
        @endif
    </div>

</x-layouts.public>
