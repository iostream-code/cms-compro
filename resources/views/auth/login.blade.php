@php
    // Waktu awal dirender server sebagai cadangan kalau JS mati -- pakai WIB,
    // bukan config('app.timezone') yang masih UTC (salamnya akan meleset 7 jam).
    // Begitu JS jalan, keduanya diganti waktu LOKAL browser, yang lebih benar
    // untuk admin yang mungkin di WITA/WIT.
    $now = \Illuminate\Support\Carbon::now('Asia/Jakarta');
    $h = (int) $now->format('G');
    $greeting = match (true) {
        $h >= 4 && $h < 11 => 'Selamat Pagi',
        $h >= 11 && $h < 15 => 'Selamat Siang',
        $h >= 15 && $h < 18 => 'Selamat Sore',
        default => 'Selamat Malam',
    };
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk — {{ config('app.name', 'CMS') }}</title>

    {{-- Palet khusus halaman ini. Sengaja tidak memakai --brand tenant:
         gerbang ini identitas CMS-nya, bukan identitas travel-nya, jadi
         tampil sama untuk semua client. --}}
    <style>
        :root {
            --gold-bright: #f0d97d;
            --gold: #d4af37;
            --gold-soft: #c5a059;
            --gold-deep: #8a6a1f;
            --emerald: #062d26;
            --emerald-mid: #0a3d33;
            /* dipakai komponen pattern & mustaka yang mengacu --brand */
            --brand: #d4af37;
            --brand-dark: #062d26;
        }
    </style>

    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-[var(--emerald)] text-white antialiased">

    {{--
        overflow-x-clip, BUKAN overflow-hidden.

        Dengan overflow-hidden, konten yang melebihi tinggi layar terpotong dan
        TIDAK BISA di-scroll -- hilang permanen. Di HP 375x667 konten gerbang
        ~781px, jadi kelebihan ~114px dibagi rata oleh items-center: ~57px
        terpotong di ATAS, tepat memakan mustaka. Itulah "offside top" yang
        terlihat.

        overflow-x-clip cuma memangkas sumbu X (tritisan atap & awan yang
        sengaja menjorok ke samping), sementara sumbu Y dibiarkan normal
        sehingga halaman bisa di-scroll saat layarnya pendek. Ini beda dengan
        overflow-x-hidden, yang justru memaksa sumbu Y jadi auto dan bikin
        div ini punya scrollbar sendiri.
    --}}
    <div class="relative flex min-h-screen flex-col overflow-x-clip">

        {{-- ================= LATAR ================= --}}
        <span aria-hidden="true"
              class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_110%_75%_at_50%_0%,#0f4a3d_0%,var(--emerald)_58%,#03201a_100%)]"></span>

        {{-- Geometri Islam samar --}}
        <x-include.pattern variant="star" class="text-[var(--gold)]" opacity="0.06" />

        {{-- Cahaya hangat dari atas, seolah dari kubah --}}
        <span aria-hidden="true"
              class="pointer-events-none absolute left-1/2 top-0 h-[560px] w-[560px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-[var(--gold)] opacity-[0.14] blur-[120px]"></span>

        {{-- ================= BAR ATAS ================= --}}
        <div class="relative z-30 flex items-center justify-between px-5 py-4 sm:px-8">
            <a href="{{ route('home') }}"
               class="group inline-flex items-center gap-2 rounded-full border border-[var(--gold)]/25 bg-white/5 px-4 py-2 text-xs font-medium text-white/75 backdrop-blur-sm transition hover:border-[var(--gold)] hover:bg-[var(--gold)] hover:text-[var(--emerald)]">
                <i class="bx bx-home-alt text-base transition-transform group-hover:-translate-x-0.5" aria-hidden="true"></i>
                Kembali ke Beranda
            </a>

            {{-- Jam berjalan. Isi awalnya dari server supaya tidak kosong
                 kalau JS mati; JS lalu menggantinya dengan waktu lokal. --}}
            <div class="flex items-center gap-2 rounded-full border border-[var(--gold)]/25 bg-white/5 px-4 py-2 backdrop-blur-sm">
                <i class="bx bx-time-five text-base text-[var(--gold)]" aria-hidden="true"></i>
                <span data-clock class="font-mono text-xs tabular-nums text-white/85">{{ $now->format('H:i:s') }}</span>
            </div>
        </div>

        {{-- ================= GERBANG =================
             pb dikecilkan di HP: tiap px vertikal berharga di layar pendek. --}}
        <div class="relative z-10 flex flex-1 items-center justify-center px-5 pb-4 sm:pb-8">
            <div class="w-full max-w-[23rem]">

                <x-include.gate-roof />

                {{--
                    BADAN GERBANG.
                    Pembungkus luar = emas (pilar & bingkai lewat px-[5%]).
                    Panel dalam = hijau tua, dengan tympanum emas berkerawang
                    sebagai anak pertamanya -- bukaan lengkung bawangnya
                    transparan, jadi panel hijau terlihat menembusnya, dan
                    konten ditarik naik (-mt) supaya duduk DI DALAM bukaan.
                --}}
                {{-- -mt-4: kotak diselipkan lebih dalam ke bawah atap. Atap
                     ber-z-10 jadi tepi atas kotak tertutup tritisan -- itu yang
                     bikin atap terbaca MENAUNGI, bukan sekadar bertumpu. --}}
                <div class="gate-body relative -mt-4 rounded-b-xl bg-gradient-to-b from-[var(--gold)] to-[var(--gold-deep)]
                            px-[5%] pb-[5%] shadow-[0_40px_90px_-25px_rgba(0,0,0,0.8)]">

                    <div class="relative overflow-hidden bg-gradient-to-b from-[#0B4237] to-[#052823]">

                        <x-include.gate-tympanum />

                        {{-- Konten ditarik naik supaya duduk DI DALAM bukaan
                             lengkung. Besarannya dihitung, bukan dikira-kira:
                             pada -mt-12 teks Arab jatuh di ketinggian yang
                             bukaannya ~230px, cukup lega untuk teks ~150px.
                             Ditarik lebih jauh (mis. -mt-16) teksnya masuk ke
                             bagian lengkung yang sudah menyempit. --}}
                        <div class="relative -mt-12 px-5 pb-7 sm:px-6">

                        {{-- Sambutan Arab, duduk di dalam bukaan lengkung --}}
                        <div class="text-center">
                            <p dir="rtl" lang="ar" class="font-serif text-3xl leading-snug text-[var(--gold-bright)]">أَهْلًا وَسَهْلًا</p>
                            <p class="mt-1.5 text-[10px] font-medium uppercase tracking-[0.3em] text-[var(--gold-soft)]/70">Ahlan wa Sahlan</p>
                        </div>

                        {{-- Pemisah: pucuk rebung (motif songket Nusantara) diapit
                             garis emas yang memudar ke tepi -- memisahkan blok
                             sambutan dari blok salam tanpa terasa memotong. --}}
                        <div class="my-4 flex items-center justify-center gap-3" aria-hidden="true">
                            <span class="h-px w-12 bg-gradient-to-r from-transparent to-[var(--gold)]/45"></span>

                            <svg viewBox="0 0 120 9" class="h-2 w-14 shrink-0" focusable="false" preserveAspectRatio="none">
                                <defs>
                                    <pattern id="pucuk" width="20" height="9" patternUnits="userSpaceOnUse">
                                        <polygon points="10,0 20,9 0,9" fill="var(--gold)" opacity="0.8" />
                                    </pattern>
                                </defs>
                                <rect width="120" height="9" fill="url(#pucuk)" />
                            </svg>

                            <span class="h-px w-12 bg-gradient-to-l from-transparent to-[var(--gold)]/45"></span>
                        </div>

                        <div class="mb-5 text-center">
                            <p data-greeting class="text-[11px] font-semibold uppercase tracking-[0.2em] text-[var(--gold)]">
                                {{ $greeting }}
                            </p>
                            <h1 class="mt-1 font-serif text-2xl font-bold text-white">Masuk ke CMS</h1>
                            <p class="mt-1 text-xs text-white/50">Kelola konten &amp; halaman travel Anda</p>
                        </div>

                        @if ($errors->any())
                            <div class="mb-4 rounded-lg border border-red-400/30 bg-red-500/10 px-3.5 py-2.5">
                                @foreach ($errors->all() as $error)
                                    <p class="flex items-center gap-1.5 text-xs text-red-300">
                                        <i class="bx bx-error-circle shrink-0" aria-hidden="true"></i>
                                        {{ $error }}
                                    </p>
                                @endforeach
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}" class="space-y-3.5">
                            @csrf

                            <div class="field-anim" style="--d: 680ms">
                                <label for="email" class="mb-1.5 block text-[11px] font-medium uppercase tracking-wider text-white/55">Email</label>
                                <div class="relative">
                                    <i class="bx bx-envelope pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-base text-[var(--gold)]/70" aria-hidden="true"></i>
                                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                                           autocomplete="username"
                                           class="w-full rounded-lg border border-[var(--gold)]/30 bg-white/5 py-2.5 pl-10 pr-3.5 text-sm text-white
                                                  placeholder:text-white/35 transition
                                                  focus:border-[var(--gold)] focus:bg-white/8 focus:outline-none focus:ring-2 focus:ring-[var(--gold)]/25"
                                           placeholder="nama@perusahaan.com">
                                </div>
                            </div>

                            <div class="field-anim" style="--d: 760ms">
                                <label for="password" class="mb-1.5 block text-[11px] font-medium uppercase tracking-wider text-white/55">Kata Sandi</label>
                                <div class="relative">
                                    <i class="bx bx-lock-alt pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-base text-[var(--gold)]/70" aria-hidden="true"></i>
                                    {{-- pr-11 memberi ruang untuk tombol mata di kanan --}}
                                    <input id="password" name="password" type="password" required
                                           autocomplete="current-password"
                                           class="w-full rounded-lg border border-[var(--gold)]/30 bg-white/5 py-2.5 pl-10 pr-11 text-sm text-white
                                                  placeholder:text-white/35 transition
                                                  focus:border-[var(--gold)] focus:bg-white/8 focus:outline-none focus:ring-2 focus:ring-[var(--gold)]/25"
                                           placeholder="••••••••">

                                    {{--
                                        Tombol lihat kata sandi.

                                        Disembunyikan (hidden) secara bawaan dan baru ditampilkan
                                        oleh JS: fungsinya 100% bergantung pada JS, jadi kalau JS
                                        mati tombolnya tidak boleh tetap muncul dan diam saja saat
                                        ditekan -- itu afordans palsu.

                                        <button type="button"> WAJIB: tanpa type, tombol di dalam
                                        form defaultnya submit -- menekannya akan mengirim form,
                                        bukan menampilkan kata sandi.
                                    --}}
                                    <button type="button" data-toggle-password hidden
                                            aria-controls="password" aria-pressed="false"
                                            aria-label="Tampilkan kata sandi"
                                            class="absolute right-2 top-1/2 flex h-8 w-8 -translate-y-1/2 items-center justify-center rounded-md
                                                   text-[var(--gold)]/70 transition hover:bg-white/10 hover:text-[var(--gold)]">
                                        <i class="bx bx-show text-base" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>

                            {{-- Tombol emas dengan teks gelap: kontras tertinggi
                                 di halaman ini, jadi mata langsung jatuh ke sini. --}}
                            <button type="submit"
                                    class="field-anim group relative mt-1 flex w-full items-center justify-center gap-2 overflow-hidden
                                           rounded-lg bg-gradient-to-b from-[var(--gold-bright)] to-[var(--gold)] py-3
                                           text-sm font-bold text-[var(--emerald)] shadow-lg shadow-black/30 transition
                                           hover:brightness-110"
                                    style="--d: 900ms">
                                <span aria-hidden="true"
                                      class="absolute inset-0 -translate-x-full bg-gradient-to-r from-transparent via-white/45 to-transparent transition-transform duration-700 group-hover:translate-x-full"></span>
                                <span class="relative">Masuk</span>
                                <i class="bx bx-right-arrow-alt relative text-lg transition-transform group-hover:translate-x-1" aria-hidden="true"></i>
                            </button>
                        </form>
                        </div> {{-- /konten (yang ditarik naik ke bukaan) --}}
                    </div> {{-- /panel hijau --}}
                </div> {{-- /pembungkus emas --}}

                {{-- Umpak: alas bertingkat yang menahan bangunan --}}
                <div class="gate-body mx-auto h-2 w-[94%] rounded-b-lg bg-gradient-to-b from-[var(--gold)] to-[var(--gold-deep)]"></div>
                <div class="gate-body mx-auto h-1.5 w-[80%] rounded-b-md bg-[var(--gold)]/30"></div>

                <p class="gate-body mt-5 text-center text-[11px] text-white/35">
                    Lupa kata sandi? Hubungi admin client Anda.
                </p>
            </div>
        </div>

        {{-- ================= AWAN FOREGROUND ================= --}}
        {{-- Ditaruh paling akhir & z-20: menimpa bagian bawah gerbang,
             sesuai spek. pointer-events-none ada di komponennya. --}}
        <x-include.mega-mendung-corner side="left" />
        <x-include.mega-mendung-corner side="right" />
    </div>

    {{--
        Jam & salam. Formulir TIDAK bergantung pada skrip ini: tanpa JS,
        keduanya tetap tampil dari render server (WIB) dan login tetap jalan.
        Sengaja inline: ~20 baris, khusus halaman ini, tidak perlu 1 request
        tambahan hanya untuk itu.
    --}}
    <script>
        // --- Tombol lihat kata sandi ---
        (function () {
            var btn = document.querySelector('[data-toggle-password]');
            var input = document.getElementById('password');
            if (!btn || !input) return;

            var icon = btn.querySelector('i');

            // Baru ditampilkan di sini: kalau skrip ini gagal dimuat, tombolnya
            // tetap tersembunyi dan tidak jadi tombol mati yang membingungkan.
            btn.hidden = false;

            btn.addEventListener('click', function () {
                var willShow = input.type === 'password';

                input.type = willShow ? 'text' : 'password';
                icon.className = 'bx ' + (willShow ? 'bx-hide' : 'bx-show') + ' text-base';
                btn.setAttribute('aria-pressed', willShow ? 'true' : 'false');
                btn.setAttribute('aria-label', willShow ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');

                // Kembalikan fokus ke input supaya alur mengetik tidak terputus
                // setelah menekan tombol.
                input.focus();
            });
        })();

        // --- Jam & salam ---
        (function () {
            var clock = document.querySelector('[data-clock]');
            var greet = document.querySelector('[data-greeting]');
            if (!clock && !greet) return;

            function pad(n) { return n < 10 ? '0' + n : '' + n; }

            function greetingFor(h) {
                if (h >= 4 && h < 11) return 'Selamat Pagi';
                if (h >= 11 && h < 15) return 'Selamat Siang';
                if (h >= 15 && h < 18) return 'Selamat Sore';
                return 'Selamat Malam';
            }

            function tick() {
                var d = new Date();
                if (clock) clock.textContent = pad(d.getHours()) + ':' + pad(d.getMinutes()) + ':' + pad(d.getSeconds());
                if (greet) greet.textContent = greetingFor(d.getHours());
            }

            tick();
            setInterval(tick, 1000);
        })();
    </script>

</body>
</html>
