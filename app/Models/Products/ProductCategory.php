<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductCategory extends Model
{
    protected $guarded = [];

    protected static function booted()
    {
        static::creating(function ($category) {
            $category->uuid = (string) Str::uuid();
            $category->slug = Str::slug($category->name);
        });

        static::updating(function ($category) {
            if ($category->isDirty('name')) {
                $category->slug = Str::slug($category->name);
            }
        });

        static::deleting(function ($category) {
            $image = $category->getRawOriginal('image');

            if ($image && Storage::disk('public')->exists("product-categories/images/{$image}")) {
                Storage::disk('public')->delete("product-categories/images/{$image}");
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function getImageUrlAttribute()
    {
        $image = $this->attributes['image'] ?? null;
        $default_path = asset('assets/images/default-image.jpg');

        if ($image && Storage::disk('public')->exists("product-categories/images/{$image}")) {
            return Storage::url("product-categories/images/{$image}");
        }

        return $default_path;
    }
}
