<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\User;
use App\Models\Order;

class OrderPolicy
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

    public function manageOrder(User $user,Order $order)
    {
        $roles = $user->group->role->role_permissions;
        foreach ($roles as $role) {
            if($role->permission->name=='orders')
                return true;
        }
        return false;
    }
}
