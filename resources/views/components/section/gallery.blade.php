@php $images = $content['images'] ?? []; @endphp

@if (!empty($images))
    <section class="section-gallery bg-[#FAF8F4] px-6 py-16" data-section-type="gallery">
        <div class="mx-auto max-w-6xl">
            <div data-reveal>
                <x-include.section-heading
                    :eyebrow="$content['eyebrow'] ?? 'Galeri'"
                    :title="$content['title'] ?? null"
                    :subtitle="$content['subtitle'] ?? null" />
            </div>

            <div class="grid grid-cols-2 gap-3 md:grid-cols-4">
                @foreach ($images as $index => $image)
                    <figure class="group relative overflow-hidden rounded-xl bg-black/5"
                            data-reveal style="--reveal-delay: {{ ($index % 4) * 70 }}ms">

                        <img src="{{ $image['image_url'] ?? '' }}" alt="{{ $image['caption'] ?? '' }}" loading="lazy"
                             class="h-44 w-full object-cover transition duration-500 group-hover:scale-110">

                        {{-- Overlay + caption naik dari bawah saat hover --}}
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

            <div class="mt-10 text-center" data-reveal>
                <a href="{{ route('gallery') }}"
                   class="group inline-flex items-center gap-2 rounded-md border-2 border-[var(--brand)] px-7 py-2.5 text-sm font-semibold text-[var(--brand)] transition hover:bg-[var(--brand)] hover:text-white">
                    Lihat Semua Dokumentasi
                    <i class="bx bx-right-arrow-alt text-lg transition-transform group-hover:translate-x-1" aria-hidden="true"></i>
                </a>
            </div>
        </div>
    </section>
@endif
