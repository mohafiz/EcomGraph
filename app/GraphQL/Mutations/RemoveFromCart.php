<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Product;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Log;

final class RemoveFromCart
{
    use ResponseTrait;
    
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        try {
            $product = Product::find($args['id']);
            $user = User::find(auth('sanctum')->id());

            if (!$user->cart->contains($product->id))
                return $this->badRequest('You didn\'t add this product to your cart');

            $user->cart()->detach($product->id);
            $product->increment('stock');

            return $this->success();

        } catch (\Throwable $th) {
            Log::error($th);
            return $this->serverError();
        }
    }
}
