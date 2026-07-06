<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk — {{ config('app.name', 'CMS') }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-[#F7F3EC] text-[#1C2521] antialiased">

    <div class="min-h-screen flex items-center justify-center px-6 py-12">
        <div class="w-full max-w-md">

            <!-- Signature element: siluet lengkung tunggal, motif mihrab yang disederhanakan -->
            <div class="flex justify-center mb-8" aria-hidden="true">
                <svg width="120" height="60" viewBox="0 0 120 60" fill="none">
                    <path d="M4 58 V32 C4 14 20 4 60 4 C100 4 116 14 116 32 V58"
                          stroke="#C9A227" stroke-width="2" fill="none"/>
                    <path d="M18 58 V34 C18 20 32 12 60 12 C88 12 102 20 102 34 V58"
                          stroke="#0F3D3E" stroke-width="1.5" fill="none" opacity="0.5"/>
                </svg>
            </div>

            <div class="bg-white rounded-2xl shadow-[0_1px_3px_rgba(0,0,0,0.06),0_20px_40px_-15px_rgba(15,61,62,0.15)] overflow-hidden">
                <div class="h-1.5 bg-[#C9A227]"></div>

                <div class="px-8 pt-8 pb-9">
                    <h1 class="font-serif text-2xl text-[#0F3D3E] mb-1">Masuk ke CMS</h1>
                    <p class="text-sm text-[#5B6663] mb-7">Kelola konten & halaman travel Anda</p>

                    @if ($errors->any())
                        <div class="mb-5 rounded-lg bg-red-50 border border-red-100 px-4 py-3">
                            @foreach ($errors->all() as $error)
                                <p class="text-sm text-red-700">{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf

                        <div>
                            <label for="email" class="block text-sm font-medium text-[#33403C] mb-1.5">Email</label>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                                   class="w-full rounded-lg border border-[#DDD6C7] px-3.5 py-2.5 text-sm
                                          focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20 focus:border-[#0F3D3E]"
                                   placeholder="nama@perusahaan.com">
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-[#33403C] mb-1.5">Kata Sandi</label>
                            <input id="password" name="password" type="password" required
                                   class="w-full rounded-lg border border-[#DDD6C7] px-3.5 py-2.5 text-sm
                                          focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20 focus:border-[#0F3D3E]"
                                   placeholder="••••••••">
                        </div>

                        <label class="flex items-center gap-2 text-sm text-[#5B6663]">
                            <input type="checkbox" name="remember" class="rounded border-[#DDD6C7] text-[#0F3D3E] focus:ring-[#0F3D3E]/30">
                            Ingat saya
                        </label>

                        <button type="submit"
                                class="w-full rounded-lg bg-[#0F3D3E] text-white text-sm font-medium py-2.5
                                       hover:bg-[#0C3132] transition-colors">
                            Masuk
                        </button>
                    </form>
                </div>
            </div>

            <p class="text-center text-xs text-[#8B9490] mt-6">
                Lupa kata sandi? Hubungi admin client Anda.
            </p>
        </div>
    </div>

</body>
</html>
