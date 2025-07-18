<?php

namespace App\Livewire\Pages\General;

use Livewire\Component;
use App\Models\Products\Product;
use App\Services\CartService;

class HomePage extends Component
{
    public function addToCart(int $product_id): void
    {
        app(CartService::class)->add($product_id);

        $this->dispatch('cart-updated');

        $this->dispatch('notify', 'Added to cart', 'success');
    }

    public function render()
    {
        $products = Product::query()
            ->select('id', 'uuid', 'title', 'slug', 'discount_price', 'selling_price', 'stock_count', 'product_category_id')
            ->with(['productImages', 'productCategory'])
            ->where('is_visible', true)
            ->where('is_featured', true)
            ->take(9)
            ->get();

            // dd($products);

        return view('livewire.pages.general.home-page', compact('products'))->layout('layouts.guest');
    }
}
