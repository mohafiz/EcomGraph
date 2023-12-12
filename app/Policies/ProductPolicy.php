<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProductPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role == 1;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): bool
    {
        return $user->role == 1;
    }

    /**
     * Determine whether the user can upload the model's photo.
     */
    public function upload(User $user): bool
    {
        return $user->role == 1;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        return $user->role == 1;
    }

    /**
     * Determine whether the user can get the out of stock products.
     */
    public function getOutOfStock(User $user): bool
    {
        return $user->role == 1;
    }

    /**
     * Determine whether the user can restock a product.
     */
    public function restock(User $user): bool
    {
        return $user->role == 1;
    }
}
