<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'company_name',
        'tagline',
        'logo_url',
        'favicon_url',
        'active_template',
        'primary_color',
        'secondary_color',
        'contact_email',
        'whatsapp_number',
        'whatsapp_default_message',
        'contact_phone',
        'contact_address',
        'maps_embed_url',
        'operational_hours',
        'ppiu_license',
        'pihk_license',
        'social_links',
        'footer_copyright',
    ];

    protected $casts = [
        'social_links' => 'collection',
    ];

    /**
     * 1 row per tenant -- bukan resource banyak baris, jadi disediakan
     * accessor tunggal ini daripada query manual berulang di tiap tempat.
     */
    public static function current(): self
    {
        return static::query()->firstOrCreate([]);
    }
}
