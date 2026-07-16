@php $settings = \App\Models\Setting::current(); @endphp

<x-layouts.public :title="'Paket Umrah & Haji - ' . $settings->company_name">

    <x-include.page-hero
        title="Layanan Paket"
        subtitle="Pilih paket perjalanan ibadah yang paling sesuai dengan kebutuhan Anda." />

    <div class="mx-auto max-w-6xl px-6 py-12">

        @php
            $filters = [
                '' => ['Semua', 'bx-grid-alt'],
                'umroh' => ['Umrah', 'bx-moon'],
                'haji' => ['Haji', 'bx-star'],
                'wisata_religi' => ['Wisata Religi', 'bx-map-alt'],
            ];
        @endphp

        <div class="mb-9 flex flex-wrap justify-center gap-2">
            @foreach ($filters as $value => [$label, $icon])
                @php $active = (string) request('type') === (string) $value; @endphp
                <a href="{{ route('packages.index', $value ? ['type' => $value] : []) }}"
                   class="flex items-center gap-1.5 rounded-full px-5 py-2 text-sm font-medium transition
                          {{ $active
                              ? 'bg-[var(--brand)] text-white shadow'
                              : 'bg-[#F4F1EB] text-[#5B6663] hover:bg-[var(--brand)]/12 hover:text-[var(--brand)]' }}">
                    <i class="bx {{ $icon }} text-base" aria-hidden="true"></i>
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($packages as $index => $package)
                <div class="h-full" data-reveal style="--reveal-delay: {{ ($index % 3) * 90 }}ms">
                    <x-include.package-card :package="$package" />
                </div>
            @empty
                <div class="col-span-full">
                    <x-include.empty-state
                        icon="bx-package"
                        message="Belum ada paket tersedia untuk kategori ini."
                        action="Lihat semua paket"
                        :actionUrl="route('packages.index')" />
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $packages->links() }}
        </div>
    </div>

</x-layouts.public>
