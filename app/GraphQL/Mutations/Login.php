<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

final class Login
{
    use ResponseTrait;

    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        try {
            $user = User::where('email', $args['email'])->first();

            if (!$user || !Hash::check($args['password'], $user->password))
                return $this->badRequest('check your email and password', 'Result');

            if (!$user->verified)
                return $this->badRequest('your account is not verified', 'Result');

            if (!$user->subscribed)
                return $this->badRequest('you are not subscribed to our telegram bot, subscribe here @ecomgraphbot', 'Result');

            $token = $user->createToken('access_token')->plainTextToken;

            return [
                '__typename' => 'UserData',
                'success' => true,
                'user' => $user,
                'token' => $token
            ];

        } catch (\Throwable $th) {
            Log::error($th);
            return $this->serverError('Result');
        }
    }
}
