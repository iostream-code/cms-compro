<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SectionType extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'type_key',
        'label',
        'description',
        'schema',
        'is_active',
        'order',
    ];

    protected $casts = [
        'schema' => 'array',
        'is_active' => 'boolean',
    ];
}
