<?php

namespace App\Models\DeliveryLocations;

use Illuminate\Database\Eloquent\Model;

class DeliveryMethod extends Model
{
    public function deliveryAreas()
    {
        return $this->belongsToMany(DeliveryArea::class, 'delivery_area_delivery_methods')
                    ->withPivot('id', 'uuid', 'custom_price')
                    ->withTimestamps();
    }

    public function deliveryAreaDeliveryMethods()
    {
        return $this->hasMany(DeliveryAreaDeliveryMethod::class);
    }
}
