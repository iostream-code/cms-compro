@php
    $settings = \App\Models\Setting::current();
    $waNumber = preg_replace('/\D/', '', $settings->whatsapp_number ?? '');
    $waMessage = 'Assalamualaikum, saya ingin bertanya tentang paket "' . $package->name . '"';

    $facts = collect([
        ['bx-time-five', 'Durasi', $package->duration],
        ['bx-calendar', 'Keberangkatan', $package->departure_date?->translatedFormat('d F Y') ?: $package->departure_date_note],
        ['bx-map-pin', 'Bandara', $package->departure_airport],
        ['bxs-plane-take-off', 'Maskapai', $package->airline],
        ['bx-buildings', 'Hotel Makkah', $package->hotel_makkah],
        ['bx-buildings', 'Hotel Madinah', $package->hotel_madinah],
    ])->filter(fn ($f) => filled($f[2]) && $f[2] !== '-');
@endphp

<x-layouts.public :title="$package->name . ' - ' . $settings->company_name">

    {{-- Header paket --}}
    <div class="relative overflow-hidden bg-[var(--brand-dark)] px-6 py-16">
        @if ($package->image_url)
            <img src="{{ $package->image_url }}" alt="" aria-hidden="true"
                 class="absolute inset-0 h-full w-full object-cover opacity-25">
        @endif

        <div class="absolute inset-0 bg-gradient-to-t from-[var(--brand-dark)] via-[var(--brand-dark)]/60 to-transparent"></div>

        <div class="relative mx-auto max-w-4xl text-center">
            <span class="inline-block rounded-full bg-[var(--brand)] px-3 py-1 text-[11px] font-bold uppercase tracking-wide text-white">
                {{ str_replace('_', ' ', $package->type) }}
            </span>

            <h1 class="mt-4 font-serif text-2xl font-bold text-white md:text-4xl">{{ $package->name }}</h1>

            @if ($package->short_description)
                <p class="mx-auto mt-3 max-w-2xl text-sm text-white/75">{{ $package->short_description }}</p>
            @endif
        </div>
    </div>

    <div class="mx-auto max-w-6xl px-6 py-12">
        <div class="grid gap-8 lg:grid-cols-3">

            {{-- Kolom utama --}}
            <div class="space-y-10 lg:col-span-2">

                @if ($facts->isNotEmpty())
                    <div class="grid gap-3 sm:grid-cols-2">
                        @foreach ($facts as $index => [$icon, $label, $value])
                            <div class="flex items-start gap-3 rounded-lg border border-black/5 bg-[#FAF8F4] px-4 py-3"
                                 data-reveal style="--reveal-delay: {{ ($index % 2) * 60 }}ms">
                                <i class="bx {{ $icon }} mt-0.5 text-lg text-[var(--brand)]" aria-hidden="true"></i>
                                <div>
                                    <p class="text-[11px] font-medium uppercase tracking-wide text-[#8B9490]">{{ $label }}</p>
                                    <p class="mt-0.5 text-sm font-semibold text-[var(--brand-dark)]">{{ $value }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if ($package->description)
                    <div data-reveal>
                        <x-include.detail-heading icon="bx-detail" title="Deskripsi" />
                        <div class="text-sm leading-relaxed text-[#5B6663]">{!! nl2br(e($package->description)) !!}</div>
                    </div>
                @endif

                {{-- Itinerary --}}
                @if ($package->itinerary?->isNotEmpty())
                    <div data-reveal>
                        <x-include.detail-heading icon="bx-map-alt" title="Itinerary" />

                        <ol class="relative space-y-6 border-l-2 border-dashed border-[var(--brand)]/30 pl-7">
                            @foreach ($package->itinerary as $day)
                                <li class="relative">
                                    <span aria-hidden="true"
                                          class="absolute -left-[35px] flex h-6 w-6 items-center justify-center rounded-full bg-[var(--brand)] text-[10px] font-bold text-white ring-4 ring-white">
                                        {{ $day['day'] ?? '•' }}
                                    </span>

                                    <p class="text-sm font-semibold text-[var(--brand-dark)]">
                                        Hari ke-{{ $day['day'] ?? '' }}@if (!empty($day['title'])) <span class="font-normal text-[#8B9490]">·</span> {{ $day['title'] }} @endif
                                    </p>

                                    @if (!empty($day['description']))
                                        <p class="mt-1 text-xs leading-relaxed text-[#8B9490]">{{ $day['description'] }}</p>
                                    @endif
                                </li>
                            @endforeach
                        </ol>
                    </div>
                @endif

                {{-- Fasilitas --}}
                @if ($package->facilities?->isNotEmpty())
                    <div data-reveal>
                        <x-include.detail-heading icon="bx-package" title="Fasilitas" />

                        <div class="grid gap-2.5 sm:grid-cols-2">
                            @foreach ($package->facilities as $facility)
                                <div class="flex items-center gap-2.5 text-sm text-[#5B6663]">
                                    <i class="bx bx-check-circle shrink-0 text-base text-[var(--brand)]" aria-hidden="true"></i>
                                    {{ $facility }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @foreach ([
                    ['bx-list-check', 'Persyaratan Peserta', $package->requirements],
                    ['bx-file', 'Syarat & Ketentuan', $package->terms_conditions],
                ] as [$icon, $heading, $body])
                    @if ($body)
                        <div data-reveal>
                            <x-include.detail-heading :icon="$icon" :title="$heading" />
                            <div class="text-sm leading-relaxed text-[#5B6663]">{!! nl2br(e($body)) !!}</div>
                        </div>
                    @endif
                @endforeach
            </div>

            {{-- Sidebar harga + CTA --}}
            <aside class="lg:sticky lg:top-28 lg:h-fit">
                <div class="overflow-hidden rounded-xl border border-black/5 bg-white shadow-lg">

                    <div class="bg-[var(--brand-dark)] px-6 py-5">
                        @if ($package->price_from)
                            <p class="text-xs text-white/60">Harga mulai dari</p>
                            <p class="font-serif text-2xl font-bold text-[var(--brand)]">
                                {{ $package->price_currency }} {{ number_format($package->price_from, 0, ',', '.') }}
                            </p>
                        @endif

                        @if ($package->seats_available !== null)
                            <p class="mt-2 flex items-center gap-1.5 text-xs text-white/70">
                                <i class="bx bx-user-check text-sm text-[var(--brand)]" aria-hidden="true"></i>
                                Sisa <span class="font-semibold text-white">{{ $package->seats_available }}</span> seat
                                @if ($package->seats_total) dari {{ $package->seats_total }} @endif
                            </p>
                        @endif
                    </div>

                    <div class="p-6">
                        @if ($package->room_types?->isNotEmpty())
                            <p class="mb-2.5 text-xs font-semibold uppercase tracking-wide text-[#8B9490]">Pilihan Kamar</p>

                            <div class="mb-5 space-y-2">
                                @foreach ($package->room_types as $room)
                                    <div class="flex items-center justify-between gap-3 rounded-lg bg-[#FAF8F4] px-3 py-2.5">
                                        <span class="flex items-center gap-1.5 text-xs text-[#5B6663]">
                                            <i class="bx bx-bed text-sm text-[var(--brand)]" aria-hidden="true"></i>
                                            {{ $room['label'] ?? '' }}
                                        </span>
                                        <span class="whitespace-nowrap text-xs font-bold text-[var(--brand-dark)]">
                                            {{ $package->price_currency }} {{ number_format((float) ($room['price'] ?? 0), 0, ',', '.') }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <div class="space-y-2.5">
                            @if ($waNumber)
                                <a href="https://wa.me/{{ $waNumber }}?text={{ urlencode($waMessage) }}" target="_blank" rel="noopener"
                                   class="flex items-center justify-center gap-2 rounded-md bg-[var(--brand)] py-3 text-sm font-semibold text-white transition hover:brightness-110">
                                    <i class="bx bxl-whatsapp text-lg" aria-hidden="true"></i>
                                    Konsultasi Paket
                                </a>
                            @endif

                            @if ($package->brochure_url)
                                <a href="{{ $package->brochure_url }}" target="_blank" rel="noopener"
                                   class="flex items-center justify-center gap-2 rounded-md border-2 border-[var(--brand-dark)] py-2.5 text-sm font-semibold text-[var(--brand-dark)] transition hover:bg-[var(--brand-dark)] hover:text-white">
                                    <i class="bx bx-download text-lg" aria-hidden="true"></i>
                                    Download Brosur
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <a href="{{ route('packages.index') }}"
                   class="group mt-4 flex items-center justify-center gap-1.5 text-xs text-[#8B9490] transition hover:text-[var(--brand)]">
                    <i class="bx bx-left-arrow-alt text-base transition-transform group-hover:-translate-x-1" aria-hidden="true"></i>
                    Lihat paket lainnya
                </a>
            </aside>
        </div>
    </div>

</x-layouts.public>
