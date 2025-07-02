<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Models\Products\ProductCategory;
use App\Http\Requests\Products\ProductCategoryRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductCategoryController extends Controller
{
    public function create()
    {
        return view('pages.products.categories.create');
    }

    public function store(ProductCategoryRequest $request)
    {
        $validated_data = $request->validated();

        if($request->hasFile('image')) {
            $image = $request->file('image');

            $slug = Str::slug($validated_data['title']);
            $date = now()->format('dmy');
            $random = Str::random(5);
            $extension = $image->getClientOriginalExtension();

            $image_name = "{$slug}-{$date}-{$random}.{$extension}";
            $image->storeAs('product-categories/images', $image_name, 'public');
            $validated_data['image'] = $image_name;
        }

        ProductCategory::create($validated_data);

        return redirect()->route('product-categories.index')->with('success', 'Product Category added successfuly');
    }

    public function edit(ProductCategory $product_category)
    {
        return view('pages.products.categories.edit', compact('product_category'));
    }

    public function update(ProductCategoryRequest $request, ProductCategory $product_category)
    {
        $validated_data = $request->validated();

        $old_slug = Str::slug($product_category->title);
        $new_slug = Str::slug($validated_data['title']);
        $date = now()->format('dmy');
        $random = Str::random(5);

        // Check if image is being replaced
        if ($request->hasFile('image')) {
            // Delete old image
            if ($product_category->image && Storage::disk('public')->exists('product-categories/images/'.$product_category->getRawOriginal('image'))) {
                Storage::disk('public')->delete('product-categories/images/'.$product_category->getRawOriginal('image'));
            }

            // Generate new image name with updated slug
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $image_name = "{$new_slug}-{$date}-{$random}.{$extension}";
            $image->storeAs('product-categories/images', $image_name, 'public');
            $validated_data['image'] = $image_name;
        } elseif ($old_slug !== $new_slug && $product_category->image) {
            // If title changed and no new image was uploaded, rename existing image
            $old_image_name = $product_category->getRawOriginal('image');
            $extension = pathinfo($old_image_name, PATHINFO_EXTENSION);
            $new_image_name = "{$new_slug}-{$date}-{$random}.{$extension}";

            $old_path = "product-categories/images/{$old_image_name}";
            $new_path = "product-categories/images/{$new_image_name}";

            if (Storage::disk('public')->exists($old_path)) {
                Storage::disk('public')->move($old_path, $new_path);
                $validated_data['image'] = $new_image_name;
            }
        }

        $product_category->update($validated_data);

        return redirect()->route('product-categories.index')->with('success', 'Product category updated successfully');
    }
}
