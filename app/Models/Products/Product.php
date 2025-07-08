<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    protected $guarded = [];

    protected static function booted()
    {
        static::creating(function ($product) {
            $product->uuid = (string) Str::uuid();
            $product->slug = Str::slug($product->title);
        });

        static::updating(function ($product) {
            if ($product->isDirty('title')) {
                $original_slug = $product->getOriginal('slug');
                $new_slug = Str::slug($product->title);

                $product->slug = $new_slug;

                // Rename associated image files
                foreach ($product->productImages as $image) {
                    $oldFilename = $image->image;

                    // Only handle files that contain the old slug
                    if (Str::startsWith($oldFilename, $original_slug)) {
                        $extension = pathinfo($oldFilename, PATHINFO_EXTENSION);
                        $random = Str::random(6);
                        $newFilename = $new_slug . '-' . $random . '.' . $extension;

                        $oldPath = "products/images/{$oldFilename}";
                        $newPath = "products/images/{$newFilename}";

                        if (Storage::disk('public')->exists($oldPath)) {
                            Storage::disk('public')->move($oldPath, $newPath);

                            // Update image name in DB
                            $image->update(['image' => $newFilename]);
                        }
                    }
                }
            }
        });

        static::deleting(function ($product) {
            foreach($product->productImages as $image) {
                $path = "products/images/{$image->image}";

                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }
        });
    }

    public function casts(): array
    {
        return [
            'is_visible' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function productImages()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function productReviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function productViews()
    {
        return $this->hasMany(ProductView::class);
    }

    public function getIsVisibleLabelAttribute(): string
    {
        return $this->is_visible ? 'Visible' : 'Invisible';
    }

    public function getIsFeaturedLabelAttribute(): string
    {
        return $this->is_featured ? 'Featured' : 'Not Featured';
    }

    public function getImageUrlAttribute()
    {
        // Use already loaded relationship if available, otherwise query
        $image = $this->relationLoaded('productImages')
            ? $this->productImages->sortBy('sort_order')->first()
            : $this->productImages()->orderBy('sort_order')->first();

        if ($image && Storage::disk('public')->exists("products/images/{$image->image}")) {
            return Storage::url("products/images/{$image->image}");
        }
    }

    public function getDiscountPercentageAttribute(): int
    {
        if ($this->discount_price && $this->discount_price < $this->selling_price) {
            $percentage = (($this->selling_price - $this->discount_price) / $this->selling_price) * 100;
            return round($percentage);
        }

        return 0;
    }

    public function getEffectivePriceAttribute(): float
    {
        if ($this->discount_price && $this->discount_price < $this->selling_price) {
            return (float) $this->discount_price;
        }

        return (float) ($this->selling_price ?? 0);
    }
}
