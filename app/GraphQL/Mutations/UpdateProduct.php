<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Product;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Log;

final class UpdateProduct
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

            $product->update([
                'name' => array_key_exists('name', $args) ? $args['name'] : $product->name,
                'price' => array_key_exists('price', $args) ? $args['price'] : $product->price,
                'stock' => array_key_exists('stock', $args) ? $args['stock'] : $product->stock,
                'description' => array_key_exists('description', $args) ? $args['description'] : $product->description,
            ]);

            return [
                '__typename' => 'ProductData',
                'success' => true,
                'product' => $product
            ];

        } catch (\Throwable $th) {
            Log::error($th);
            return $this->serverError('Result');
        }
    }
}
