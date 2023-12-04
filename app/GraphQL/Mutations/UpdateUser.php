<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

final class UpdateUser
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
            
            $hashedPassword = null;

            if (array_key_exists('password', $args))
                $hashedPassword = Hash::make($args['password']);

            $user->update([
                'name' => array_key_exists('name', $args) ? $args['name'] : $user->name,
                'email' => array_key_exists('email', $args) ? $args['email'] : $user->name,
                'password' => $hashedPassword ?: $user->password
            ]);

            return $this->success();

        } catch (\Throwable $th) {
            Log::error($th);
            return $this->serverError();
        }
    }
}
