<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $page->seoMeta?->meta_title ?? $page->title }}</title>
    @if ($page->seoMeta?->meta_description)
        <meta name="description" content="{{ $page->seoMeta->meta_description }}">
    @endif
    @if ($page->seoMeta?->canonical_url)
        <link rel="canonical" href="{{ $page->seoMeta->canonical_url }}">
    @endif
    <meta name="robots" content="{{ $page->seoMeta?->robots ?? 'index, follow' }}">
    @vite(['resources/css/app.css'])
</head>
<body class="bg-white text-[#1C2521] antialiased">

    @foreach ($page->sections as $section)
        <x-include.section-renderer :section="$section" />
    @endforeach

</body>
</html>
