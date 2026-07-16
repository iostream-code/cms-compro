<div class="max-w-3xl mx-auto px-6 py-10">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="font-serif text-2xl text-[#0F3D3E]">{{ $package ? 'Edit Paket' : 'Paket Baru' }}</h1>
            <p class="text-sm text-[#5B6663]">Isi detail paket -- semua kolom opsional kecuali Tipe & Nama</p>
        </div>
        <a href="{{ route('cms.packages.index') }}" class="text-sm text-[#5B6663] hover:underline">&larr; Kembali</a>
    </div>

    <form wire:submit="save" class="space-y-6">

        {{-- Info dasar --}}
        <div class="bg-white rounded-xl border border-[#EDE7D8] p-5 space-y-4">
            <h2 class="font-serif text-base text-[#0F3D3E]">Info Dasar</h2>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-[#33403C] mb-1.5">Tipe Paket</label>
                    <select wire:model="type" class="w-full rounded-lg border border-[#DDD6C7] px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
                        <option value="umroh">Umrah</option>
                        <option value="haji">Haji</option>
                        <option value="wisata_religi">Wisata Religi</option>
                    </select>
                    @error('type') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#33403C] mb-1.5">Durasi</label>
                    <input type="text" wire:model="duration" placeholder="mis. Umrah 12 Hari"
                           class="w-full rounded-lg border border-[#DDD6C7] px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-[#33403C] mb-1.5">Nama Paket</label>
                <input type="text" wire:model="name" placeholder="mis. 12 HARI - OKTOBER 2026 - PAKET C"
                       class="w-full rounded-lg border border-[#DDD6C7] px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
                @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-[#33403C] mb-1.5">Deskripsi Singkat</label>
                <input type="text" wire:model="short_description"
                       class="w-full rounded-lg border border-[#DDD6C7] px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
            </div>

            <div>
                <label class="block text-sm font-medium text-[#33403C] mb-1.5">Deskripsi Lengkap</label>
                <textarea wire:model="description" rows="3"
                          class="w-full rounded-lg border border-[#DDD6C7] px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20"></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-[#33403C] mb-1.5">Gambar Utama (URL)</label>
                <input type="text" wire:model="image_url"
                       class="w-full rounded-lg border border-[#DDD6C7] px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
            </div>
        </div>

        {{-- Harga & keberangkatan --}}
        <div class="bg-white rounded-xl border border-[#EDE7D8] p-5 space-y-4">
            <h2 class="font-serif text-base text-[#0F3D3E]">Harga &amp; Keberangkatan</h2>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-[#33403C] mb-1.5">Harga Mulai Dari</label>
                    <input type="number" step="0.01" wire:model="price_from"
                           class="w-full rounded-lg border border-[#DDD6C7] px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#33403C] mb-1.5">Mata Uang</label>
                    <input type="text" wire:model="price_currency" maxlength="3"
                           class="w-full rounded-lg border border-[#DDD6C7] px-3.5 py-2.5 text-sm uppercase focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#33403C] mb-1.5">Kota Keberangkatan</label>
                    <input type="text" wire:model="departure_city"
                           class="w-full rounded-lg border border-[#DDD6C7] px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-[#33403C] mb-1.5">Bandara Keberangkatan</label>
                    <input type="text" wire:model="departure_airport" placeholder="mis. Juanda International Airport (SUB)"
                           class="w-full rounded-lg border border-[#DDD6C7] px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#33403C] mb-1.5">Maskapai</label>
                    <input type="text" wire:model="airline"
                           class="w-full rounded-lg border border-[#DDD6C7] px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-[#33403C] mb-1.5">Tanggal Keberangkatan</label>
                    <input type="date" wire:model="departure_date"
                           class="w-full rounded-lg border border-[#DDD6C7] px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#33403C] mb-1.5">Catatan Tanggal (kalau belum pasti)</label>
                    <input type="text" wire:model="departure_date_note" placeholder="mis. Estimasi Tahun 2034"
                           class="w-full rounded-lg border border-[#DDD6C7] px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-[#33403C] mb-1.5">Total Seat</label>
                    <input type="number" wire:model="seats_total"
                           class="w-full rounded-lg border border-[#DDD6C7] px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#33403C] mb-1.5">Sisa Seat</label>
                    <input type="number" wire:model="seats_available"
                           class="w-full rounded-lg border border-[#DDD6C7] px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
                </div>
            </div>
        </div>

        {{-- Hotel --}}
        <div class="bg-white rounded-xl border border-[#EDE7D8] p-5 space-y-4">
            <h2 class="font-serif text-base text-[#0F3D3E]">Hotel</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-[#33403C] mb-1.5">Hotel Makkah</label>
                    <input type="text" wire:model="hotel_makkah"
                           class="w-full rounded-lg border border-[#DDD6C7] px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#33403C] mb-1.5">Hotel Madinah</label>
                    <input type="text" wire:model="hotel_madinah"
                           class="w-full rounded-lg border border-[#DDD6C7] px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
                </div>
            </div>
        </div>

        {{-- Fasilitas (repeater string) --}}
        <div class="bg-white rounded-xl border border-[#EDE7D8] p-5 space-y-3">
            <div class="flex items-center justify-between">
                <h2 class="font-serif text-base text-[#0F3D3E]">Fasilitas</h2>
                <button type="button" wire:click="addFacility" class="text-sm text-[#0F3D3E] hover:underline">+ Tambah</button>
            </div>

            @forelse ($facilities as $index => $facility)
                <div class="flex gap-2">
                    <input type="text" wire:model="facilities.{{ $index }}" placeholder="mis. Konsumsi"
                           class="flex-1 rounded-lg border border-[#DDD6C7] px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
                    <button type="button" wire:click="removeFacility({{ $index }})" class="text-sm text-red-500 px-2">Hapus</button>
                </div>
            @empty
                <p class="text-sm text-[#8B9490]">Belum ada fasilitas ditambahkan.</p>
            @endforelse
        </div>

        {{-- Itinerary (repeater object) --}}
        <div class="bg-white rounded-xl border border-[#EDE7D8] p-5 space-y-3">
            <div class="flex items-center justify-between">
                <h2 class="font-serif text-base text-[#0F3D3E]">Itinerary Harian</h2>
                <button type="button" wire:click="addItineraryDay" class="text-sm text-[#0F3D3E] hover:underline">+ Tambah Hari</button>
            </div>

            @forelse ($itinerary as $index => $day)
                <div class="border border-[#EDE7D8] rounded-lg p-3 space-y-2">
                    <div class="flex gap-2 items-start">
                        <input type="text" wire:model="itinerary.{{ $index }}.day" placeholder="Hari ke-"
                               class="w-20 rounded-lg border border-[#DDD6C7] px-2.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
                        <input type="text" wire:model="itinerary.{{ $index }}.title" placeholder="Judul (mis. Keberangkatan)"
                               class="flex-1 rounded-lg border border-[#DDD6C7] px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
                        <button type="button" wire:click="removeItineraryDay({{ $index }})" class="text-sm text-red-500 px-2 py-2">Hapus</button>
                    </div>
                    <textarea wire:model="itinerary.{{ $index }}.description" rows="2" placeholder="Rute / lokasi / aktivitas"
                              class="w-full rounded-lg border border-[#DDD6C7] px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20"></textarea>
                </div>
            @empty
                <p class="text-sm text-[#8B9490]">Belum ada itinerary ditambahkan.</p>
            @endforelse
        </div>

        {{-- Tipe kamar (repeater object) --}}
        <div class="bg-white rounded-xl border border-[#EDE7D8] p-5 space-y-3">
            <div class="flex items-center justify-between">
                <h2 class="font-serif text-base text-[#0F3D3E]">Tipe Kamar &amp; Harga</h2>
                <button type="button" wire:click="addRoomType" class="text-sm text-[#0F3D3E] hover:underline">+ Tambah</button>
            </div>

            @forelse ($roomTypes as $index => $room)
                <div class="flex gap-2">
                    <input type="text" wire:model="roomTypes.{{ $index }}.label" placeholder="mis. Quad (1 Kamar ber-4)"
                           class="flex-1 rounded-lg border border-[#DDD6C7] px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
                    <input type="number" step="0.01" wire:model="roomTypes.{{ $index }}.price" placeholder="Harga"
                           class="w-40 rounded-lg border border-[#DDD6C7] px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
                    <button type="button" wire:click="removeRoomType({{ $index }})" class="text-sm text-red-500 px-2">Hapus</button>
                </div>
            @empty
                <p class="text-sm text-[#8B9490]">Belum ada tipe kamar ditambahkan.</p>
            @endforelse
        </div>

        {{-- Syarat & lainnya --}}
        <div class="bg-white rounded-xl border border-[#EDE7D8] p-5 space-y-4">
            <h2 class="font-serif text-base text-[#0F3D3E]">Persyaratan &amp; Ketentuan</h2>

            <div>
                <label class="block text-sm font-medium text-[#33403C] mb-1.5">Persyaratan Peserta</label>
                <textarea wire:model="requirements" rows="3"
                          class="w-full rounded-lg border border-[#DDD6C7] px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20"></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-[#33403C] mb-1.5">Syarat &amp; Ketentuan</label>
                <textarea wire:model="terms_conditions" rows="3"
                          class="w-full rounded-lg border border-[#DDD6C7] px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20"></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-[#33403C] mb-1.5">Link Brosur (PDF)</label>
                <input type="text" wire:model="brochure_url"
                       class="w-full rounded-lg border border-[#DDD6C7] px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
            </div>

            <label class="flex items-center gap-2 text-sm text-[#33403C]">
                <input type="checkbox" wire:model="is_published" class="rounded border-[#DDD6C7]">
                Publikasikan paket ini
            </label>
        </div>

        <div class="flex justify-end gap-2 pb-6">
            <a href="{{ route('cms.packages.index') }}" class="text-sm text-[#5B6663] px-4 py-2.5">Batal</a>
            <button type="submit" class="rounded-lg bg-[#0F3D3E] text-white text-sm font-medium px-5 py-2.5 hover:bg-[#0C3132]">
                Simpan Paket
            </button>
        </div>
    </form>
</div>
