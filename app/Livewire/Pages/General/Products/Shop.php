<?php

namespace App\Livewire\Pages\General\Products;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Products\Product;
use App\Models\Products\ProductCategory;

class Shop extends Component
{
    use WithPagination;

    public function render()
    {
        $categories = ProductCategory::orderBy('title')->get();

        $products = Product::query()
            ->select('id', 'title', 'slug', 'discount_price', 'selling_price', 'stock_count')
            ->with('productImages')
            ->where('is_visible', true)
            ->paginate(12);

        $count_products = Product::where('is_visible', true)->count();

        return view('livewire.pages.general.products.shop', compact('categories', 'products', 'count_products'))->layout('layouts.guest');
    }
}
