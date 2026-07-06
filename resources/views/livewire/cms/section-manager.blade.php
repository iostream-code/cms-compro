<div class="max-w-3xl mx-auto px-6 py-10" x-data>
    <div class="flex items-center justify-between mb-2">
        <div>
            <a href="{{ route('cms.pages.index') }}" class="text-xs text-[#8B9490] hover:underline">&larr; Kembali ke Halaman</a>
            <h1 class="font-serif text-2xl text-[#0F3D3E] mt-1">Susun Section: {{ $page->title }}</h1>
        </div>
        <button wire:click="$set('showTypePicker', true)"
                class="rounded-lg bg-[#0F3D3E] text-white text-sm font-medium px-4 py-2.5 hover:bg-[#0C3132]">
            + Tambah Section
        </button>
    </div>
    <p class="text-sm text-[#5B6663] mb-6">Drag untuk mengubah urutan tampil di halaman.</p>

    {{-- Modal pilih tipe section --}}
    @if ($showTypePicker)
        <div class="fixed inset-0 bg-black/30 flex items-center justify-center z-50 px-4" wire:click.self="$set('showTypePicker', false)">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-lg p-6 max-h-[80vh] overflow-y-auto">
                <h2 class="font-serif text-lg text-[#0F3D3E] mb-4">Pilih Tipe Section</h2>
                <div class="grid grid-cols-2 gap-3">
                    @foreach ($availableTypes as $type)
                        <button wire:click="addSection('{{ $type->type_key }}')"
                                class="text-left rounded-lg border border-[#EDE7D8] p-3.5 hover:border-[#0F3D3E] hover:bg-[#F7F3EC] transition-colors">
                            <p class="text-sm font-medium text-[#1C2521]">{{ $type->label }}</p>
                            <p class="text-xs text-[#8B9490] mt-0.5">{{ $type->description }}</p>
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- List section, draggable --}}
    <div id="section-list" class="space-y-2">
        @forelse ($sections as $section)
            <div wire:key="section-{{ $section->id }}" data-id="{{ $section->id }}"
                 class="flex items-center gap-3 bg-white rounded-lg border border-[#EDE7D8] px-4 py-3.5">

                <span class="drag-handle cursor-grab text-[#C4BCA6] select-none" title="Drag untuk urutkan">
                    ⠿
                </span>

                <div class="flex-1">
                    <p class="text-sm font-medium text-[#1C2521]">
                        {{ $section->typeDefinition()?->label ?? $section->type }}
                    </p>
                    @if (empty($section->content))
                        <p class="text-xs text-amber-600">Belum diisi kontennya</p>
                    @endif
                </div>

                <button wire:click="toggleVisibility({{ $section->id }})"
                        class="text-xs px-2.5 py-1 rounded-full font-medium
                               {{ $section->is_visible ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                    {{ $section->is_visible ? 'Tampil' : 'Tersembunyi' }}
                </button>

                {{-- Form isi konten menyusul di Hari 3 -- untuk sekarang cukup placeholder link --}}
                <a href="#" class="text-sm text-[#0F3D3E] hover:underline">Isi Konten</a>

                <button wire:click="removeSection({{ $section->id }})" wire:confirm="Hapus section ini?"
                        class="text-sm text-red-500 hover:underline">
                    Hapus
                </button>
            </div>
        @empty
            <p class="text-sm text-[#8B9490] text-center py-10 border border-dashed border-[#DDD6C7] rounded-lg">
                Belum ada section. Klik "+ Tambah Section" untuk mulai.
            </p>
        @endforelse
    </div>
</div>

@script
<script>
    // SortableJS diasumsikan sudah di-import di resources/js/app.js:
    // import Sortable from 'sortablejs'; window.Sortable = Sortable;
    const el = document.getElementById('section-list');

    if (el && window.Sortable) {
        Sortable.create(el, {
            handle: '.drag-handle',
            animation: 150,
            onEnd: function () {
                const orderedIds = Array.from(el.children)
                    .map(child => child.dataset.id)
                    .filter(Boolean);

                $wire.call('reorder', orderedIds);
            },
        });
    }
</script>
@endscript
