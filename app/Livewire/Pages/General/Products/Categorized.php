<?php

namespace App\Livewire\Pages\General\Products;

use Livewire\Component;
use App\Models\Products\Product;
use App\Models\Products\ProductCategory;

class Categorized extends Component
{
    public $category;
    public $categories;
    public $products = [];

    public function mount($slug)
    {
        $this->categories = ProductCategory::select('id', 'title', 'slug')->orderBy('title')->get();

        $this->category = ProductCategory::query()
            ->withCount('products')
            ->where('slug', $slug)
            ->firstOrFail();

        $this->products = Product::query()
            ->with('productCategory')
            ->where('product_category_id', $this->category->id)
            ->latest()
            ->get();
    }

    public function render()
    {
        return view('livewire.pages.general.products.categorized')->layout('layouts.guest');
    }
}
