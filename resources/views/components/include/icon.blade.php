@props(['name', 'class' => 'text-xl', 'fallback' => 'bx-check'])

@php
    $value = trim((string) $name);

    // Field `icon` di section_types dulu diisi emoji manual. Setelah pindah ke
    // Boxicons, data lama itu masih ada di database tenant yang sudah jalan --
    // jadi jangan diperlakukan sebagai nama class (nanti render kotak kosong).
    // Aturan: kalau nilainya diawali "bx" anggap nama ikon Boxicons
    // (bx-*, bxs-*, bxl-* semuanya tetap pakai class dasar "bx"), kalau tidak
    // tampilkan apa adanya supaya emoji/teks lama tetap muncul utuh.
    $isBoxicon = $value !== '' && str_starts_with($value, 'bx');
@endphp

@if ($isBoxicon)
    <i class="bx {{ $value }} {{ $class }}" aria-hidden="true"></i>
@elseif ($value !== '')
    <span class="{{ $class }}" aria-hidden="true">{{ $value }}</span>
@else
    <i class="bx {{ $fallback }} {{ $class }}" aria-hidden="true"></i>
@endif
