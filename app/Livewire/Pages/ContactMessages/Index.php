<?php

namespace App\Livewire\Pages\ContactMessages;

use Livewire\Component;
use App\Models\ContactMessage;
use Livewire\WithPagination;

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
        $messages = ContactMessage::query()
            ->when($this->search && $this->search_performed, function ($query) {
                $query->where(function($q) {
                    $q->where('email', 'like', '%' . $this->search . '%')
                    ->orWhere('name', 'like', '%' . $this->search . '%');
                });
            })
            ->latest()
            ->paginate(150)
            ->withQueryString();

        $count_messages = ContactMessage::count();
        $count_unread = ContactMessage::where('is_read', false)->count();
        $count_important = ContactMessage::where('is_important', true)->count();

        return view('livewire.pages.contact-messages.index', compact('messages', 'count_messages', 'count_unread', 'count_important'));
    }
}
