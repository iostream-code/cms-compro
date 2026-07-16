@php $slides = $content['slides'] ?? []; @endphp

@if (!empty($slides))
    <section class="section-hero-slider" data-section-type="hero-slider">
        <div class="swiper hero-swiper">
            <div class="swiper-wrapper">
                @foreach ($slides as $slide)
                    <div class="swiper-slide">
                        <div class="relative flex min-h-[480px] items-center overflow-hidden md:min-h-[600px]">

                            {{-- Gambar dipisah dari container supaya bisa di-zoom (ken burns)
                                 tanpa ikut men-zoom teks di atasnya. --}}
                            <div class="absolute inset-0 bg-[var(--brand-dark)]">
                                @if (!empty($slide['image_url']))
                                    <img src="{{ $slide['image_url'] }}" alt="" aria-hidden="true"
                                         class="h-full w-full object-cover [.swiper-slide-active_&]:animate-ken-burns">
                                @endif
                            </div>

                            {{-- Dua lapis overlay: gradien gelap dari kiri untuk keterbacaan teks,
                                 plus sapuan warna brand tipis supaya nyatu dengan identitas tenant. --}}
                            <div class="absolute inset-0 bg-gradient-to-r from-black/75 via-black/45 to-transparent"></div>
                            <div class="absolute inset-0 bg-gradient-to-t from-[var(--brand-dark)]/60 to-transparent"></div>

                            <div class="relative mx-auto w-full max-w-6xl px-6">
                                <div class="max-w-xl">
                                    @if (!empty($slide['title']))
                                        <h2 class="mb-4 font-serif text-3xl font-bold leading-tight text-white drop-shadow-sm md:text-5xl">
                                            {{ $slide['title'] }}
                                        </h2>
                                    @endif

                                    @if (!empty($slide['subtitle']))
                                        <p class="mb-7 text-base leading-relaxed text-white/90 md:text-lg">{{ $slide['subtitle'] }}</p>
                                    @endif

                                    @if (!empty($slide['cta_text']))
                                        <a href="{{ $slide['cta_link'] ?? '#' }}"
                                           class="group inline-flex items-center gap-2 rounded-md bg-[var(--brand)] px-7 py-3.5 text-sm font-semibold text-white shadow-lg shadow-black/20 transition hover:brightness-110">
                                            {{ $slide['cta_text'] }}
                                            <i class="bx bx-right-arrow-alt text-lg transition-transform group-hover:translate-x-1" aria-hidden="true"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if (count($slides) > 1)
                {{--
                    Tombol panah dibuat sendiri (bukan .swiper-button-next/prev
                    bawaan) supaya ukuran & isinya sepenuhnya kita yang atur --
                    lihat catatan di app.css. Swiper cuma diberi tahu selektornya
                    lewat opsi navigation.

                    Disembunyikan di mobile: di sana panah menutupi gambar dan
                    susah dipencet, sementara user sudah terbiasa swipe dan dot
                    pagination tetap ada sebagai penanda posisi.
                --}}
                <button type="button" aria-label="Slide sebelumnya"
                        class="hero-prev group/nav absolute left-4 top-1/2 z-10 hidden h-12 w-12 -translate-y-1/2 items-center justify-center rounded-full border border-white/30 bg-black/25 text-white backdrop-blur-sm transition hover:border-[var(--brand)] hover:bg-[var(--brand)] md:flex">
                    <i class="bx bx-chevron-left text-3xl transition-transform group-hover/nav:-translate-x-0.5" aria-hidden="true"></i>
                </button>

                <button type="button" aria-label="Slide berikutnya"
                        class="hero-next group/nav absolute right-4 top-1/2 z-10 hidden h-12 w-12 -translate-y-1/2 items-center justify-center rounded-full border border-white/30 bg-black/25 text-white backdrop-blur-sm transition hover:border-[var(--brand)] hover:bg-[var(--brand)] md:flex">
                    <i class="bx bx-chevron-right text-3xl transition-transform group-hover/nav:translate-x-0.5" aria-hidden="true"></i>
                </button>

                <div class="swiper-pagination"></div>
            @endif
        </div>
    </section>

    @once
        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const el = document.querySelector('.hero-swiper');
                    if (!el || !window.Swiper) return;

                    const slideCount = el.querySelectorAll('.swiper-slide').length;

                    new window.Swiper(el, {
                        modules: Object.values(window.SwiperModules),
                        // loop butuh minimal 2 slide -- kalau cuma 1, Swiper
                        // akan menggandakannya dan malah bikin dot ganda.
                        loop: slideCount > 1,
                        speed: 700,
                        autoplay: slideCount > 1 ? { delay: 5500, disableOnInteraction: false } : false,
                        pagination: { el: '.swiper-pagination', clickable: true },
                        // Selektor tombol buatan sendiri -- lihat catatan di Blade di atas
                        navigation: { nextEl: '.hero-next', prevEl: '.hero-prev' },
                        a11y: { prevSlideMessage: 'Slide sebelumnya', nextSlideMessage: 'Slide berikutnya' },
                    });
                });
            </script>
        @endpush
    @endonce
@endif
