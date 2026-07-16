@php
    $typeFilter = $content['package_type_filter'] ?? 'all';

    $packages = \App\Models\Package::query()
        ->where('is_published', true)
        ->when($typeFilter !== 'all', fn ($q) => $q->where('type', $typeFilter))
        ->orderBy('order')
        ->orderByDesc('created_at')
        ->limit($content['limit'] ?? 4)
        ->get();
@endphp

@if ($packages->isNotEmpty())
    <section class="section-packages-preview bg-[#FAF8F4] px-6 py-16" data-section-type="packages-preview">
        <div class="mx-auto max-w-6xl">
            <div data-reveal>
                <x-include.section-heading
                    :eyebrow="$content['eyebrow'] ?? null"
                    :title="$content['title'] ?? null" />
            </div>

            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($packages as $index => $package)
                    <div class="h-full" data-reveal style="--reveal-delay: {{ ($index % 4) * 90 }}ms">
                        <x-include.package-card :package="$package" />
                    </div>
                @endforeach
            </div>

            <div class="mt-10 text-center" data-reveal>
                <a href="{{ route('packages.index', $typeFilter !== 'all' ? ['type' => $typeFilter] : []) }}"
                   class="group inline-flex items-center gap-2 rounded-md border-2 border-[var(--brand)] px-7 py-2.5 text-sm font-semibold text-[var(--brand)] transition hover:bg-[var(--brand)] hover:text-white">
                    Selengkapnya
                    <i class="bx bx-right-arrow-alt text-lg transition-transform group-hover:translate-x-1" aria-hidden="true"></i>
                </a>
            </div>
        </div>
    </section>
@endif
