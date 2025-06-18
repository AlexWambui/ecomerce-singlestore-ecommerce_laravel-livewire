<?php

namespace App\Livewire\Pages\Users;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Enums\USER_STATUSES;
use App\Enums\USER_ROLES;

class Index extends Component
{
    use WithPagination;

    public $confirm_user_deletion = false;
    public $user_to_delete = null;
    public ?int $delete_user_id = null;

    protected $listeners = [
        'confirm-user-deletion' => 'confirmUserDeletion',
    ];

    public function toggleStatus($user_id)
    {
        $user = User::findOrFail($user_id);
        $user->status = match($user->status->value) {
            USER_STATUSES::ACTIVE->value => USER_STATUSES::INACTIVE->value,
            USER_STATUSES::INACTIVE->value => USER_STATUSES::BANNED->value,
            USER_STATUSES::BANNED->value => USER_STATUSES::ACTIVE->value,
            default => USER_STATUSES::ACTIVE->value,
        };
        $user->save();

        $this->dispatch('notify', type: 'success', message: 'status updated');
    }

    public function confirmUserDeletion($data)
    {
        $this->delete_user_id = $data['user_id'];
        $this->dispatch('open-modal', 'confirm-user-deletion');
    }

    public function deleteUser()
    {
        if($this->delete_user_id) {
            $user = User::findOrFail($this->delete_user_id);
            if($user) {
                $user->delete();

                $this->delete_user_id = null;
                $this->dispatch('close-modal', 'confirm-user-deletion');
                $this->dispatch('notify', type: 'success', message: 'user is deleted');
            }
        }
    }

    public function render()
    {
        $users = User::orderBy('first_name')->paginate(50);
        $count_users = User::count();
        $count_super_admins = User::where('role', USER_ROLES::SUPER_ADMIN->value)->count();
        $count_admins = User::where('role', USER_ROLES::ADMIN->value)->count();
        $count_owners = User::where('role', USER_ROLES::OWNER->value)->count();

        return view('livewire.pages.users.index', compact(
            'users',
            'count_users',
            'count_super_admins',
            'count_admins',
            'count_owners',
        ));
    }
}
