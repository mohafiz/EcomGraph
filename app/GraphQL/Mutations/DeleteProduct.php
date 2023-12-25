<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Product;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class DeleteProduct
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

            if ($product->photo)
                Storage::delete(Str::replaceFirst('storage', 'public', $product->photo));

            $product->delete();
            return $this->success();
            
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->serverError();
        }
    }
}
