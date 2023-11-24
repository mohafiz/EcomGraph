<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Log;

final class VerifyAccount
{
    use ResponseTrait;

    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        try {
            $user = User::find($args['id']);

            if ($user->code !== $args['code'])
                return $this->badRequest('wrong verification code');

            $user->update(['verified' => true]);
            return $this->success();

        } catch (\Throwable $th) {
            Log::error($th);
            return $this->serverError('Result');
        }
    }
}
