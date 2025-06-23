<?php

namespace App\Traits;

use App\Enums\USER_ROLES;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait UserRoleScopes
{
    /**
     * Scope to return users visible based on the role of the authenticated user.
     */

    public function scopeVisibleToRole(Builder $query, USER_ROLES $role): Builder
    {
        return match($role) {
            USER_ROLES::SUPER_ADMIN => $query, // See everyone
            USER_ROLES::ADMIN => $query->whereIn('role', [
                USER_ROLES::ADMIN,
                USER_ROLES::OWNER,
                USER_ROLES::CUSTOMER,
            ]),
            USER_ROLES::CUSTOMER => $query->where('id', Auth::user()->id), // See self only
        };
    }
}
