<?php

namespace App\Http\Controllers\Blogs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blogs\BlogCategory;
use App\Http\Requests\Blogs\BlogCategoryRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BlogCategoryController extends Controller
{
    public function create()
    {
        return view('pages.blogs.categories.create');
    }

    public function store(BlogCategoryRequest $request)
    {
        $validated_data = $request->validated();

        if($request->hasFile('image')) {
            $image = $request->file('image');

            $slug = Str::slug($validated_data['title']);
            $date = now()->format('dmy');
            $random = Str::random(5);
            $extension = $image->getClientOriginalExtension();

            $image_name = "{$slug}-{$date}-{$random}.{$extension}";
            $image->storeAs('blog-categories/images', $image_name, 'public');
            $validated_data['image'] = $image_name;
        }

        BlogCategory::create($validated_data);

        return redirect()->route('blog-categories.index')->with('success', 'Blog Category added successfully');
    }

    public function edit(BlogCategory $blog_category)
    {
        return view('pages.blogs.categories.edit', compact('blog_category'));
    }

    public function update(BlogCategoryRequest $request, BlogCategory $blog_category)
    {
        $validated_data = $request->validated();

        $old_slug = Str::slug($blog_category->title);
        $new_slug = Str::slug($validated_data['title']);
        $date = now()->format('dmy');
        $random = Str::random(5);

        // Check if image is being replaced
        if ($request->hasFile('image')) {
            // Delete old image
            if ($blog_category->image && Storage::disk('public')->exists('blog-categories/images/'.$blog_category->getRawOriginal('image'))) {
                Storage::disk('public')->delete('blog-categories/images/'.$blog_category->getRawOriginal('image'));
            }

            // Generate new image name with updated slug
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $image_name = "{$new_slug}-{$date}-{$random}.{$extension}";
            $image->storeAs('blog-categories/images', $image_name, 'public');
            $validated_data['image'] = $image_name;
        } elseif ($old_slug !== $new_slug && $blog_category->image) {
            // If title changed and no new image was uploaded, rename existing image
            $old_image_name = $blog_category->getRawOriginal('image');
            $extension = pathinfo($old_image_name, PATHINFO_EXTENSION);
            $new_image_name = "{$new_slug}-{$date}-{$random}.{$extension}";

            $old_path = "blog-categories/images/{$old_image_name}";
            $new_path = "blog-categories/images/{$new_image_name}";

            if (Storage::disk('public')->exists($old_path)) {
                Storage::disk('public')->move($old_path, $new_path);
                $validated_data['image'] = $new_image_name;
            }
        }

        $blog_category->update($validated_data);

        return redirect()->route('blog-categories.index')->with('success', 'Blog category updated successfully.');
    }
}
