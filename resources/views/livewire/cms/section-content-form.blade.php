<div class="max-w-3xl mx-auto px-6 py-10">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="font-serif text-2xl text-[#0F3D3E]">Isi Konten: {{ $section->typeDefinition()?->label ?? $section->type }}</h1>
            <p class="text-sm text-[#5B6663]">{{ $section->page->title }}</p>
        </div>
        <a href="{{ route('cms.sections.index', $section->page) }}" class="text-sm text-[#5B6663] hover:underline">&larr; Kembali</a>
    </div>

    <form wire:submit="save" class="space-y-4">
        <div class="bg-white rounded-xl border border-[#EDE7D8] p-5 space-y-4">
            @forelse ($fields as $field)
                @if ($field['type'] === 'repeater')
                    <div class="border border-[#EDE7D8] rounded-lg p-3 space-y-3">
                        <div class="flex items-center justify-between">
                            <label class="text-sm font-medium text-[#33403C]">{{ $field['label'] }}</label>
                            <button type="button" wire:click="addRepeaterItem('{{ $field['key'] }}')" class="text-sm text-[#0F3D3E] hover:underline">+ Tambah</button>
                        </div>

                        @forelse (data_get($data, $field['key'], []) as $index => $item)
                            <div class="border border-[#EDE7D8] rounded-lg p-3 space-y-2">
                                @foreach ($field['fields'] as $subField)
                                    <div>
                                        @if ($subField['type'] !== 'boolean')
                                            <label class="block text-xs font-medium text-[#5B6663] mb-1">{{ $subField['label'] }}</label>
                                        @endif
                                        @include('livewire.cms.partials.field-input', ['field' => $subField, 'path' => $field['key'] . '.' . $index . '.' . $subField['key']])
                                    </div>
                                @endforeach
                                <button type="button" wire:click="removeRepeaterItem('{{ $field['key'] }}', {{ $index }})" class="text-xs text-red-500 hover:underline">Hapus item</button>
                            </div>
                        @empty
                            <p class="text-xs text-[#8B9490]">Belum ada item.</p>
                        @endforelse
                    </div>
                @else
                    <div>
                        @if ($field['type'] !== 'boolean')
                            <label class="block text-sm font-medium text-[#33403C] mb-1.5">{{ $field['label'] }}</label>
                        @endif
                        @include('livewire.cms.partials.field-input', ['field' => $field, 'path' => $field['key']])
                    </div>
                @endif
            @empty
                <p class="text-sm text-[#8B9490]">Tipe section ini tidak punya field yang bisa diisi.</p>
            @endforelse
        </div>

        <div class="flex justify-end gap-2 pb-6">
            <a href="{{ route('cms.sections.index', $section->page) }}" class="text-sm text-[#5B6663] px-4 py-2.5">Batal</a>
            <button type="submit" class="rounded-lg bg-[#0F3D3E] text-white text-sm font-medium px-5 py-2.5 hover:bg-[#0C3132]">
                Simpan
            </button>
        </div>
    </form>
</div>
