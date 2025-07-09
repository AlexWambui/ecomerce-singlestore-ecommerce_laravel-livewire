<?php

namespace App\Livewire\Pages\General\Products;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Products\Product;
use App\Models\Products\ProductCategory;

class Shop extends Component
{
    use WithPagination;

    public string $search = '';
    public bool $search_performed = false;
    protected $queryString = ['search'];

    // Reset page when search input changes
    public function performSearch()
    {
        $this->search_performed = true;
        $this->resetPage();
    }

    public function resetSearch()
    {
        $this->search = '';
        $this->search_performed = false;
        $this->resetPage();
    }

    public function render()
    {
        $categories = ProductCategory::orderBy('title')->get();

        $products = Product::query()
            ->select('id', 'uuid', 'title', 'slug', 'discount_price', 'selling_price', 'stock_count', 'product_category_id')
            ->with(['productImages', 'productCategory'])
            ->when($this->search && $this->search_performed, function ($query) {
                $query->where(function($q) {
                    $q->where('title', 'like', '%' . $this->search . '%');
                });
            })
            ->where('is_visible', true)
            ->paginate(30);

        $count_products = Product::where('is_visible', true)->count();

        return view('livewire.pages.general.products.shop', compact('categories', 'products', 'count_products'))->layout('layouts.guest');
    }
}
