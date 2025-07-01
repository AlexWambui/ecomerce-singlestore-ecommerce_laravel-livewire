<?php

namespace App\Models\DeliveryLocations;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DeliveryRegion extends Model
{
    protected $guarded = [];

    protected static function booted()
    {
        static::creating(function ($region) {
            $region->uuid =(string) Str::uuid();
            $region->slug = Str::slug($region->name);
            $region->country = $region->country ?? 'KE';
        });

        static::updating(function ($region) {
            if ($region->isDirty('name')) {
                $region->slug = Str::slug($region->name);
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function areas()
    {
        return $this->hasMany(DeliveryArea::class);
    }
}
