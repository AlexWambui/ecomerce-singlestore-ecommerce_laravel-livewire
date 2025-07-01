<?php

namespace App\Models\DeliveryLocations;

use Illuminate\Database\Eloquent\Model;

class DeliveryArea extends Model
{
    protected $guarded = [];

    public function region()
    {
        return $this->belongsTo(DeliveryRegion::class);
    }

    public function deliveryMethods()
    {
        return $this->belongsToMany(DeliveryMethod::class, 'delivery_area_delivery_methods')->withPivot('id', 'uuid', 'custom_price')->withTimestamps();
    }

    public function deliveryAreaDeliveryMethods()
    {
        return $this->hasMany(DeliveryAreaDeliveryMethod::class);
    }
}
