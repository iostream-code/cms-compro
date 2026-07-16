@php $items = $content['items'] ?? []; @endphp

@if (!empty($items))
    <section class="section-facilities px-6 py-16" data-section-type="facilities">
        <div class="mx-auto max-w-6xl">
            <div data-reveal>
                <x-include.section-heading
                    :eyebrow="$content['eyebrow'] ?? 'Fasilitas Jamaah'"
                    :title="$content['title'] ?? null"
                    :subtitle="$content['subtitle'] ?? null" />
            </div>

            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($items as $index => $item)
                    <div class="group relative overflow-hidden rounded-xl border border-black/5 bg-white p-6 text-center shadow-sm transition duration-300 hover:-translate-y-1.5 hover:shadow-xl"
                         data-reveal style="--reveal-delay: {{ ($index % 4) * 80 }}ms">

                        {{-- Sapuan warna brand yang naik dari bawah saat hover --}}
                        <span aria-hidden="true"
                              class="absolute inset-x-0 bottom-0 h-0 bg-[var(--brand)]/5 transition-all duration-300 group-hover:h-full"></span>

                        <div class="relative">
                            <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-[var(--brand)]/10 text-[var(--brand)] transition duration-300 group-hover:bg-[var(--brand)] group-hover:text-white">
                                <x-include.icon :name="$item['icon'] ?? null" class="text-2xl" fallback="bx-check" />
                            </div>

                            <p class="text-sm font-semibold text-[var(--brand-dark)]">{{ $item['label'] ?? '' }}</p>

                            @if (!empty($item['description']))
                                <p class="mt-1.5 text-xs leading-relaxed text-[#8B9490]">{{ $item['description'] }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
