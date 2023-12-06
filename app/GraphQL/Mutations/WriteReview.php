<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Review;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Log;

final class WriteReview
{
    use ResponseTrait;

    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        try {
            Review::create([
                'user_id' => auth('sanctum')->id(),
                'product_id' => $args['productId'],
                'review' => $args['review']
            ]);

            return $this->success();

        } catch (\Throwable $th) {
            Log::error($th);
            return $this->serverError();
        }
    }
}
