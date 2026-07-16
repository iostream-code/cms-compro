{{--
    Partial generik untuk 1 field, dipakai baik di level atas maupun di
    dalam item repeater. $field = definisi dari section_types.schema,
    $path = key dot-notation untuk wire:model (mis. "slides.0.title").
--}}
@switch($field['type'])
    @case('textarea')
        <textarea wire:model="data.{{ $path }}" rows="4" placeholder="{{ $field['label'] }}"
                  class="w-full rounded-lg border border-[#DDD6C7] px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20"></textarea>
        @break

    @case('richtext')
        <textarea wire:model="data.{{ $path }}" rows="6" placeholder="{{ $field['label'] }}"
                  class="w-full rounded-lg border border-[#DDD6C7] px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20"></textarea>
        @break

    @case('image')
        <input type="text" wire:model="data.{{ $path }}" placeholder="URL gambar"
               class="w-full rounded-lg border border-[#DDD6C7] px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
        @break

    @case('icon')
        {{--
            Picker ikon. Admin tidak boleh disuruh menghafal nama kelas
            Boxicons, jadi disediakan daftar terkurasi (config/boxicons.php)
            + pencarian. Field teksnya tetap bisa diisi manual untuk ikon
            di luar daftar.

            wire:ignore.self di root: Livewire tidak boleh mem-patch ulang
            isi dropdown saat re-render, kalau tidak state Alpine (open/search)
            ikut ter-reset tiap kali user mengetik.
        --}}
        @php $iconGroups = config('boxicons'); @endphp

        <div x-data="{
                open: false,
                search: '',
                get value() { return $wire.get('data.{{ $path }}') || '' },
                pick(name) { $wire.set('data.{{ $path }}', name); this.open = false; this.search = '' },
                matches(name, label) {
                    if (!this.search) return true;
                    const q = this.search.toLowerCase();
                    return name.toLowerCase().includes(q) || label.toLowerCase().includes(q);
                },
             }"
             @click.outside="open = false"
             class="relative">

            <div class="flex gap-2">
                <button type="button" @click="open = !open"
                        class="flex h-[42px] w-[42px] shrink-0 items-center justify-center rounded-lg border border-[#DDD6C7] bg-white text-[#0F3D3E] transition hover:border-[#0F3D3E]"
                        :aria-expanded="open ? 'true' : 'false'" aria-label="Pilih ikon">
                    <template x-if="value">
                        <i class="bx text-xl" :class="value"></i>
                    </template>
                    <template x-if="!value">
                        <i class="bx bx-plus text-xl text-[#8B9490]"></i>
                    </template>
                </button>

                <input type="text" wire:model.live.debounce.300ms="data.{{ $path }}"
                       placeholder="Pilih ikon atau ketik nama, mis. bx-star"
                       class="w-full rounded-lg border border-[#DDD6C7] px-3.5 py-2.5 font-mono text-xs focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
            </div>

            <div x-show="open" x-cloak x-transition.opacity
                 class="absolute z-30 mt-2 max-h-72 w-full overflow-y-auto rounded-xl border border-[#EDE7D8] bg-white p-3 shadow-xl">

                <input type="text" x-model="search" placeholder="Cari ikon..."
                       class="mb-3 w-full rounded-lg border border-[#DDD6C7] px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">

                @foreach ($iconGroups as $groupLabel => $icons)
                    <div x-show="{{ collect($icons)->map(fn ($label, $name) => "matches('{$name}', '" . addslashes($label) . "')")->implode(' || ') }}">
                        <p class="mb-1.5 mt-2 text-[10px] font-semibold uppercase tracking-wide text-[#8B9490]">{{ $groupLabel }}</p>

                        <div class="grid grid-cols-6 gap-1.5">
                            @foreach ($icons as $name => $label)
                                <button type="button" x-show="matches('{{ $name }}', '{{ addslashes($label) }}')"
                                        @click="pick('{{ $name }}')" title="{{ $label }}"
                                        class="flex h-9 items-center justify-center rounded-lg border transition"
                                        :class="value === '{{ $name }}'
                                            ? 'border-[#0F3D3E] bg-[#0F3D3E] text-white'
                                            : 'border-transparent bg-[#F7F3EC] text-[#33403C] hover:border-[#0F3D3E]/40'">
                                    <i class="bx {{ $name }} text-lg"></i>
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @break

    @case('number')
        <input type="number" wire:model="data.{{ $path }}" @if(isset($field['min'])) min="{{ $field['min'] }}" @endif @if(isset($field['max'])) max="{{ $field['max'] }}" @endif
               class="w-full rounded-lg border border-[#DDD6C7] px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
        @break

    @case('select')
        <select wire:model="data.{{ $path }}"
                class="w-full rounded-lg border border-[#DDD6C7] px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
            @foreach ($field['options'] ?? [] as $option)
                <option value="{{ $option }}">{{ ucfirst(str_replace('_', ' ', $option)) }}</option>
            @endforeach
        </select>
        @break

    @case('boolean')
        <label class="flex items-center gap-2 text-sm text-[#33403C]">
            <input type="checkbox" wire:model="data.{{ $path }}" class="rounded border-[#DDD6C7]">
            {{ $field['label'] }}
        </label>
        @break

    @default
        <input type="text" wire:model="data.{{ $path }}" placeholder="{{ $field['label'] }}"
               @if(isset($field['max'])) maxlength="{{ $field['max'] }}" @endif
               class="w-full rounded-lg border border-[#DDD6C7] px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
@endswitch
