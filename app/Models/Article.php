<?php

namespace App\Models;

use App\Traits\LogsTenantActivity;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use HasUuids, SoftDeletes, LogsTenantActivity;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'body',
        'category',
        'featured_image_url',
        'is_published',
        'published_at',
        'created_by',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Sama seperti Package: generate URL harus ikut slug, bukan cuma
     * resolusi request masuk (lihat catatan di App\Models\Package).
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
