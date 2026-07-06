{{--
    Section: packages-preview
    $content -- array jsonb dari sections.content, sesuai blueprint di section_types.
    Styling per template (corporate/creative/minimal) menyusul di Fase 5 (Hari 4-6).
    Untuk sekarang: render minimal supaya sistem section->content->tampil sudah tersambung.
--}}
<section class="section-packages-preview py-12 px-6" data-section-type="packages-preview">
    <div class="max-w-5xl mx-auto">
        <p class="text-xs font-mono uppercase tracking-wide text-[#8B9490] mb-2">packages-preview</p>
        @if (empty($content))
            <p class="text-sm text-amber-600 italic">Section ini belum diisi kontennya.</p>
        @else
            <pre class="text-xs bg-[#F7F3EC] rounded-lg p-4 overflow-x-auto">{{ json_encode($content, JSON_PRETTY_PRINT) }}</pre>
        @endif
    </div>
</section>
