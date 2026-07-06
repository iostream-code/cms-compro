<?php

namespace App\Services;

use App\Exceptions\TenantInactiveException;
use App\Exceptions\TenantNotFoundException;
use App\Models\Client;
use Illuminate\Support\Facades\Cache;

class TenantResolver
{
    /**
     * Resolve Client dari host request.
     *
     * Urutan pengecekan (sesuai spesifikasi):
     *   1. custom_domain persis sama dengan host
     *   2. subdomain: host = "{subdomain}.{base_domain}"
     *   3. tidak cocok keduanya -> TenantNotFoundException
     *
     * Catatan desain: yang di-cache HANYA client id (string kecil), BUKAN
     * objek Eloquent Client secara utuh. Model Eloquent punya banyak state
     * internal (relasi ter-load, koneksi, dsb.) yang bikin serialisasi PHP-nya
     * rapuh -- gampang berakhir jadi __PHP_Incomplete_Class saat di-unserialize,
     * terutama di cache driver file/redis. Query ulang by primary key itu murah
     * (selalu indexed), jadi tidak ada trade-off performa berarti.
     *
     * @throws TenantNotFoundException
     * @throws TenantInactiveException
     */
    public function resolveFromHost(string $host): Client
    {
        $host = strtolower(trim($host));

        $clientId = Cache::remember(
            TenantCacheHelper::key($host),
            TenantCacheHelper::ttl(),
            fn() => $this->lookup($host)?->id
        );

        if (!$clientId) {
            throw new TenantNotFoundException();
        }

        $client = Client::query()->find($clientId);

        if (!$client) {
            // Client sudah dihapus tapi cache ID-nya belum kadaluarsa -- bersihkan
            // supaya request berikutnya query ulang dari database, bukan nyangkut 404 terus.
            Cache::forget(TenantCacheHelper::key($host));
            throw new TenantNotFoundException();
        }

        if (!$client->is_active) {
            throw new TenantInactiveException();
        }

        return $client;
    }

    private function lookup(string $host): ?Client
    {
        // 1. Cek custom domain dulu (lebih spesifik / sengaja dikonfigurasi client)
        $client = Client::query()->where('custom_domain', $host)->first();
        if ($client) {
            return $client;
        }

        // 2. Cek subdomain: strip base domain dari host untuk dapat slug-nya
        $baseDomain = config('tenancy.base_domain'); // contoh: "yourcompany.com"
        if (str_ends_with($host, '.' . $baseDomain)) {
            $subdomain = substr($host, 0, -1 * (strlen($baseDomain) + 1));

            // Blokir subdomain reserved supaya tidak bentrok dengan route sistem
            if (in_array($subdomain, config('tenancy.reserved_subdomains', []), true)) {
                return null;
            }

            return Client::query()->where('subdomain', $subdomain)->first();
        }

        return null;
    }
}
