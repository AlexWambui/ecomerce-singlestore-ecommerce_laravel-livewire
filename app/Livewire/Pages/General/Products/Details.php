<?php

namespace App\Livewire\Pages\General\Products;

use Livewire\Component;
use App\Models\Products\Product;

class Details extends Component
{
    public $product;
    public $related_products;

    public function mount($slug)
    {
        $this->product = Product::with(['productImages', 'productCategory', 'productReviews'])->where('slug', $slug)->firstOrFail();

        $this->related_products = Product::with('productImages')
            ->where('product_category_id', $this->product->product_category_id)
            ->where('id', '!=', $this->product->id)
            ->inRandomOrder()
            ->limit(6)
            ->get();
    }

    public function render()
    {
        return view('livewire.pages.general.products.details')->layout('layouts.guest');
    }
}
