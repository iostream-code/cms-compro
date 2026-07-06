<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Cms\ArticleController;
use App\Http\Controllers\Cms\PackageController;
use App\Http\Controllers\Cms\SettingController;
use App\Livewire\Cms\PageManager;
use App\Livewire\Cms\SectionManager;
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
        // Placeholder -- ganti ke controller dashboard sungguhan begitu modul konten (Fase 4) jalan
        Route::view('/', 'cms.dashboard')->name('dashboard');

        // Admin & member sama-sama boleh kelola konten
        Route::middleware('role:admin,member')->group(function () {
            Route::get('pages', PageManager::class)->name('pages.index');
            Route::get('pages/{page}/sections', SectionManager::class)->name('sections.index');

            Route::resource('packages', PackageController::class);
            Route::resource('articles', ArticleController::class);
        });

        // Hanya admin: kelola user, ganti template, ubah setting
        Route::middleware('role:admin')->group(function () {
            Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
            Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
        });
    });
});

// Visitor-facing (public, tanpa auth) -- tetap perlu resolusi tenant
// untuk tahu data client mana yang ditampilkan
Route::get('/', [\App\Http\Controllers\Public\HomeController::class, 'index'])->name('home');
Route::get('/{slug}', [\App\Http\Controllers\Public\PageController::class, 'show'])->name('page.show');
