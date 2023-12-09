<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Promo;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Log;

final class UpdatePromo
{
    use ResponseTrait;

    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        try {
            $promo = Promo::find($args['promoId']);

            $promo->update([
                'code' => array_key_exists('code', $args) ? $args['code'] : $promo->code,
                'discountType' => array_key_exists('discountType', $args) ? $args['discountType'] : $promo->discountType,
                'discount' => array_key_exists('discount', $args) ? $args['discount'] : $promo->discount,
                'minimumTotal' => array_key_exists('minimumTotal', $args) ? $args['minimumTotal'] : $promo->minimumTotal,
                'startDate' => array_key_exists('startDate', $args) ? $args['startDate'] : $promo->startDate,
                'endDate' => array_key_exists('endDate', $args) ? $args['endDate'] : $promo->endDate
            ]);

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
}
