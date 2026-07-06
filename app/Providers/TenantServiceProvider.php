<?php

namespace App\Providers;

use App\Services\TenantContext;
use Illuminate\Support\ServiceProvider;

class TenantServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Singleton per-request (bukan global) -- container Laravel di-reset
        // otomatis tiap request untuk aplikasi non-octane, jadi ini aman.
        $this->app->singleton(TenantContext::class);
    }

    public function boot(): void
    {
        // WAJIB: tanpa ini, `php artisan migrate` biasa TIDAK akan menemukan
        // file di database/migrations/central/ -- Laravel cuma scan
        // database/migrations/ secara default.
        // Migration di database/migrations/tenant/ SENGAJA tidak didaftarkan
        // di sini -- itu hanya dijalankan lewat TenantMigrationService
        // (search_path diarahkan ke schema tenant dulu), bukan migrate biasa.
        $this->loadMigrationsFrom(database_path('migrations/central'));
    }
}
