<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class OrderPolicy
{
    /**
     * Determine whether the user can view all orders.
     */
    public function viewAll(User $user): bool
    {
        return $user->role == 1;
    }

    /**
     * Determine whether the user can update order status.
     */
    public function updateStatus(User $user): bool
    {
        return $user->role == 1;
    }
}
