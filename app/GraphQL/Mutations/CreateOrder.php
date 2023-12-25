<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Order;
use App\Models\Product;
use App\Models\Promo;
use App\Models\Status;
use App\Models\User;
use App\Notifications\OrderPlaced;
use App\Traits\ResponseTrait;
use Carbon\Carbon;
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

            $args = $args['input'];
            $orderDetails = $args['products'];

            $totalPrice = 0;
            foreach ($orderDetails as $product)
                $totalPrice += (Product::find($product['productId'])->price * $product['quantity']);

            if (array_key_exists('promoCode', $args))
                $totalPrice = $this->applyPromoCode($totalPrice, $args['promoCode']);
            
            $status = Status::firstOrCreate(['name' => 'pending']);
            $order  = Order::create(['user_id' => auth('sanctum')->id(), 'totalPrice' => $totalPrice, 'status_id' => $status->id]);

            foreach ($orderDetails as $product)
                $order->products()->attach($product['productId'], ['quantity' => $product['quantity']]);

            $user = User::find(auth('sanctum')->id());
            $user->cart()->detach();

            $user->notify(new OrderPlaced($order));

            DB::commit();
            return $order;

        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th);

            return $this->serverError();
        }
    }

    private function applyPromoCode($totalPrice, $promoCode)
    {
        $promo = Promo::where('code', $promoCode)->first();

        if (!$promo)
            return $totalPrice;

        $now = Carbon::now();
        if ($now->lt($promo->startDate) || $now->gt($promo->endDate))
            return $totalPrice;

        if ($totalPrice < $promo->minimumTotal)
            return $totalPrice;

        if ($promo->discountType == 'percentage')
            return $totalPrice - ($totalPrice * ($promo->discount / 100));

        if ($promo->discountType == 'fixed')
            return $totalPrice - $promo->discount;
    }
}
