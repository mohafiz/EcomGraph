<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Order;
use App\Models\Status;
use App\Models\User;
use App\Notifications\OrderPayed;
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

            if ($order->status->name != 'pending')
                return $this->badRequest('this order has already payed for');

            $status = Status::firstOrCreate(['name' => 'payed']);

            $order->update([
                'shippingDetails' => $args['shipping'],
                'billingDetails' => $args['billing'],
                'status_id' => $status->id
            ]);

            $user = User::find(auth('sanctum')->id());
            $user->notify(new OrderPayed($order));

            return $this->success();

        } catch (\Throwable $th) {
            Log::error($th);
            return $this->serverError();
        }
    }
}
