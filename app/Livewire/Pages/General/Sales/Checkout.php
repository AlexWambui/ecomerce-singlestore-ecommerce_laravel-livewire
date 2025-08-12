<?php

namespace App\Livewire\Pages\General\Sales;

use Livewire\Component;
use App\Services\CartService;

class Checkout extends Component
{
    public $cart_items;
    public $cart_count = 0;

    public function mount(CartService $cart)
    {
        $this->cart_items = $cart->getItems();
        $this->cart_count = $cart->count();
    }

    public function render()
    {
        return view('livewire.pages.general.sales.checkout')->layout('layouts.guest');
    }
}
