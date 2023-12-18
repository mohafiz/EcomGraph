<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Log;

final class SetDefaultLanguage
{
    use ResponseTrait;

    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        try {
            $user = User::find(auth('sanctum')->id());
            $user->update(['default_language' => $args['lang']]);

            return $this->success();
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->serverError();
        }
    }
}
