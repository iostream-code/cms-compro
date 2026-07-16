@props(['icon' => 'bx-folder-open', 'message', 'action' => null, 'actionUrl' => null])

{{-- Placeholder untuk daftar kosong. Sengaja tidak sekadar teks polos:
     halaman yang isinya belum ada tetap harus terlihat disengaja, bukan rusak. --}}
<div class="flex flex-col items-center justify-center px-6 py-20 text-center">
    <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-[var(--brand)]/8 text-[var(--brand)]/60">
        <i class="bx {{ $icon }} text-3xl" aria-hidden="true"></i>
    </div>

    <p class="text-sm text-[#8B9490]">{{ $message }}</p>

    @if ($action && $actionUrl)
        <a href="{{ $actionUrl }}"
           class="mt-5 inline-flex items-center gap-2 rounded-md border-2 border-[var(--brand)] px-6 py-2.5 text-sm font-semibold text-[var(--brand)] transition hover:bg-[var(--brand)] hover:text-white">
            {{ $action }}
        </a>
    @endif
</div>
