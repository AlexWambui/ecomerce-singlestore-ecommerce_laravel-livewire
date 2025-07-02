<?php

namespace App\Livewire\Pages\General;

use Livewire\Component;
use App\Models\Products\Product;

class HomePage extends Component
{
    public function render()
    {
        $products = Product::select('id', 'uuid', 'title', 'slug', 'price', 'description')->with('productImages')->where('is_featured', true)->take(6)->get();

        return view('livewire.pages.general.home-page', compact('products'))->layout('layouts.guest');
    }
}
