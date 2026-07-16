<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'CMS' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="min-h-screen bg-[#F7F3EC] text-[#1C2521] antialiased">

    @auth
        <nav class="bg-white border-b border-[#EDE7D8] px-6 py-3.5 flex items-center justify-between">
            <div class="flex items-center gap-6">
                <span class="font-serif text-[#0F3D3E] font-medium">CMS</span>
                <a href="{{ route('cms.dashboard') }}" class="text-sm text-[#5B6663] hover:text-[#0F3D3E]">Dashboard</a>
                <a href="{{ route('cms.pages.index') }}" class="text-sm text-[#5B6663] hover:text-[#0F3D3E]">Halaman</a>
                <a href="{{ route('cms.packages.index') }}" class="text-sm text-[#5B6663] hover:text-[#0F3D3E]">Paket</a>
                <a href="{{ route('cms.articles.index') }}" class="text-sm text-[#5B6663] hover:text-[#0F3D3E]">Artikel</a>
                <a href="{{ route('cms.testimonials.index') }}" class="text-sm text-[#5B6663] hover:text-[#0F3D3E]">Testimoni</a>
                @if (auth()->user()->role === 'admin')
                    <a href="{{ route('cms.settings.edit') }}"
                        class="text-sm text-[#5B6663] hover:text-[#0F3D3E]">Pengaturan</a>
                @endif
            </div>

            <div class="flex items-center gap-4">
                <span class="text-xs text-[#8B9490]">{{ auth()->user()->name }} ({{ auth()->user()->role }})</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-[#0F3D3E] hover:underline">Keluar</button>
                </form>
            </div>
        </nav>
    @endauth

    <main>
        {{ $slot }}
    </main>

    @livewireScripts
</body>

</html>
