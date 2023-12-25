<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Log;

final class RemoveFromWishlist
{
    use ResponseTrait;

    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        try {
            $args = $args['input'];
            
            $user = User::find(auth('sanctum')->id());

            if (!$user->wishlist->contains($args['productId']))
                return $this->badRequest('You didn\'t add this product to your wishlist');

            $user->wishlist()->detach($args['productId']);
            return $this->success();

        } catch (\Throwable $th) {
            Log::error($th);
            return $this->serverError();
        }
    }
}
