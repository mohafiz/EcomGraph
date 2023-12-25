<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Product;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Log;

final class RateProduct
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
            $product = Product::find($args['productId']);

            $product->increment('raters');
            $product->increment('rating_sum', $args['rating']);

            $product->update(['rating' => number_format( ($product->rating_sum / $product->raters), 1 )]);
            return $this->success();

        } catch (\Throwable $th) {
            Log::error($th);
            return $this->serverError('Result');
        }
    }
}
