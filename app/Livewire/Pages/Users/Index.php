<?php

namespace App\Livewire\Pages\Users;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Enums\USER_ROLES;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    use WithPagination;

    public $confirm_user_deletion = false;
    public ?int $delete_user_id = null;

    public string $search = '';
    public bool $search_performed = false;

    protected $listeners = [
        'confirm-user-deletion' => 'confirmUserDeletion',
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

    public function toggleStatus($user_id)
    {
        $user = User::findOrFail($user_id);
        $user->status = !$user->status;
        $user->save();

        $this->dispatch('notify', type: 'success', message: 'Status updated.');
    }

    public function confirmUserDeletion($data)
    {
        $this->delete_user_id = $data['user_id'];
        $this->dispatch('open-modal', 'confirm-user-deletion');
    }

    public function deleteUser()
    {
        if ($this->delete_user_id) {
            $user = User::findOrFail($this->delete_user_id);
            $user->delete();

            $this->delete_user_id = null;
            $this->dispatch('close-modal', 'confirm-user-deletion');
            $this->dispatch('notify', type: 'success', message: 'User deleted.');
        }
    }

    public function render()
    {
        $users = User::query()
            ->visibleToRole(Auth::user()->role)
            ->when($this->search && $this->search_performed, function ($query) {
                $query->where(function($q) {
                    $q->where('email', 'like', '%' . $this->search . '%')
                    ->orWhere('first_name', 'like', '%' . $this->search . '%')
                    ->orWhere('last_name', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('first_name')
            ->paginate(16)
            ->withQueryString();

        return view('livewire.pages.users.index', [
            'users' => $users,
            'count_users' => User::where('role', !USER_ROLES::SUPER_ADMIN)->count(),
            'count_super_admins' => User::where('role', USER_ROLES::SUPER_ADMIN->value)->count(),
            'count_admins' => User::where('role', USER_ROLES::ADMIN->value)->count(),
            'count_owners' => User::where('role', USER_ROLES::OWNER->value)->count(),
        ]);
    }
}
