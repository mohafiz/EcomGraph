<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Order;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Log;

final class Checkout
{
    use ResponseTrait;

    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        try {
            $order = Order::find($args['orderId']);

            if (!$order)
                return $this->badRequest('order not found');

            $order->update([
                'shippingDetails' => $args['shipping'],
                'billingDetails' => $args['billing'],
                'payed' => true
            ]);

            return $this->success();

        } catch (\Throwable $th) {
            Log::error($th);
            return $this->serverError();
        }
    }
}
