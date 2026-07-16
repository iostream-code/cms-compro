<?php

use App\Http\Controllers\Auth\LoginController;
use App\Livewire\Cms\ArticleManager;
use App\Livewire\Cms\Dashboard;
use App\Livewire\Cms\PackageForm;
use App\Livewire\Cms\PackageManager;
use App\Livewire\Cms\PageManager;
use App\Livewire\Cms\SectionContentForm;
use App\Livewire\Cms\SectionManager;
use App\Livewire\Cms\SettingForm;
use App\Livewire\Cms\TestimonialManager;
use Illuminate\Support\Facades\Route;

/*
 * ResolveTenant sekarang di-prepend ke grup 'web' secara GLOBAL (lihat
 * bootstrap/app.php) -- otomatis berlaku untuk semua route di file ini,
 * termasuk route internal Livewire (/livewire/update). Tidak perlu
 * dibungkus manual lagi di sini.
 */

// Guest saja -- kalau sudah login, redirect otomatis ke dashboard (bawaan middleware 'guest')
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [LoginController::class, 'destroy'])->name('logout');

    Route::prefix('cms')->name('cms.')->group(function () {
        Route::get('/', Dashboard::class)->name('dashboard');

        // Admin & member sama-sama boleh kelola konten
        Route::middleware('role:admin,member')->group(function () {
            Route::get('pages', PageManager::class)->name('pages.index');
            Route::get('pages/{page}/sections', SectionManager::class)->name('sections.index');
            Route::get('sections/{section}/edit', SectionContentForm::class)->name('sections.edit');

            Route::get('packages', PackageManager::class)->name('packages.index');
            Route::get('packages/create', PackageForm::class)->name('packages.create');
            Route::get('packages/{package}/edit', PackageForm::class)->name('packages.edit');

            Route::get('articles', ArticleManager::class)->name('articles.index');

            Route::get('testimonials', TestimonialManager::class)->name('testimonials.index');
        });

        // Hanya admin: ubah setting perusahaan
        Route::middleware('role:admin')->group(function () {
            Route::get('settings', SettingForm::class)->name('settings.edit');
        });
    });
});

// Visitor-facing (public, tanpa auth) -- tetap perlu resolusi tenant
// untuk tahu data client mana yang ditampilkan
Route::get('/', [\App\Http\Controllers\Public\HomeController::class, 'index'])->name('home');

// WAJIB didaftarkan sebelum catch-all '/{slug}' di bawah, kalau tidak
// '/paket', '/artikel', dst akan ketangkep sebagai slug halaman biasa.
Route::get('paket', [\App\Http\Controllers\Public\PackagePublicController::class, 'index'])->name('packages.index');
Route::get('paket/{package:slug}', [\App\Http\Controllers\Public\PackagePublicController::class, 'show'])->name('packages.show');

Route::get('artikel', [\App\Http\Controllers\Public\ArticlePublicController::class, 'index'])->name('articles.index');
Route::get('artikel/{article:slug}', [\App\Http\Controllers\Public\ArticlePublicController::class, 'show'])->name('articles.show');

Route::get('galeri', [\App\Http\Controllers\Public\GalleryPublicController::class, 'index'])->name('gallery');
Route::get('testimoni', [\App\Http\Controllers\Public\TestimonialPublicController::class, 'index'])->name('testimonials');

Route::get('/{slug}', [\App\Http\Controllers\Public\PageController::class, 'show'])->name('page.show');
