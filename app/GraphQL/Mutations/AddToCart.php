<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Product;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Log;

final class AddToCart
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

            if ($product->stock == 0)
                return $this->badRequest('Stock is 0 right now !');

            $user = User::find(auth('sanctum')->id());

            if ($user->cart->contains($product->id))
                return $this->badRequest('Product already added to your cart');

            $user->cart()->attach($product->id);
            $product->decrement('stock');

            return $this->success();

        } catch (\Throwable $th) {
            Log::error($th);
            return $this->serverError();
        }
    }
}
