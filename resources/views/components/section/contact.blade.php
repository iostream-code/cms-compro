@php
    $settings = \App\Models\Setting::current();
    $waNumber = preg_replace('/\D/', '', $settings->whatsapp_number ?? '');

    // Blok kontak dibangun dari data yang terisi saja, supaya tidak ada
    // baris kosong kalau tenant belum melengkapi semua field di Pengaturan.
    $rows = collect([
        ['bx-map', 'Alamat', $settings->contact_address, null],
        ['bx-phone', 'Telepon', $settings->contact_phone, 'tel:' . $settings->contact_phone],
        ['bx-envelope', 'Email', $settings->contact_email, 'mailto:' . $settings->contact_email],
        ['bx-time-five', 'Jam Operasional', $settings->operational_hours, null],
    ])->filter(fn ($row) => filled($row[2]));

    $showMap = ($content['show_map'] ?? true) && filled($settings->maps_embed_url);

    // Kolom kanan TIDAK boleh menganga kosong. Kalau peta tidak ada (belum
    // diisi tenant atau sengaja dimatikan), isi dengan panel ajakan kontak.
    // Kalau dua-duanya tidak mungkin, kolom kiri melebar penuh -- jangan
    // sisakan setengah section kosong.
    $hasAside = $showMap || filled($waNumber);
@endphp

<section class="section-contact px-6 py-16" data-section-type="contact">
    <div class="mx-auto max-w-6xl">
        <div data-reveal>
            <x-include.section-heading
                :eyebrow="$content['eyebrow'] ?? 'Kontak'"
                :title="$content['title'] ?? null" />
        </div>

        <div class="grid items-stretch gap-6 {{ $hasAside ? 'md:grid-cols-2' : '' }}">

            <div class="space-y-4">
                @foreach ($rows as $index => [$icon, $label, $value, $href])
                    <div class="group flex gap-4 rounded-xl border border-black/5 bg-white p-4 shadow-sm transition hover:border-[var(--brand)]/30 hover:shadow-md"
                         data-reveal style="--reveal-delay: {{ $index * 80 }}ms">

                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-[var(--brand)]/10 text-[var(--brand)] transition group-hover:bg-[var(--brand)] group-hover:text-white">
                            <i class="bx {{ $icon }} text-xl" aria-hidden="true"></i>
                        </div>

                        <div>
                            <p class="text-sm font-semibold text-[var(--brand-dark)]">{{ $label }}</p>

                            @if ($href)
                                <a href="{{ $href }}" class="mt-0.5 block text-sm text-[#5B6663] transition hover:text-[var(--brand)]">{{ $value }}</a>
                            @else
                                <p class="mt-0.5 whitespace-pre-line text-sm leading-relaxed text-[#5B6663]">{{ $value }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($hasAside)
                <div class="flex flex-col gap-4" data-reveal style="--reveal-delay: 120ms">

                    @if ($showMap)
                        <iframe src="{{ $settings->maps_embed_url }}" title="Peta lokasi {{ $settings->company_name }}"
                                class="min-h-64 w-full flex-1 rounded-xl border border-black/5 shadow-sm" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
                    @endif

                    {{-- Panel ajakan: jadi pengisi utama saat peta tidak ada,
                         atau pelengkap di bawah peta saat peta ada. --}}
                    @if ($waNumber)
                        <div class="relative flex flex-col justify-center overflow-hidden rounded-xl bg-[var(--brand-dark)] p-6 text-center {{ $showMap ? '' : 'flex-1' }}">
                            <x-include.pattern variant="star" class="text-[var(--brand)]" opacity="0.10" />

                            <div class="relative">
                                <p class="font-serif text-lg font-bold text-white">Masih ada pertanyaan?</p>
                                <p class="mx-auto mt-1.5 max-w-xs text-xs leading-relaxed text-white/65">
                                    Tim kami siap membantu memilihkan paket yang paling sesuai untuk Anda.
                                </p>

                                <a href="https://wa.me/{{ $waNumber }}?text={{ urlencode($settings->whatsapp_default_message ?? '') }}"
                                   target="_blank" rel="noopener"
                                   class="mt-4 inline-flex items-center gap-2 rounded-md bg-[var(--brand)] px-6 py-2.5 text-xs font-semibold text-white transition hover:brightness-110">
                                    <i class="bx bxl-whatsapp text-base" aria-hidden="true"></i>
                                    Chat via WhatsApp
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</section>
