<div class="max-w-5xl mx-auto px-6 py-10">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="font-serif text-2xl text-[#0F3D3E]">Paket</h1>
            <p class="text-sm text-[#5B6663]">Kelola paket umrah, haji, dan wisata religi</p>
        </div>
        <a href="{{ route('cms.packages.create') }}" class="rounded-lg bg-[#0F3D3E] text-white text-sm font-medium px-4 py-2.5 hover:bg-[#0C3132]">
            + Paket Baru
        </a>
    </div>

    @if (session('status'))
        <div class="mb-5 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3">
            {{ session('status') }}
        </div>
    @endif

    <div class="flex gap-3 mb-5">
        <input type="text" wire:model.live.debounce.400ms="search" placeholder="Cari nama paket..."
               class="flex-1 rounded-lg border border-[#DDD6C7] px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">

        <select wire:model.live="typeFilter"
                class="rounded-lg border border-[#DDD6C7] px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
            <option value="">Semua Tipe</option>
            <option value="umroh">Umrah</option>
            <option value="haji">Haji</option>
            <option value="wisata_religi">Wisata Religi</option>
        </select>
    </div>

    <div class="bg-white rounded-xl border border-[#EDE7D8] divide-y divide-[#EDE7D8]">
        @forelse ($packages as $package)
            <div class="flex items-center justify-between px-5 py-4">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs px-2 py-0.5 rounded-full bg-[#F7F3EC] text-[#5B6663] font-medium uppercase">
                            {{ str_replace('_', ' ', $package->type) }}
                        </span>
                        <p class="text-sm font-medium text-[#1C2521]">{{ $package->name }}</p>
                    </div>
                    <p class="text-xs text-[#8B9490] mt-1">
                        {{ $package->duration ?: '-' }}
                        @if ($package->price_from)
                            &middot; {{ $package->price_currency }} {{ number_format($package->price_from, 0, ',', '.') }}
                        @endif
                        @if ($package->departure_date)
                            &middot; {{ $package->departure_date->translatedFormat('d M Y') }}
                        @elseif ($package->departure_date_note)
                            &middot; {{ $package->departure_date_note }}
                        @endif
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <button wire:click="togglePublish('{{ $package->id }}')"
                            class="text-xs px-2.5 py-1 rounded-full font-medium
                                   {{ $package->is_published ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                        {{ $package->is_published ? 'Published' : 'Draft' }}
                    </button>

                    <a href="{{ route('cms.packages.edit', $package) }}" class="text-sm text-[#5B6663] hover:underline">Edit</a>
                    <button wire:click="delete('{{ $package->id }}')" wire:confirm="Hapus paket ini?" class="text-sm text-red-500 hover:underline">Hapus</button>
                </div>
            </div>
        @empty
            <p class="text-sm text-[#8B9490] px-5 py-8 text-center">Belum ada paket. Buat yang pertama.</p>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $packages->links() }}
    </div>
</div>
