<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Section;
use Illuminate\View\View;

class GalleryPublicController extends Controller
{
    /**
     * Galeri tidak punya tabel sendiri -- fotonya tersimpan di dalam
     * content JSON milik section bertipe 'gallery'. Halaman ini
     * menggabungkan foto dari semua section gallery yang tampil, supaya
     * admin cukup kelola foto di satu tempat (Susun Section).
     */
    public function index(): View
    {
        $images = Section::query()
            ->where('type', 'gallery')
            ->where('is_visible', true)
            ->orderBy('order')
            ->get()
            ->flatMap(fn (Section $section) => $section->content['images'] ?? [])
            ->filter(fn ($image) => !empty($image['image_url']))
            ->values();

        return view('public.gallery', ['images' => $images]);
    }
}
