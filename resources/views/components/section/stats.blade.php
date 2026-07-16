@php $items = $content['items'] ?? []; @endphp

@if (!empty($items))
    <section class="section-stats relative overflow-hidden bg-[var(--brand)] px-6 py-14" data-section-type="stats">

        {{-- Kisi jali (kerawang masjid) menggantikan garis diagonal generik --}}
        <x-include.pattern variant="lattice" class="text-white" opacity="0.12" />

        <div class="relative mx-auto max-w-6xl">
            {{-- grid-cols di-fix (bukan dihitung dinamis) -- Tailwind JIT cuma compile class literal yang ada di source --}}
            <div class="grid grid-cols-2 gap-x-6 gap-y-9 text-center md:grid-cols-4">
                @foreach ($items as $index => $item)
                    <div class="relative" data-reveal style="--reveal-delay: {{ $index * 90 }}ms">

                        {{-- Garis pemisah antar kolom, kecuali kolom pertama tiap baris --}}
                        @if (!$loop->first)
                            <span aria-hidden="true"
                                  class="absolute -left-3 top-1/2 hidden h-10 w-px -translate-y-1/2 bg-white/25 md:block"></span>
                        @endif

                        <p class="font-serif text-3xl font-bold text-white md:text-4xl">
                            {{-- data-counter dibaca public.js untuk animasi hitung naik.
                                 Isi awalnya 0 supaya tidak "loncat" dari angka penuh ke 0. --}}
                            <span data-counter="{{ $item['value'] ?? '' }}">0</span><span class="text-white/75">{{ $item['suffix'] ?? '' }}</span>
                        </p>

                        <p class="mt-2 text-xs font-medium uppercase tracking-wide text-white/80">{{ $item['label'] ?? '' }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
