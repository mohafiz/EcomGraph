<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Product;
use App\Notifications\ProductRestocked;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Log;

final class RestockProduct
{
    use ResponseTrait;

    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        try {
            $product = Product::find($args['productId']);
            $product->update(['stock' => $args['stock']]);

            $users = $product->wishlisted;

            foreach ($users as $user) {
                if ($user->chat_id)
                    $user->notify(new ProductRestocked($product));
            }

            return $this->success();

        } catch (\Throwable $th) {
            Log::error($th);
            return $this->serverError();
        }
    }
}
