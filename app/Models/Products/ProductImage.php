<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProductImage extends Model
{
    protected $guarded = [];

    protected static function booted()
    {
        static::creating(function ($product) {
            $product->uuid = (string) Str::uuid();
        });
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
