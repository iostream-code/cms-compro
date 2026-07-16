<div class="max-w-4xl mx-auto px-6 py-10">
    <div class="mb-8">
        <p class="text-xs font-mono uppercase tracking-wide text-[#8B9490] mb-1">Dashboard</p>
        <h1 class="font-serif text-2xl text-[#0F3D3E]">
            Halo, {{ auth()->user()->name }} <span class="text-[#8B9490] text-base font-sans">({{ auth()->user()->role }})</span>
        </h1>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <a href="{{ route('cms.pages.index') }}" class="bg-white rounded-xl border border-[#EDE7D8] p-5 hover:border-[#0F3D3E]/30">
            <p class="text-2xl font-serif text-[#0F3D3E]">{{ $pageCount }}</p>
            <p class="text-sm text-[#5B6663] mt-1">Halaman</p>
        </a>
        <a href="{{ route('cms.packages.index') }}" class="bg-white rounded-xl border border-[#EDE7D8] p-5 hover:border-[#0F3D3E]/30">
            <p class="text-2xl font-serif text-[#0F3D3E]">{{ $packageCount }}</p>
            <p class="text-sm text-[#5B6663] mt-1">Paket</p>
        </a>
        <a href="{{ route('cms.articles.index') }}" class="bg-white rounded-xl border border-[#EDE7D8] p-5 hover:border-[#0F3D3E]/30">
            <p class="text-2xl font-serif text-[#0F3D3E]">{{ $articleCount }}</p>
            <p class="text-sm text-[#5B6663] mt-1">Artikel</p>
        </a>
        <a href="{{ route('cms.testimonials.index') }}" class="bg-white rounded-xl border border-[#EDE7D8] p-5 hover:border-[#0F3D3E]/30">
            <p class="text-2xl font-serif text-[#0F3D3E]">{{ $testimonialCount }}</p>
            <p class="text-sm text-[#5B6663] mt-1">Testimoni</p>
        </a>
    </div>

    <div class="bg-white rounded-xl border border-[#EDE7D8] p-5">
        <h2 class="font-serif text-base text-[#0F3D3E] mb-3">Mulai Cepat</h2>
        <ul class="space-y-2 text-sm">
            <li><a href="{{ route('cms.packages.create') }}" class="text-[#0F3D3E] hover:underline">+ Buat paket umrah/haji baru</a></li>
            <li><a href="{{ route('cms.pages.index') }}" class="text-[#0F3D3E] hover:underline">+ Kelola halaman & section</a></li>
            @if (auth()->user()->role === 'admin')
                <li><a href="{{ route('cms.settings.edit') }}" class="text-[#0F3D3E] hover:underline">+ Atur identitas & kontak perusahaan</a></li>
            @endif
        </ul>
    </div>
</div>
