<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = [
        'jamaah_name',
        'jamaah_city',
        'jamaah_photo_url',
        'package_type',
        'year',
        'rating',
        'content',
        'is_published',
        'order',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'rating' => 'integer',
        'year' => 'integer',
    ];
}
