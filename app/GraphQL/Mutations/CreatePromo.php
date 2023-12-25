<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Promo;
use App\Models\User;
use App\Notifications\NewPromo;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Log;

final class CreatePromo
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
            $code = $this->generatePromoCode();
            
            $promo = Promo::create([
                'code' => $code,
                'discountType' => $args['discountType'],
                'discount' => $args['discount'],
                'minimumTotal' => $args['minimumTotal'],
                'startDate' => $args['startDate'],
                'endDate' => $args['endDate']
            ]);

            $recepients = User::whereNotNull('chat_id')->get();

            foreach ($recepients as $recepient) {
                if ($recepient->chat_id)
                    $recepient->notify(new NewPromo($promo));
            }

            return [
                '__typename' => 'PromoData',
                'success' => true,
                'promo' => $promo
            ];

        } catch (\Throwable $th) {
            Log::error($th);
            return $this->serverError('Result');
        }
    }

    private function generatePromoCode($length = 6) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
    
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
    
        return $randomString;
    }
}
