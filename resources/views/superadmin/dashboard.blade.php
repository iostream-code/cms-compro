<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panel Monitoring</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-[#10151B] text-[#D8DEE4] antialiased">
    <div class="max-w-2xl mx-auto px-6 py-16">
        <p class="text-xs font-mono uppercase tracking-wide text-[#8A94A0] mb-2">Login berhasil</p>
        <h1 class="font-mono text-2xl text-white mb-4">
            {{ auth('super_admin')->user()->name }}
        </h1>
        <p class="text-sm text-[#8A94A0] mb-8">
            Placeholder — UI dashboard monitoring sungguhan (list client, activity feed) menyusul di Fase 6 / Hari 7.
            Endpoint data-nya sudah jalan: <code class="text-[#B8963E]">{{ route('superadmin.dashboard.summary') }}</code>
        </p>
        <form method="POST" action="{{ route('superadmin.logout') }}">
            @csrf
            <button type="submit" class="text-sm text-[#B8963E] underline">Keluar</button>
        </form>
    </div>
</body>
</html>
