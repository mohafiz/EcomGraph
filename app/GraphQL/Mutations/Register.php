<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Mail\VerificationCodeMail;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

final class Register
{
    use ResponseTrait;

    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        try {
            $hashedPassword = Hash::make($args['password']);
            
            // create a random verification code
            $code = str_pad((string) rand(0000, 9999), 4, '0', STR_PAD_LEFT);

            $data = [
                'name'     => $args['name'],
                'email'    => $args['email'],
                'password' => $hashedPassword,
                'role'     => 2,
                'code'     => $code
            ];

            $user = User::create($data);

            // send verification code
            Mail::to($user)->send(new VerificationCodeMail($code));
            
            return [
                '__typename' => 'UserData',
                'success' => true,
                'user' => $user,
            ];

        } catch (\Throwable $th) {
            Log::error($th);
            return $this->serverError('Result');
        }
    }
}
