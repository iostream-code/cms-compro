<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SeoMeta extends Model
{
    protected $table = 'seo_meta';

    protected $fillable = [
        'page_id',
        'meta_title',
        'meta_description',
        'og_image_url',
        'canonical_url',
        'robots',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }
}
