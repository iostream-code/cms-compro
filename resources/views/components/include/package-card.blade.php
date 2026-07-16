@props(['package'])

@php
    // Baris detail dibangun dari data yang ada saja, supaya kartu paket wisata
    // (yang tak punya hotel Makkah/Madinah) tidak menyisakan baris kosong.
    $details = collect([
        ['bx-buildings', $package->hotel_makkah, 'Makkah'],
        ['bx-buildings', $package->hotel_madinah, 'Madinah'],
        ['bxs-plane-take-off', $package->airline, null],
        ['bx-map-pin', $package->departure_airport, null],
    ])->filter(fn ($row) => filled($row[1]) && $row[1] !== '-');
@endphp

{{-- h-full: wajib supaya semua kartu dalam satu baris grid tingginya rata,
     bukan mengikuti panjang kontennya masing-masing. --}}
<article class="group flex h-full flex-col overflow-hidden rounded-xl border border-black/5 bg-white shadow-sm transition duration-300 hover:-translate-y-1.5 hover:shadow-xl">

    <div class="relative h-48 overflow-hidden bg-[var(--brand-dark)]">
        @if ($package->image_url)
            <img src="{{ $package->image_url }}" alt="{{ $package->name }}" loading="lazy"
                 class="h-full w-full object-cover transition duration-500 group-hover:scale-110">
        @endif

        {{-- Gradien bawah supaya badge tetap terbaca di atas gambar terang --}}
        <span aria-hidden="true" class="absolute inset-x-0 bottom-0 h-20 bg-gradient-to-t from-black/50 to-transparent"></span>

        <span class="absolute left-3 top-3 rounded-full bg-[var(--brand)] px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide text-white shadow">
            {{ str_replace('_', ' ', $package->type) }}
        </span>

        @if ($package->seats_available !== null)
            @php $isLimited = $package->seats_available > 0 && $package->seats_available <= 10; @endphp
            <span class="absolute right-3 top-3 flex items-center gap-1 rounded-full px-2.5 py-1 text-[10px] font-bold shadow
                         {{ $isLimited ? 'bg-red-500 text-white' : 'bg-white/95 text-[var(--brand-dark)]' }}">
                @if ($isLimited)
                    {{-- Titik berdenyut hanya saat seat menipis -- menandakan urgensi
                         nyata, bukan sekadar hiasan di semua kartu. --}}
                    <span class="relative flex h-1.5 w-1.5">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-white opacity-75"></span>
                        <span class="relative inline-flex h-1.5 w-1.5 rounded-full bg-white"></span>
                    </span>
                @endif
                Sisa {{ $package->seats_available }} Seat
            </span>
        @endif
    </div>

    <div class="flex flex-1 flex-col p-5">
        <h3 class="font-serif text-base font-bold leading-snug text-[var(--brand-dark)] transition group-hover:text-[var(--brand)]">
            {{ $package->name }}
        </h3>

        <p class="mt-2 flex items-center gap-1.5 text-xs text-[#5B6663]">
            <i class="bx bx-calendar shrink-0 text-sm text-[var(--brand)]" aria-hidden="true"></i>
            {{ $package->departure_date?->translatedFormat('d F Y') ?: ($package->departure_date_note ?: 'Tanggal belum tersedia') }}
        </p>

        @if ($details->isNotEmpty())
            <div class="mt-3 space-y-1.5 border-t border-dashed border-black/10 pt-3 text-xs text-[#5B6663]">
                @foreach ($details as [$icon, $value, $note])
                    <p class="flex items-start gap-1.5">
                        <i class="bx {{ $icon }} mt-0.5 shrink-0 text-sm text-[var(--brand)]" aria-hidden="true"></i>
                        <span>
                            {{ $value }}
                            @if ($note) <span class="text-[#9AA5A1]">({{ $note }})</span> @endif
                        </span>
                    </p>
                @endforeach
            </div>
        @endif

        {{-- Harga + CTA didorong ke bawah supaya tinggi kartu rata dalam satu baris grid --}}
        <div class="mt-auto pt-4">
            @if ($package->price_from)
                <p class="text-[11px] text-[#8B9490]">Harga mulai :</p>
                <p class="font-serif text-lg font-bold text-[var(--brand)]">
                    {{ $package->price_currency }} {{ number_format($package->price_from, 0, ',', '.') }}
                </p>
            @endif

            <a href="{{ route('packages.show', $package) }}"
               class="mt-3 flex items-center justify-center gap-1.5 rounded-md bg-[var(--brand-dark)] py-2.5 text-xs font-semibold uppercase tracking-wide text-white transition hover:bg-[var(--brand)]">
                Detail Paket
                <i class="bx bx-right-arrow-alt text-base transition-transform group-hover:translate-x-1" aria-hidden="true"></i>
            </a>
        </div>
    </div>
</article>
