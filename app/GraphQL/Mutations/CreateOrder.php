<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class CreateOrder
{
    use ResponseTrait;

    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        try {
            DB::beginTransaction();

            $orderDetails = $args['products'];

            $totalPrice = 0;
            foreach ($orderDetails as $product)
                $totalPrice += (Product::find($product['productId'])->price * $product['quantity']);
            
            $order = Order::create(['user_id'  => auth('sanctum')->id(), 'totalPrice' => $totalPrice]);

            foreach ($orderDetails as $product)
                $order->products()->attach($product['productId'], ['quantity' => $product['quantity']]);

            DB::commit();
            return $order;

        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th);

            return $this->serverError();
        }
    }
}
