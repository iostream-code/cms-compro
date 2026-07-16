<div class="max-w-4xl mx-auto px-6 py-10">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="font-serif text-2xl text-[#0F3D3E]">Artikel</h1>
            <p class="text-sm text-[#5B6663]">Kelola berita & artikel edukatif</p>
        </div>
        <button wire:click="create" class="rounded-lg bg-[#0F3D3E] text-white text-sm font-medium px-4 py-2.5 hover:bg-[#0C3132]">
            + Artikel Baru
        </button>
    </div>

    <input type="text" wire:model.live.debounce.400ms="search" placeholder="Cari artikel..."
           class="w-full mb-5 rounded-lg border border-[#DDD6C7] px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">

    @if ($showForm)
        <div class="fixed inset-0 bg-black/30 flex items-center justify-center z-50 px-4 py-8" wire:click.self="$set('showForm', false)">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-lg p-6 max-h-[90vh] overflow-y-auto">
                <h2 class="font-serif text-lg text-[#0F3D3E] mb-4">{{ $editingId ? 'Edit Artikel' : 'Artikel Baru' }}</h2>

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

                    <div>
                        <label class="block text-sm font-medium text-[#33403C] mb-1.5">Kategori</label>
                        <input type="text" wire:model="category" placeholder="mis. Info Umroh"
                               class="w-full rounded-lg border border-[#DDD6C7] px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#33403C] mb-1.5">Ringkasan</label>
                        <input type="text" wire:model="excerpt"
                               class="w-full rounded-lg border border-[#DDD6C7] px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#33403C] mb-1.5">Isi Artikel</label>
                        <textarea wire:model="body" rows="6"
                                  class="w-full rounded-lg border border-[#DDD6C7] px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20"></textarea>
                        @error('body') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#33403C] mb-1.5">Gambar Utama (URL)</label>
                        <input type="text" wire:model="featured_image_url"
                               class="w-full rounded-lg border border-[#DDD6C7] px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
                    </div>

                    <label class="flex items-center gap-2 text-sm text-[#33403C]">
                        <input type="checkbox" wire:model="is_published" class="rounded border-[#DDD6C7]">
                        Publikasikan artikel ini
                    </label>

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

    <div class="bg-white rounded-xl border border-[#EDE7D8] divide-y divide-[#EDE7D8]">
        @forelse ($articles as $article)
            <div class="flex items-center justify-between px-5 py-4">
                <div>
                    <p class="text-sm font-medium text-[#1C2521]">{{ $article->title }}</p>
                    <p class="text-xs text-[#8B9490]">
                        {{ $article->category ?: 'Tanpa kategori' }}
                        @if ($article->published_at)
                            &middot; {{ $article->published_at->translatedFormat('d M Y') }}
                        @endif
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <button wire:click="togglePublish('{{ $article->id }}')"
                            class="text-xs px-2.5 py-1 rounded-full font-medium
                                   {{ $article->is_published ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                        {{ $article->is_published ? 'Published' : 'Draft' }}
                    </button>

                    <button wire:click="edit('{{ $article->id }}')" class="text-sm text-[#5B6663] hover:underline">Edit</button>
                    <button wire:click="delete('{{ $article->id }}')" wire:confirm="Hapus artikel ini?" class="text-sm text-red-500 hover:underline">Hapus</button>
                </div>
            </div>
        @empty
            <p class="text-sm text-[#8B9490] px-5 py-8 text-center">Belum ada artikel. Buat yang pertama.</p>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $articles->links() }}
    </div>
</div>
