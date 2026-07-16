@props(['settings'])

@php
    // Admin mengetik nama platform bebas di Pengaturan ("Instagram", "instagram",
    // "IG"), jadi dipetakan lewat kata kunci -- bukan pencocokan persis.
    $socialIcon = function (string $platform): string {
        $key = strtolower(trim($platform));

        return match (true) {
            str_contains($key, 'insta') => 'bxl-instagram',
            str_contains($key, 'face') || $key === 'fb' => 'bxl-facebook',
            str_contains($key, 'you') || $key === 'yt' => 'bxl-youtube',
            str_contains($key, 'tiktok') || str_contains($key, 'tik tok') => 'bxl-tiktok',
            str_contains($key, 'twitter') || $key === 'x' => 'bxl-twitter',
            str_contains($key, 'linked') => 'bxl-linkedin',
            str_contains($key, 'telegram') => 'bxl-telegram',
            str_contains($key, 'whatsapp') || $key === 'wa' => 'bxl-whatsapp',
            default => 'bx-link',
        };
    };
@endphp

<footer class="bg-[var(--brand-dark)] text-sm text-white/70">
    <div class="mx-auto grid max-w-6xl gap-10 px-6 py-14 md:grid-cols-4">

        <div class="max-w-sm md:col-span-2">
            @if ($settings->logo_url)
                <img src="{{ $settings->logo_url }}" alt="{{ $settings->company_name }}" class="mb-4 h-11 w-auto brightness-0 invert">
            @else
                <p class="mb-3 font-serif text-lg font-semibold text-white">{{ $settings->company_name }}</p>
            @endif

            <p class="mb-5 leading-relaxed">{{ $settings->tagline }}</p>

            <div class="space-y-2">
                @if ($settings->contact_phone)
                    <a href="tel:{{ $settings->contact_phone }}" class="flex items-center gap-2 transition hover:text-white">
                        <i class="bx bx-phone text-[var(--brand)]" aria-hidden="true"></i>
                        {{ $settings->contact_phone }}
                    </a>
                @endif

                @if ($settings->contact_email)
                    <a href="mailto:{{ $settings->contact_email }}" class="flex items-center gap-2 transition hover:text-white">
                        <i class="bx bx-envelope text-[var(--brand)]" aria-hidden="true"></i>
                        {{ $settings->contact_email }}
                    </a>
                @endif
            </div>

            @if ($settings->social_links?->isNotEmpty())
                <div class="mt-5 flex gap-2.5">
                    @foreach ($settings->social_links as $link)
                        <a href="{{ $link['url'] }}" target="_blank" rel="noopener"
                           aria-label="{{ $link['platform'] }}"
                           class="flex h-9 w-9 items-center justify-center rounded-full border border-white/20 text-white/70 transition hover:-translate-y-0.5 hover:border-[var(--brand)] hover:bg-[var(--brand)] hover:text-white">
                            <i class="bx {{ $socialIcon($link['platform'] ?? '') }} text-lg" aria-hidden="true"></i>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        <div>
            <h2 class="mb-4 font-serif text-base text-white">Layanan</h2>
            <ul class="space-y-2.5">
                @foreach ([
                    ['Paket Umrah', route('packages.index', ['type' => 'umroh'])],
                    ['Paket Haji', route('packages.index', ['type' => 'haji'])],
                    ['Paket Wisata Religi', route('packages.index', ['type' => 'wisata_religi'])],
                    ['Artikel', route('articles.index')],
                    ['Galeri', route('gallery')],
                ] as [$label, $url])
                    <li>
                        <a href="{{ $url }}" class="group inline-flex items-center gap-1.5 transition hover:text-white">
                            <i class="bx bx-chevron-right text-[var(--brand)] transition-transform group-hover:translate-x-0.5" aria-hidden="true"></i>
                            {{ $label }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        <div>
            <h2 class="mb-4 font-serif text-base text-white">Kunjungi Kami</h2>

            @if ($settings->operational_hours)
                <p class="mb-4 flex gap-2 leading-relaxed">
                    <i class="bx bx-time-five mt-0.5 shrink-0 text-[var(--brand)]" aria-hidden="true"></i>
                    <span class="whitespace-pre-line">{{ $settings->operational_hours }}</span>
                </p>
            @endif

            @if ($settings->contact_address)
                <p class="flex gap-2 leading-relaxed">
                    <i class="bx bx-map mt-0.5 shrink-0 text-[var(--brand)]" aria-hidden="true"></i>
                    {{ $settings->contact_address }}
                </p>
            @endif
        </div>
    </div>

    {{-- Legalitas + copyright --}}
    <div class="border-t border-white/10">
        <div class="mx-auto flex max-w-6xl flex-wrap items-center justify-between gap-3 px-6 py-5 text-xs">
            <p>{{ $settings->footer_copyright ?: '© ' . date('Y') . ' ' . $settings->company_name }}</p>

            <div class="flex flex-wrap gap-x-4 gap-y-1">
                @if ($settings->ppiu_license)
                    <span class="flex items-center gap-1.5">
                        <i class="bx bx-badge-check text-[var(--brand)]" aria-hidden="true"></i>
                        Izin Umrah PPIU: {{ $settings->ppiu_license }}
                    </span>
                @endif

                @if ($settings->pihk_license)
                    <span class="flex items-center gap-1.5">
                        <i class="bx bx-badge-check text-[var(--brand)]" aria-hidden="true"></i>
                        Izin Haji PIHK: {{ $settings->pihk_license }}
                    </span>
                @endif
            </div>
        </div>
    </div>
</footer>
