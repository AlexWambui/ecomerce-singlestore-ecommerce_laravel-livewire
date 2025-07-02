<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    protected $guarded = [];

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
