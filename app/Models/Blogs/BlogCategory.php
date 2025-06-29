<?php

namespace App\Models\Blogs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BlogCategory extends Model
{
    protected static function booted()
    {
        static::saving(function ($category) {
            // Always keep slug unique and in sync with title
            $slug = Str::slug($category->title);
            $count = Blog::where('slug', $slug)->count();
            $category->slug = $count ? "{$slug}-{$count}" : $slug;

            // Only generate uuid on create
            if(!$category->uuid) {
                $category->uuid = (string) Str::uuid();
            }
        });

        static::deleting(function ($category) {
            $image = $category->getRawOriginal('image');

            // Delete image from storage
            if ($image && Storage::disk('public')->exists("blog-categories/images/{$image}")) {
                Storage::disk('public')->delete("blog-categories/images/{$image}");
            }
        });
    }

    protected $guarded = [];

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function blogs(): HasMany
    {
        return $this->hasMany(Blog::class);
    }

    public function getImageUrlAttribute()
    {
        $image = $this->attributes['image'] ?? null;
        $default_path = asset('assets/images/default-image.jpg');

        if ($image && Storage::disk('public')->exists("blog-categories/images/{$image}")) {
            return Storage::url("blog-categories/images/{$image}");
        }

        return $default_path;
    }
}
