@php
    $settings = \App\Models\Setting::current();

    // Warna per tenant -- di-inject sebagai CSS variable supaya seluruh
    // template ikut tanpa perlu class Tailwind dinamis (yang tidak
    // ke-compile JIT). Fallback ke emas seperti default template.
    $primary = $settings->primary_color ?: '#C8952B';
    $secondary = $settings->secondary_color ?: '#0E3B35';
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? ($settings->company_name ?? 'Travel Umrah & Haji') }}</title>

    @if ($settings->favicon_url)
        <link rel="icon" href="{{ $settings->favicon_url }}">
    @endif

    {{-- SEO per halaman -- diisi kalau view-nya kirim $seo --}}
    @isset($seo)
        @if ($seo?->meta_description)
            <meta name="description" content="{{ $seo->meta_description }}">
        @endif
        @if ($seo?->canonical_url)
            <link rel="canonical" href="{{ $seo->canonical_url }}">
        @endif
        <meta name="robots" content="{{ $seo?->robots ?? 'index, follow' }}">
    @endisset

    <style>
        :root {
            --brand: {{ $primary }};
            --brand-dark: {{ $secondary }};
        }
    </style>

    {{-- Data banner console. Hanya info yang memang boleh publik: nama
         pembuat, URL, versi rilis. Detail infrastruktur (nama/IP server)
         sengaja TIDAK dikirim -- lihat catatan di public.js.

         Arraynya dirakit di blok PHP di bawah, bukan ditulis langsung sebagai
         argumen json: direktif Blade tidak menerima argumen array multi-baris
         dan akan memotong ekspresinya jadi ParseError.

         CATATAN: jangan pernah menulis nama direktif ber-"at" di dalam
         komentar seperti ini -- Blade meng-compile direktif SEBELUM menghapus
         komentar, jadi direktif di dalam komentar tetap ikut dieksekusi dan
         merusak sisa file. --}}
    @php
        $branding = [
            'name' => config('branding.name'),
            'url' => config('branding.url'),
            'tagline' => config('branding.tagline'),
            'color' => config('branding.color'),
            'version' => config('branding.version'),
        ];
    @endphp
    <script>
        window.__branding = @json($branding);
    </script>

    @vite(['resources/css/app.css', 'resources/js/public.js'])
</head>
<body class="bg-white text-[#1F2421] antialiased">

    <x-include.public-header :settings="$settings" />

    <main>
        {{ $slot }}
    </main>

    <x-include.public-footer :settings="$settings" />

    {{--
        Kembali ke atas. Lebar & posisinya dihitung supaya SUMBU VERTIKALNYA
        segaris dengan tombol WhatsApp di bawahnya:
        WhatsApp  right-6 (1.5rem) + w-14/2 (1.75rem) = 3.25rem dari kanan
        Tombol ini right-7 (1.75rem) + w-12/2 (1.5rem) = 3.25rem dari kanan
    --}}
    <button type="button" data-scroll-top aria-label="Kembali ke atas"
            class="fixed bottom-24 right-7 z-40 flex h-12 w-12 items-center justify-center rounded-full border border-black/10 bg-white text-[var(--brand-dark)] shadow-lg transition hover:bg-[var(--brand)] hover:text-white">
        <i class="bx bx-chevron-up text-2xl" aria-hidden="true"></i>
    </button>

    {{-- Tombol WhatsApp mengambang --}}
    @php $waNumber = preg_replace('/\D/', '', $settings->whatsapp_number ?? ''); @endphp
    @if ($waNumber)
        <a href="https://wa.me/{{ $waNumber }}?text={{ urlencode($settings->whatsapp_default_message ?? '') }}"
           target="_blank" rel="noopener" aria-label="Chat via WhatsApp"
           class="group fixed bottom-6 right-6 z-40 flex h-14 w-14 items-center justify-center rounded-full bg-[#25D366] shadow-lg shadow-black/25 transition hover:scale-110">

            {{-- Denyut halus supaya tombol terlihat tanpa harus mengganggu --}}
            <span aria-hidden="true" class="absolute inline-flex h-full w-full animate-ping rounded-full bg-[#25D366] opacity-30"></span>

            <i class="bx bxl-whatsapp relative text-3xl text-white" aria-hidden="true"></i>

            {{-- Label muncul saat hover di layar besar --}}
            <span class="pointer-events-none absolute right-16 hidden whitespace-nowrap rounded-md bg-[var(--brand-dark)] px-3 py-1.5 text-xs font-medium text-white opacity-0 transition group-hover:opacity-100 md:block">
                Butuh bantuan?
            </span>
        </a>
    @endif

    @stack('scripts')

</body>
</html>
