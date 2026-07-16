<?php

namespace App\Models;

use App\Traits\LogsTenantActivity;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use HasUuids, SoftDeletes, LogsTenantActivity;

    protected $fillable = [
        'type',
        'name',
        'slug',
        'short_description',
        'description',
        'duration',
        'price_from',
        'price_currency',
        'departure_city',
        'departure_date',
        'departure_date_note',
        'departure_airport',
        'seats_total',
        'seats_available',
        'airline',
        'hotel_makkah',
        'hotel_madinah',
        'facilities',
        'itinerary',
        'room_types',
        'requirements',
        'terms_conditions',
        'brochure_url',
        'image_url',
        'is_published',
        'order',
        'created_by',
    ];

    protected $casts = [
        'price_from' => 'decimal:2',
        'departure_date' => 'date',
        'seats_total' => 'integer',
        'seats_available' => 'integer',
        'facilities' => 'collection',
        'itinerary' => 'collection',
        'room_types' => 'collection',
        'is_published' => 'boolean',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * WAJIB: `{package:slug}` di route cuma atur resolusi request masuk --
     * generate URL (`route('packages.show', $package)`) tetap pakai
     * getRouteKeyName(), jadi harus di-override juga supaya konsisten
     * pakai slug di kedua arah.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
