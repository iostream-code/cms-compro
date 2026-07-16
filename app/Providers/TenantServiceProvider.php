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

        // Connection 'central' -- salinan config dari connection default
        // (pgsql atau mysql, sesuai DB_CONNECTION), tapi TIDAK PERNAH disentuh
        // oleh TenantDatabaseManager::useSchema()/resetToDefault(). Model yang
        // datanya harus selalu bisa diakses terlepas dari tenant mana yang
        // sedang aktif (Client, SuperAdmin, ClientStat, ActivityLog) pakai
        // connection ini. Karena cuma alias dari connection default, ganti
        // driver di .env (pgsql <-> mysql) otomatis kebawa ke sini juga --
        // tidak ada yang perlu diubah manual.
        $default = config('database.default');
        config(['database.connections.central' => config("database.connections.{$default}")]);
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
