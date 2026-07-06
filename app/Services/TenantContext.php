<?php

namespace App\Services;

use App\Models\Client;

/**
 * Dibind sebagai singleton di container per request (lihat AppServiceProvider).
 * Controller / policy / job bisa resolve app(TenantContext::class) untuk tahu
 * client mana yang sedang aktif, tanpa perlu passing $client di semua layer.
 */
class TenantContext
{
    private ?Client $client = null;

    public function set(Client $client): void
    {
        $this->client = $client;
    }

    public function get(): Client
    {
        if (!$this->client) {
            throw new \RuntimeException('Tenant context belum di-set. Pastikan ResolveTenant middleware sudah jalan.');
        }

        return $this->client;
    }

    public function schemaName(): string
    {
        return $this->get()->schema_name;
    }

    public function check(): bool
    {
        return $this->client !== null;
    }
}
