<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientStat extends Model
{
    protected $table = 'public.client_stats';
    protected $primaryKey = 'client_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'client_id',
        'total_users',
        'active_users_7d',
        'total_pages',
        'total_packages',
        'total_articles',
        'last_login_at',
        'last_activity_at',
        'stats_refreshed_at',
    ];

    protected $casts = [
        'last_login_at' => 'datetime',
        'last_activity_at' => 'datetime',
        'stats_refreshed_at' => 'datetime',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Bantu dashboard menandai data statistik yang sudah basi
     * (misal job refresh gagal/telat), bukan cuma menampilkan angka mentah.
     */
    public function isStale(int $thresholdMinutes = 30): bool
    {
        return !$this->stats_refreshed_at
            || $this->stats_refreshed_at->diffInMinutes(now()) > $thresholdMinutes;
    }
}
