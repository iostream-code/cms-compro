@props(['settings'])

@php
    // Menu didefinisikan sekali di sini supaya versi desktop & mobile tidak
    // pernah beda isi. `active` dicek per-route, bukan per-URL, supaya
    // halaman detail (mis. /paket/xxx) ikut menandai menu induknya.
    $menu = [
        ['label' => 'Home', 'route' => 'home', 'active' => request()->routeIs('home')],
        ['label' => 'Layanan', 'route' => 'packages.index', 'active' => request()->routeIs('packages.*')],
        ['label' => 'Dokumentasi', 'route' => 'gallery', 'active' => request()->routeIs('gallery')],
        ['label' => 'Testimoni', 'route' => 'testimonials', 'active' => request()->routeIs('testimonials')],
        ['label' => 'Artikel', 'route' => 'articles.index', 'active' => request()->routeIs('articles.*')],
    ];
@endphp

<header data-site-header class="sticky top-0 z-30">

    {{-- Topbar: tagline + kontak cepat. Dilipat saat scroll (lihat app.css) --}}
    <div data-topbar class="bg-[var(--brand)] text-white text-xs">
        <div>
            <div class="max-w-6xl mx-auto flex flex-wrap items-center justify-between gap-2 px-6 py-2.5">
                <p class="font-medium">{{ $settings->tagline }}</p>

                <div class="flex items-center gap-5">
                    @if ($settings->contact_email)
                        <a href="mailto:{{ $settings->contact_email }}" class="flex items-center gap-1.5 transition hover:opacity-75">
                            <i class="bx bx-envelope text-sm" aria-hidden="true"></i>
                            {{ $settings->contact_email }}
                        </a>
                    @endif

                    @if ($settings->contact_phone)
                        <a href="tel:{{ $settings->contact_phone }}" class="flex items-center gap-1.5 transition hover:opacity-75">
                            <i class="bx bx-phone text-sm" aria-hidden="true"></i>
                            {{ $settings->contact_phone }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Nav utama --}}
    <nav data-nav x-data="{ open: false }" class="border-b border-black/5 bg-white transition-shadow">
        <div class="max-w-6xl mx-auto flex items-center justify-between px-6 py-3.5">

            <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                @if ($settings->logo_url)
                    <img src="{{ $settings->logo_url }}" alt="{{ $settings->company_name }}" class="h-10 w-auto">
                @else
                    <span class="font-serif text-lg font-semibold text-[var(--brand-dark)]">{{ $settings->company_name }}</span>
                @endif
            </a>

            <button @click="open = !open" class="md:hidden text-2xl text-[var(--brand-dark)]"
                    :aria-expanded="open ? 'true' : 'false'" aria-label="Buka menu">
                <i class="bx" :class="open ? 'bx-x' : 'bx-menu'" aria-hidden="true"></i>
            </button>

            <div class="hidden items-center gap-7 text-sm font-medium tracking-wide md:flex">
                @foreach ($menu as $item)
                    <a href="{{ route($item['route']) }}"
                       @if ($item['active']) aria-current="page" @endif
                       class="group relative py-1 uppercase transition
                              {{ $item['active'] ? 'text-[var(--brand)]' : 'text-[var(--brand-dark)] hover:text-[var(--brand)]' }}">
                        {{ $item['label'] }}
                        {{-- Underline yang melebar dari tengah saat hover / aktif --}}
                        <span class="absolute inset-x-0 -bottom-0.5 mx-auto h-0.5 rounded-full bg-[var(--brand)] transition-all duration-300
                                     {{ $item['active'] ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
                    </a>
                @endforeach

                <a href="{{ route('login') }}"
                   class="flex items-center gap-1.5 rounded-md border border-[var(--brand)] px-4 py-1.5 uppercase text-[var(--brand)] transition hover:bg-[var(--brand)] hover:text-white">
                    <i class="bx bx-log-in text-base" aria-hidden="true"></i>
                    Login
                </a>
            </div>
        </div>

        {{-- Menu mobile --}}
        <div x-show="open" x-cloak x-collapse class="border-t border-black/5 px-6 py-2 md:hidden">
            @foreach ($menu as $item)
                <a href="{{ route($item['route']) }}"
                   @if ($item['active']) aria-current="page" @endif
                   class="block border-b border-black/5 py-2.5 text-sm last:border-0
                          {{ $item['active'] ? 'font-semibold text-[var(--brand)]' : 'text-[var(--brand-dark)]' }}">
                    {{ $item['label'] }}
                </a>
            @endforeach

            <a href="{{ route('login') }}" class="mt-2 mb-1 block rounded-md bg-[var(--brand)] py-2.5 text-center text-sm font-semibold text-white">
                Login
            </a>
        </div>
    </nav>
</header>
