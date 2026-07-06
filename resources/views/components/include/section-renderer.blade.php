{{--
    Pemakaian: @foreach ($page->sections as $section) <x-include:section-renderer :section="$section" /> @endforeach
    Otomatis pilih component sesuai $section->type (snake_case) -> section.{kebab-case}.blade.php
--}}
@if ($section->is_visible)
    @php
        $componentName = 'section.' . str_replace('_', '-', $section->type);
    @endphp
    <x-dynamic-component :component="$componentName" :content="$section->content" />
@endif
