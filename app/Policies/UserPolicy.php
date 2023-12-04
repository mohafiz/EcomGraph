<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Log;

class UserPolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, $passedId): bool
    {
        Log::info($passedId);
        return $user->id === $passedId;
    }
}
