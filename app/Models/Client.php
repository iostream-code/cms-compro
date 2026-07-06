<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Services\TenantCacheHelper;

class Client extends Model
{
    use HasUuids;

    protected $connection = 'pgsql'; // selalu connection default (search_path publik/default)
    protected $table = 'public.clients'; // qualifier eksplisit, aman meski search_path sedang di-override

    protected $fillable = [
        'name',
        'subdomain',
        'custom_domain',
        'domain_type', // 'subdomain' | 'custom'
        'schema_name',
        'is_active',
        'plan',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function clientStat(): HasOne
    {
        return $this->hasOne(ClientStat::class);
    }

    /**
     * Invalidate cache resolusi domain setiap kali data client berubah,
     * supaya perubahan custom_domain / is_active langsung berefek tanpa
     * menunggu TTL cache habis.
     */
    protected static function booted(): void
    {
        static::saved(function (Client $client) {
            TenantCacheHelper::forget($client);
        });

        static::deleted(function (Client $client) {
            TenantCacheHelper::forget($client);
        });
    }
}
