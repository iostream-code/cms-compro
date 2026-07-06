<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Section extends Model
{
    protected $fillable = [
        'page_id',
        'type',
        'content',
        'order',
        'is_visible',
    ];

    protected $casts = [
        'content' => 'array',
        'is_visible' => 'boolean',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    /**
     * Ambil metadata tipe (label, blueprint schema) dari section_types.
     * Bukan FK formal -- type_key dicocokkan manual karena section_types
     * adalah data referensi statis, bukan relasi yang perlu di-enforce DB.
     */
    public function typeDefinition(): ?SectionType
    {
        return SectionType::query()->where('type_key', $this->type)->first();
    }
}
