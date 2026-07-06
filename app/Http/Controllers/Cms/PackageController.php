<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * PLACEHOLDER -- implementasi penuh menyusul di Fase 4 / SPRINT_PLAN.md Hari 4.
 * Dibuat sekarang cuma supaya Route::resource() tidak melempar
 * ReflectionException saat class-nya di-scan (mis. oleh `route:list`
 * atau saat route-nya benar-benar diakses).
 */
class PackageController extends Controller
{
    public function index()
    {
        abort(501, 'Belum diimplementasikan -- lihat SPRINT_PLAN.md Hari 4');
    }

    public function create()
    {
        abort(501, 'Belum diimplementasikan -- lihat SPRINT_PLAN.md Hari 4');
    }

    public function store(Request $request)
    {
        abort(501, 'Belum diimplementasikan -- lihat SPRINT_PLAN.md Hari 4');
    }

    public function show(string $package)
    {
        abort(501, 'Belum diimplementasikan -- lihat SPRINT_PLAN.md Hari 4');
    }

    public function edit(string $package)
    {
        abort(501, 'Belum diimplementasikan -- lihat SPRINT_PLAN.md Hari 4');
    }

    public function update(Request $request, string $package)
    {
        abort(501, 'Belum diimplementasikan -- lihat SPRINT_PLAN.md Hari 4');
    }

    public function destroy(string $package)
    {
        abort(501, 'Belum diimplementasikan -- lihat SPRINT_PLAN.md Hari 4');
    }
}
