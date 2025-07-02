<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Models\Products\Product;
use App\Models\Products\ProductCategory;
use App\Http\Requests\Products\ProductRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ProductController extends Controller
{
    public function create()
    {
        $categories = ProductCategory::get();

        return view('pages.products.products.create', compact('categories'));
    }

    public function store(ProductRequest $request)
    {
        DB::beginTransaction();

        try {
            $validated_data = $request->validated();
            unset($validated_data['images']);

            $validated_data['is_featured'] = $request->boolean('is_featured');
            $validated_data['is_visible'] = $request->has('is_visible');

            // Basic fallbacks
            $validated_data['stock_count'] = $validated_data['stock_count'] ?? 0;
            $validated_data['safety_stock'] = $validated_data['safety_stock'] ?? 0;
            $validated_data['sort_order'] = $validated_data['sort_order'] ?? 200;

            $product = Product::create($validated_data);

            // Handle image uploads
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $filename = $product->slug . '-' . Str::random(6) . '.' . $image->getClientOriginalExtension();
                    $image->storeAs('products/images', $filename, 'public');
                    $product->productImages()->create(['image' => $filename]);
                }
            }

            DB::commit();

            return redirect()->route('products.index')->with('success', 'Product created successfully');
        } catch(Throwable $e) {
            DB::rollback();

            if (app()->isLocal()) {
                dd($e->getMessage(), $e->getTraceAsString());
            }

            report($e);

            return back()->withInput()->with('error', 'An error occured while saving the product');
        }
    }

    public function edit(Product $product)
    {
        $categories = ProductCategory::get();
        $product->load('productImages');

        return view('pages.products.products.edit', compact('product', 'categories'));
    }

    public function update(ProductRequest $request, Product $product)
    {
        DB::beginTransaction();

        try {
            $validated_data = $request->validated();
            unset($validated_data['images']);

            $validated_data['is_featured'] = $request->boolean('is_featured');
            $validated_data['is_visible'] = $request->has('is_visible');

            // Basic fallbacks
            $validated_data['is_featured'] = $request->boolean('is_featured');
            $validated_data['is_visible'] = $request->has('is_visible');
            $validated_data['stock_count'] = $validated_data['stock_count'] ?? 0;
            $validated_data['safety_stock'] = $validated_data['safety_stock'] ?? 0;
            $validated_data['sort_order'] = $validated_data['sort_order'] ?? 200;

            $product->update($validated_data);

            // Handle image uploads
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $filename = $product->slug . '-' . Str::random(6) . '.' . $image->getClientOriginalExtension();
                    $image->storeAs('products/images', $filename, 'public');
                    $product->productImages()->create(['image' => $filename]);
                }
            }

            DB::commit();

            return redirect()->route('products.index')->with('success', 'Product updated successfully');
        } catch(Throwable $e) {
            DB::rollback();

            if (app()->isLocal()) {
                dd($e->getMessage(), $e->getTraceAsString());
            }

            report($e);

            return back()->withInput()->with('error', 'An error occured while updating the product');
        }
    }
}
