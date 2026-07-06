<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Super Admin — {{ config('app.name', 'CMS') }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-[#10151B] text-[#D8DEE4] antialiased">

    <div class="min-h-screen flex items-center justify-center px-6 py-12">
        <div class="w-full max-w-sm">

            <div class="flex items-center gap-2 justify-center mb-8">
                <span class="w-1.5 h-1.5 rounded-full bg-[#B8963E]"></span>
                <span class="font-mono text-xs tracking-[0.2em] uppercase text-[#8A94A0]">Panel Monitoring</span>
            </div>

            <div class="bg-[#161C24] rounded-xl border border-[#232B35] overflow-hidden">
                <div class="px-7 pt-7 pb-8">
                    <h1 class="font-mono text-lg text-white mb-1">super_admin.login()</h1>
                    <p class="text-sm text-[#8A94A0] mb-6">Akses seluruh client dari satu tempat</p>

                    @if ($errors->any())
                        <div class="mb-5 rounded-lg bg-red-950/40 border border-red-900/50 px-4 py-3">
                            @foreach ($errors->all() as $error)
                                <p class="text-sm text-red-300">{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('superadmin.login') }}" class="space-y-4">
                        @csrf

                        <div>
                            <label for="email" class="block text-xs font-mono uppercase tracking-wide text-[#8A94A0] mb-1.5">Email</label>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                                   class="w-full rounded-lg bg-[#0E1319] border border-[#232B35] px-3.5 py-2.5 text-sm text-white
                                          focus:outline-none focus:ring-2 focus:ring-[#B8963E]/30 focus:border-[#B8963E]"
                                   placeholder="superadmin@yourcompany.com">
                        </div>

                        <div>
                            <label for="password" class="block text-xs font-mono uppercase tracking-wide text-[#8A94A0] mb-1.5">Kata Sandi</label>
                            <input id="password" name="password" type="password" required
                                   class="w-full rounded-lg bg-[#0E1319] border border-[#232B35] px-3.5 py-2.5 text-sm text-white
                                          focus:outline-none focus:ring-2 focus:ring-[#B8963E]/30 focus:border-[#B8963E]"
                                   placeholder="••••••••">
                        </div>

                        <label class="flex items-center gap-2 text-xs text-[#8A94A0]">
                            <input type="checkbox" name="remember" class="rounded border-[#232B35] bg-[#0E1319] text-[#B8963E] focus:ring-[#B8963E]/30">
                            Ingat saya di perangkat ini
                        </label>

                        <button type="submit"
                                class="w-full rounded-lg bg-[#B8963E] text-[#10151B] text-sm font-medium py-2.5
                                       hover:bg-[#CBA84A] transition-colors">
                            Masuk ke Panel
                        </button>
                    </form>
                </div>

                <div class="border-t border-[#232B35] px-7 py-3.5">
                    <p class="text-xs font-mono text-[#5C6570]">guard: super_admin · schema: public</p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
