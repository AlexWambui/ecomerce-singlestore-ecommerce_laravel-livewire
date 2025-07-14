<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\Products\Product;

class CartItem extends Model
{
    protected $guarded = [];

    protected static function booted()
    {
        static::creating(function ($cart_item) {
            $cart_item->uuid = (string) Str::uuid();
        });
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
