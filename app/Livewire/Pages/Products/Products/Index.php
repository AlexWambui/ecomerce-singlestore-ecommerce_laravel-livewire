<?php

namespace App\Livewire\Pages\Products\Products;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Products\Product;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public bool $search_performed = false;

    // Include search in URL query string
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

    public function toggleVisibility($product_uuid)
    {
        $product = Product::where('uuid', $product_uuid)->firstOrFail();
        $product->is_visible = !$product->is_visible;
        $product->save();

        $this->dispatch('notify', type: 'success', message: 'Visibility updated.');
    }

    public function toggleFeatured($product_uuid)
    {
        $product = Product::where('uuid', $product_uuid)->firstOrFail();
        $product->is_featured = !$product->is_featured;
        $product->save();

        $this->dispatch('notify', type: 'success', message: 'Featured status updated.');
    }

    public function render()
    {
        $products = Product::query()
            ->with(['productImages', 'productCategory'])
            ->when($this->search && $this->search_performed, function ($query) {
                $query->where(function($q) {
                    $q->where('title', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('title')
            ->paginate(50)
            ->withQueryString();

        $count_products = Product::count();

        return view('livewire.pages.products.products.index', compact('products', 'count_products'));
    }
}
