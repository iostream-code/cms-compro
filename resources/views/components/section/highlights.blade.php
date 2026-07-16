@php $items = $content['items'] ?? []; @endphp

@if (!empty($items))
    <section class="section-highlights relative overflow-hidden bg-[var(--brand-dark)] px-6 py-16" data-section-type="highlights">

        {{-- Motif bintang delapan (khatim) menggantikan aksen lingkaran:
             lebih terbaca sebagai ornamen islami, bukan sekadar hiasan generik. --}}
        <x-include.pattern variant="star" class="text-[var(--brand)]" opacity="0.10" />

        {{-- Pita brand tipis di tepi atas & bawah, meniru garis batas ornamen masjid --}}
        <span aria-hidden="true" class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-[var(--brand)]/45 to-transparent"></span>
        <span aria-hidden="true" class="absolute inset-x-0 bottom-0 h-px bg-gradient-to-r from-transparent via-[var(--brand)]/45 to-transparent"></span>

        <div class="relative mx-auto max-w-6xl">
            @if (!empty($content['title']))
                <div class="mb-11 text-center" data-reveal>
                    <h2 class="font-serif text-2xl font-bold text-white md:text-3xl">{{ $content['title'] }}</h2>
                    <span class="mx-auto mt-3 block h-[3px] w-14 rounded-full bg-[var(--brand)]"></span>
                </div>
            @endif

            <div class="grid gap-7 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($items as $index => $item)
                    {{-- Delay berjenjang: kartu muncul berurutan, bukan serempak --}}
                    <div class="group flex gap-4" data-reveal style="--reveal-delay: {{ $index * 90 }}ms">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-[var(--brand)] text-white shadow-lg shadow-black/20 transition duration-300 group-hover:-translate-y-1 group-hover:rotate-6">
                            <x-include.icon :name="$item['icon'] ?? null" class="text-2xl" fallback="bx-check-shield" />
                        </div>

                        <div>
                            <p class="text-sm font-semibold text-white">{{ $item['title'] ?? '' }}</p>
                            @if (!empty($item['description']))
                                <p class="mt-1.5 text-xs leading-relaxed text-white/65">{{ $item['description'] }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
