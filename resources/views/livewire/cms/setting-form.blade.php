<div class="max-w-3xl mx-auto px-6 py-10">
    <div class="mb-6">
        <h1 class="font-serif text-2xl text-[#0F3D3E]">Pengaturan</h1>
        <p class="text-sm text-[#5B6663]">Identitas, kontak, dan tampilan situs</p>
    </div>

    @if (session('status'))
        <div class="mb-5 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3">
            {{ session('status') }}
        </div>
    @endif

    <form wire:submit="save" class="space-y-6">

        <div class="bg-white rounded-xl border border-[#EDE7D8] p-5 space-y-4">
            <h2 class="font-serif text-base text-[#0F3D3E]">Identitas</h2>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-[#33403C] mb-1.5">Nama Perusahaan</label>
                    <input type="text" wire:model="company_name"
                           class="w-full rounded-lg border border-[#DDD6C7] px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#33403C] mb-1.5">Tagline</label>
                    <input type="text" wire:model="tagline"
                           class="w-full rounded-lg border border-[#DDD6C7] px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-[#33403C] mb-1.5">Logo (URL)</label>
                    <input type="text" wire:model="logo_url"
                           class="w-full rounded-lg border border-[#DDD6C7] px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#33403C] mb-1.5">Favicon (URL)</label>
                    <input type="text" wire:model="favicon_url"
                           class="w-full rounded-lg border border-[#DDD6C7] px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-[#EDE7D8] p-5 space-y-4">
            <h2 class="font-serif text-base text-[#0F3D3E]">Template &amp; Warna</h2>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-[#33403C] mb-1.5">Template</label>
                    <select wire:model="active_template"
                            class="w-full rounded-lg border border-[#DDD6C7] px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
                        <option value="corporate">Corporate</option>
                        <option value="creative">Creative</option>
                        <option value="minimal">Minimal</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#33403C] mb-1.5">Warna Primer</label>
                    <input type="text" wire:model="primary_color" placeholder="#0F3D3E"
                           class="w-full rounded-lg border border-[#DDD6C7] px-3.5 py-2.5 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#33403C] mb-1.5">Warna Sekunder</label>
                    <input type="text" wire:model="secondary_color" placeholder="#F7F3EC"
                           class="w-full rounded-lg border border-[#DDD6C7] px-3.5 py-2.5 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-[#EDE7D8] p-5 space-y-4">
            <h2 class="font-serif text-base text-[#0F3D3E]">Kontak</h2>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-[#33403C] mb-1.5">Email</label>
                    <input type="email" wire:model="contact_email"
                           class="w-full rounded-lg border border-[#DDD6C7] px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
                    @error('contact_email') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#33403C] mb-1.5">Telepon</label>
                    <input type="text" wire:model="contact_phone"
                           class="w-full rounded-lg border border-[#DDD6C7] px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-[#33403C] mb-1.5">Nomor WhatsApp</label>
                <input type="text" wire:model="whatsapp_number" placeholder="628123456789"
                       class="w-full rounded-lg border border-[#DDD6C7] px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
            </div>

            <div>
                <label class="block text-sm font-medium text-[#33403C] mb-1.5">Pesan Default WhatsApp</label>
                <textarea wire:model="whatsapp_default_message" rows="2"
                          class="w-full rounded-lg border border-[#DDD6C7] px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20"></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-[#33403C] mb-1.5">Alamat</label>
                <textarea wire:model="contact_address" rows="2"
                          class="w-full rounded-lg border border-[#DDD6C7] px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20"></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-[#33403C] mb-1.5">Embed Google Maps (URL)</label>
                <input type="text" wire:model="maps_embed_url"
                       class="w-full rounded-lg border border-[#DDD6C7] px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
            </div>

            <div>
                <label class="block text-sm font-medium text-[#33403C] mb-1.5">Jam Operasional</label>
                <input type="text" wire:model="operational_hours" placeholder="Senin - Sabtu, 09.00 - 17.00"
                       class="w-full rounded-lg border border-[#DDD6C7] px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
            </div>
        </div>

        <div class="bg-white rounded-xl border border-[#EDE7D8] p-5 space-y-4">
            <h2 class="font-serif text-base text-[#0F3D3E]">Lisensi</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-[#33403C] mb-1.5">Izin PPIU (Umrah)</label>
                    <input type="text" wire:model="ppiu_license"
                           class="w-full rounded-lg border border-[#DDD6C7] px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#33403C] mb-1.5">Izin PIHK (Haji)</label>
                    <input type="text" wire:model="pihk_license"
                           class="w-full rounded-lg border border-[#DDD6C7] px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-[#EDE7D8] p-5 space-y-3">
            <div class="flex items-center justify-between">
                <h2 class="font-serif text-base text-[#0F3D3E]">Media Sosial</h2>
                <button type="button" wire:click="addSocialLink" class="text-sm text-[#0F3D3E] hover:underline">+ Tambah</button>
            </div>

            @forelse ($socialLinks as $index => $link)
                <div class="flex gap-2">
                    <input type="text" wire:model="socialLinks.{{ $index }}.platform" placeholder="mis. Instagram"
                           class="w-40 rounded-lg border border-[#DDD6C7] px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
                    <input type="text" wire:model="socialLinks.{{ $index }}.url" placeholder="https://instagram.com/..."
                           class="flex-1 rounded-lg border border-[#DDD6C7] px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
                    <button type="button" wire:click="removeSocialLink({{ $index }})" class="text-sm text-red-500 px-2">Hapus</button>
                </div>
            @empty
                <p class="text-sm text-[#8B9490]">Belum ada link media sosial.</p>
            @endforelse
        </div>

        <div class="bg-white rounded-xl border border-[#EDE7D8] p-5 space-y-4">
            <h2 class="font-serif text-base text-[#0F3D3E]">Footer</h2>
            <div>
                <label class="block text-sm font-medium text-[#33403C] mb-1.5">Teks Copyright</label>
                <input type="text" wire:model="footer_copyright" placeholder="Copyright © 2026 - Nama Perusahaan"
                       class="w-full rounded-lg border border-[#DDD6C7] px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
            </div>
        </div>

        <div class="flex justify-end pb-6">
            <button type="submit" class="rounded-lg bg-[#0F3D3E] text-white text-sm font-medium px-5 py-2.5 hover:bg-[#0C3132]">
                Simpan Pengaturan
            </button>
        </div>
    </form>
</div>
