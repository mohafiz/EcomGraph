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
                'name_ar' => array_key_exists('name_ar', $args) ? $args['name_ar'] : $product->name_ar,
                'name_es' => array_key_exists('name_es', $args) ? $args['name_es'] : $product->name_es,
                'name_fr' => array_key_exists('name_fr', $args) ? $args['name_fr'] : $product->name_fr,
                'price' => array_key_exists('price', $args) ? $args['price'] : $product->price,
                'stock' => array_key_exists('stock', $args) ? $args['stock'] : $product->stock,
                'description' => array_key_exists('description', $args) ? $args['description'] : $product->description,
                'description_ar' => array_key_exists('description_ar', $args) ? $args['description_ar'] : $product->description_ar,
                'description_es' => array_key_exists('description_es', $args) ? $args['description_es'] : $product->description_es,
                'description_fr' => array_key_exists('description_fr', $args) ? $args['description_fr'] : $product->description_fr,
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
