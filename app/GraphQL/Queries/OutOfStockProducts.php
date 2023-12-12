<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\Product;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Log;

final class OutOfStockProducts
{
    use ResponseTrait;
    
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        try {
            $products = Product::where('stock', 0)->get();
            return $products;

        } catch (\Throwable $th) {
            Log::error($th);
            return $this->serverError();
        }
    }
}
