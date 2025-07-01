<?php

namespace App\Models\DeliveryLocations;

use Illuminate\Database\Eloquent\Model;

class DeliveryAreaDeliveryMethod extends Model
{
    protected $guarded = [];

    protected $casts = [
        'custom_price' => 'decimal:2',
    ];

    public function deliveryArea()
    {
        return $this->belongsTo(DeliveryArea::class);
    }

    public function deliveryMethod()
    {
        return $this->belongsTo(DeliveryMethod::class);
    }
}
