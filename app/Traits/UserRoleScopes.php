<?php

namespace App\Traits;

use App\Enums\UserRoles;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait UserRoleScopes
{
    /**
     * Scope to return users visible based on the role of the authenticated user.
     */

    public function scopeVisibleToRole(Builder $query, UserRoles $role): Builder
    {
        return match($role) {
            UserRoles::SUPER_ADMIN => $query, // See everyone
            UserRoles::ADMIN => $query->whereIn('role', [
                UserRoles::ADMIN,
                UserRoles::OWNER,
                UserRoles::CUSTOMER,
            ]),
            UserRoles::CUSTOMER => $query->where('id', Auth::user()->id), // See self only
        };
    }
}
