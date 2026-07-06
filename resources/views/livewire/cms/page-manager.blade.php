<div class="max-w-4xl mx-auto px-6 py-10">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="font-serif text-2xl text-[#0F3D3E]">Halaman</h1>
            <p class="text-sm text-[#5B6663]">Kelola halaman statis situs Anda</p>
        </div>
        <button wire:click="create" class="rounded-lg bg-[#0F3D3E] text-white text-sm font-medium px-4 py-2.5 hover:bg-[#0C3132]">
            + Halaman Baru
        </button>
    </div>

    <input type="text" wire:model.live.debounce.400ms="search" placeholder="Cari halaman..."
           class="w-full mb-5 rounded-lg border border-[#DDD6C7] px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">

    {{-- Modal form create/edit --}}
    @if ($showForm)
        <div class="fixed inset-0 bg-black/30 flex items-center justify-center z-50 px-4" wire:click.self="$set('showForm', false)">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">
                <h2 class="font-serif text-lg text-[#0F3D3E] mb-4">{{ $editingId ? 'Edit Halaman' : 'Halaman Baru' }}</h2>

                <form wire:submit="save" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-[#33403C] mb-1.5">Judul</label>
                        <input type="text" wire:model.live="title"
                               class="w-full rounded-lg border border-[#DDD6C7] px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
                        @error('title') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#33403C] mb-1.5">Slug (URL)</label>
                        <input type="text" wire:model="slug"
                               class="w-full rounded-lg border border-[#DDD6C7] px-3.5 py-2.5 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
                        @error('slug') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" wire:click="$set('showForm', false)" class="text-sm text-[#5B6663] px-4 py-2">
                            Batal
                        </button>
                        <button type="submit" class="rounded-lg bg-[#0F3D3E] text-white text-sm font-medium px-4 py-2 hover:bg-[#0C3132]">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- List halaman --}}
    <div class="bg-white rounded-xl border border-[#EDE7D8] divide-y divide-[#EDE7D8]">
        @forelse ($pages as $page)
            <div class="flex items-center justify-between px-5 py-4">
                <div>
                    <p class="text-sm font-medium text-[#1C2521]">{{ $page->title }}</p>
                    <p class="text-xs text-[#8B9490] font-mono">/{{ $page->slug }}</p>
                </div>

                <div class="flex items-center gap-3">
                    <button wire:click="togglePublish('{{ $page->id }}')"
                            class="text-xs px-2.5 py-1 rounded-full font-medium
                                   {{ $page->is_published ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                        {{ $page->is_published ? 'Published' : 'Draft' }}
                    </button>

                    <a href="{{ route('cms.sections.index', $page) }}" class="text-sm text-[#0F3D3E] hover:underline">Susun Section</a>
                    <button wire:click="edit('{{ $page->id }}')" class="text-sm text-[#5B6663] hover:underline">Edit</button>
                    <button wire:click="delete('{{ $page->id }}')" wire:confirm="Hapus halaman ini?" class="text-sm text-red-500 hover:underline">Hapus</button>
                </div>
            </div>
        @empty
            <p class="text-sm text-[#8B9490] px-5 py-8 text-center">Belum ada halaman. Buat yang pertama.</p>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $pages->links() }}
    </div>
</div>
