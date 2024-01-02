<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Product;
use App\Services\ElasticSearchService;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Log;

final class CreateProduct
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
            $product = Product::create($args);

            $elasticSearch = new ElasticSearchService();
            $elasticSearch->index('products', $product);

            return [
                '__typename' => 'ProductData',
                'success' => true,
                'product' => $product,
            ];
            
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->serverError('Result');
        }
    }
}
