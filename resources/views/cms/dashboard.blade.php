<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard CMS</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-[#F7F3EC] text-[#1C2521] antialiased">
    <div class="max-w-2xl mx-auto px-6 py-16">
        <p class="text-xs font-mono uppercase tracking-wide text-[#8B9490] mb-2">Login berhasil</p>
        <h1 class="font-serif text-2xl text-[#0F3D3E] mb-4">
            Halo, {{ auth()->user()->name }} <span class="text-[#8B9490] text-base font-sans">({{ auth()->user()->role }})</span>
        </h1>
        <p class="text-sm text-[#5B6663] mb-8">
            Ini placeholder — dashboard section-builder sungguhan menyusul di Fase 3 (lihat SPRINT_PLAN.md Hari 2-3).
        </p>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm text-[#0F3D3E] underline">Keluar</button>
        </form>
    </div>
</body>
</html>
