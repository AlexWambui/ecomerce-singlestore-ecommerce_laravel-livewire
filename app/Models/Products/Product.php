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

            if(empty($product->slug)) {
                $product->slug = static::generateUniqueSlug($product->title);
            }
        });

        static::updating(function ($product) {
            $original_title = $product->getOriginal('title');
            $original_slug = $product->getOriginal('slug');

            if ($product->isDirty('title') || empty($product->slug)) {
                $new_slug = static::generateUniqueSlug($product->title, $product->id);
                $product->slug = $new_slug;

                // Rename associated image files
                foreach ($product->productImages as $image) {
                    $old_filename = $image->image;

                    // Only handle files that contain the old slug
                    if (Str::startsWith($old_filename, $original_slug)) {
                        $extension = pathinfo($old_filename, PATHINFO_EXTENSION);
                        $random = Str::random(6);
                        $new_filename = $new_slug . '-' . $random . '.' . $extension;

                        $old_path = "products/images/{$old_filename}";
                        $new_path = "products/images/{$new_filename}";

                        if (Storage::disk('public')->exists($old_path)) {
                            Storage::disk('public')->move($old_path, $new_path);
                            $image->update(['image' => $new_filename]);
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

    /**
     * Generate a unique slug for a given title.
     */
    protected static function generateUniqueSlug(string $title, $ignore_id = null): string
    {
        $base_slug = Str::slug($title);
        $slug = $base_slug;
        $i = 1;

        while (
            static::query()
                ->where('slug', $slug)
                ->when($ignore_id, fn ($query) => $query->where('id', '!=', $ignore_id))
                ->exists()
        ) {
            $slug = $base_slug . '-' . $i++;
        }

        return $slug;
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
