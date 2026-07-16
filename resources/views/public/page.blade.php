@php $settings = \App\Models\Setting::current(); @endphp

<x-layouts.public
    :title="$page->seoMeta?->meta_title ?? ($page->title . ' - ' . $settings->company_name)"
    :seo="$page->seoMeta">

    @foreach ($page->sections as $section)
        <x-include.section-renderer :section="$section" />
    @endforeach

</x-layouts.public>
