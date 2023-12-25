<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Order;
use App\Notifications\OrderStatusUpdated;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Log;

final class UpdateOrderStatus
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
            
            $order = Order::find($args['orderId']);
            $order->update(['status_id' => $args['statusId']]);

            $user = $order->user;
            $user->notify(new OrderStatusUpdated($order->id, $order->status->name));
            
            return $this->success();

        } catch (\Throwable $th) {
            Log::error($th);
            return $this->serverError();
        }
    }
}
