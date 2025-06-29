<?php

namespace App\Livewire\Pages\Blogs\Blogs;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Blogs\Blog;

class Index extends Component
{
    use WithPagination;

    public $confirm_blog_deletion = false;
    public ?int $delete_blog_id = null;

    public string $search = '';
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

    public function confirmBlogDeletion($data)
    {
        $this->delete_blog_id = $data['blog_id'];
        $this->dispatch('open-modal', 'confirm-blog-deletion');
    }

    public function deleteBlog()
    {
        if ($this->delete_blog_id) {
            $user = Blog::findOrFail($this->delete_blog_id);
            $user->delete();

            $this->delete_blog_id = null;
            $this->dispatch('close-modal', 'confirm-blog-deletion');
            $this->dispatch('notify', type: 'success', message: 'Blog deleted successfully');
        }
    }

    public function render()
    {
        $blogs = Blog::query()
            ->when($this->search && $this->search_performed, function ($query) {
                $query->where(function($q) {
                    $q->where('title', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('title')
            ->paginate(16)
            ->withQueryString();

        return view('livewire.pages.blogs.blogs.index', compact('blogs'));
    }
}
