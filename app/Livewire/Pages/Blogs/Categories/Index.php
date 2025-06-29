<?php

namespace App\Livewire\Pages\Blogs\Categories;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Blogs\BlogCategory;

class Index extends Component
{
    use WithPagination;

    public $confirm_category_deletion = false;
    public ?int $delete_category_id = null;

    public string $search = '';
    public bool $search_performed = false;

    protected $listeners = [
        'confirm-category-deletion' => 'confirmBlogCategoryDeletion',
    ];

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

    public function confirmBlogCategoryDeletion($data)
    {
        $this->delete_category_id = $data['blog_category_id'];
        $this->dispatch('open-modal', 'confirm-category-deletion');
    }

    public function deleteBlogCategory()
    {
        if ($this->delete_category_id) {
            $user = BlogCategory::findOrFail($this->delete_category_id);
            $user->delete();

            $this->delete_category_id = null;
            $this->dispatch('close-modal', 'confirm-category-deletion');
            $this->dispatch('notify', type: 'success', message: 'Blog category deleted successfully');
        }
    }

    public function render()
    {
        $blog_categories = BlogCategory::query()
            ->when($this->search && $this->search_performed, function ($query) {
                $query->where(function($q) {
                    $q->where('title', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('title')
            ->paginate(16)
            ->withQueryString();

        $count_blog_categories = BlogCategory::count();

        return view('livewire.pages.blogs.categories.index', compact('blog_categories', 'count_blog_categories'));
    }
}
