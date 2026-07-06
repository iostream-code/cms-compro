<?php

namespace App\Services;

use App\Models\Client;
use Illuminate\Support\Facades\Cache;

/**
 * Menyimpan logika key cache di satu tempat supaya TenantResolver (yang menulis)
 * dan Client model (yang menghapus saat data berubah) selalu konsisten.
 */
class TenantCacheHelper
{
    private const TTL_SECONDS = 300;

    public static function key(string $host): string
    {
        return 'tenant:host:' . strtolower(trim($host));
    }

    public static function ttl(): int
    {
        return self::TTL_SECONDS;
    }

    /**
     * Hapus entry cache untuk kedua kemungkinan host milik client ini
     * (subdomain penuh & custom domain), dipanggil dari Client::booted().
     */
    public static function forget(Client $client): void
    {
        if ($client->subdomain) {
            Cache::forget(self::key($client->subdomain . '.' . config('tenancy.base_domain')));
        }

        if ($client->custom_domain) {
            Cache::forget(self::key($client->custom_domain));
        }
    }
}
