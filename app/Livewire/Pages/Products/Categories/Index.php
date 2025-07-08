<?php

namespace App\Livewire\Pages\Products\Categories;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Products\ProductCategory;

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

    public function render()
    {
        $categories = ProductCategory::query()
            ->withCount('products')
            ->when($this->search && $this->search_performed, function ($query) {
                $query->where(function($q) {
                    $q->where('title', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('title')
            ->paginate(50)
            ->withQueryString();

        $count_categories = ProductCategory::count();

        return view('livewire.pages.products.categories.index', compact('categories', 'count_categories'));
    }
}
