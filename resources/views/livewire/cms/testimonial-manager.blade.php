<div class="max-w-4xl mx-auto px-6 py-10">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="font-serif text-2xl text-[#0F3D3E]">Testimoni</h1>
            <p class="text-sm text-[#5B6663]">Kelola testimoni jamaah</p>
        </div>
        <button wire:click="create" class="rounded-lg bg-[#0F3D3E] text-white text-sm font-medium px-4 py-2.5 hover:bg-[#0C3132]">
            + Testimoni Baru
        </button>
    </div>

    @if ($showForm)
        <div class="fixed inset-0 bg-black/30 flex items-center justify-center z-50 px-4 py-8" wire:click.self="$set('showForm', false)">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-lg p-6 max-h-[90vh] overflow-y-auto">
                <h2 class="font-serif text-lg text-[#0F3D3E] mb-4">{{ $editingId ? 'Edit Testimoni' : 'Testimoni Baru' }}</h2>

                <form wire:submit="save" class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-[#33403C] mb-1.5">Nama Jamaah</label>
                            <input type="text" wire:model="jamaah_name"
                                   class="w-full rounded-lg border border-[#DDD6C7] px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
                            @error('jamaah_name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[#33403C] mb-1.5">Kota</label>
                            <input type="text" wire:model="jamaah_city"
                                   class="w-full rounded-lg border border-[#DDD6C7] px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#33403C] mb-1.5">Foto (URL)</label>
                        <input type="text" wire:model="jamaah_photo_url"
                               class="w-full rounded-lg border border-[#DDD6C7] px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-[#33403C] mb-1.5">Tipe Paket</label>
                            <select wire:model="package_type"
                                    class="w-full rounded-lg border border-[#DDD6C7] px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
                                <option value="">-</option>
                                <option value="umroh">Umrah</option>
                                <option value="haji">Haji</option>
                                <option value="wisata_religi">Wisata Religi</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[#33403C] mb-1.5">Tahun</label>
                            <input type="number" wire:model="year"
                                   class="w-full rounded-lg border border-[#DDD6C7] px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[#33403C] mb-1.5">Rating (1-5)</label>
                            <input type="number" min="1" max="5" wire:model="rating"
                                   class="w-full rounded-lg border border-[#DDD6C7] px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#33403C] mb-1.5">Isi Testimoni</label>
                        <textarea wire:model="content" rows="4"
                                  class="w-full rounded-lg border border-[#DDD6C7] px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20"></textarea>
                        @error('content') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <label class="flex items-center gap-2 text-sm text-[#33403C]">
                        <input type="checkbox" wire:model="is_published" class="rounded border-[#DDD6C7]">
                        Publikasikan testimoni ini
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
        @forelse ($testimonials as $testimonial)
            <div class="flex items-center justify-between px-5 py-4">
                <div>
                    <p class="text-sm font-medium text-[#1C2521]">{{ $testimonial->jamaah_name }} <span class="text-[#8B9490] font-normal">{{ $testimonial->jamaah_city ? '- ' . $testimonial->jamaah_city : '' }}</span></p>
                    <p class="text-xs text-[#8B9490] mt-0.5">{{ str($testimonial->content)->limit(80) }}</p>
                </div>

                <div class="flex items-center gap-3">
                    <button wire:click="togglePublish({{ $testimonial->id }})"
                            class="text-xs px-2.5 py-1 rounded-full font-medium
                                   {{ $testimonial->is_published ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                        {{ $testimonial->is_published ? 'Published' : 'Draft' }}
                    </button>

                    <button wire:click="edit({{ $testimonial->id }})" class="text-sm text-[#5B6663] hover:underline">Edit</button>
                    <button wire:click="delete({{ $testimonial->id }})" wire:confirm="Hapus testimoni ini?" class="text-sm text-red-500 hover:underline">Hapus</button>
                </div>
            </div>
        @empty
            <p class="text-sm text-[#8B9490] px-5 py-8 text-center">Belum ada testimoni. Buat yang pertama.</p>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $testimonials->links() }}
    </div>
</div>
