<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Currency;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Log;

final class SetDefaultCurrency
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
            
            $user = User::find(auth('sanctum')->id());
            $currency = Currency::where('code', $args['currency'])->first();

            $user->update(['currency_id' => $currency->id]);
            return $this->success();

        } catch (\Throwable $th) {
            Log::error($th);
            return $this->serverError();
        }
    }
}
