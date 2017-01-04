<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\User;


class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function manageUser(User $user,User $manage_user)
    {
        $roles = $user->group->role->role_permissions;
        foreach ($roles as $role) {
            if($role->permission->name=='users')
                return true;
        }
        return false;
    }
}
