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
            $args = $args['input'];
            $hashedPassword = Hash::make($args['password']);
            
            if (array_key_exists('telegram_id', $args)) {

                $telegramId = $args['telegram_id'];
                $user = User::where('chat_id', $telegramId)->first();

                if (!$user)
                    return $this->badRequest('Your telegram ID is not correct', 'Result');

                if ($user->name != null && $user->email != null)
                    return $this->badRequest('You have already registered with us with this email', 'Result');

                $code = str_pad((string) rand(0000, 9999), 4, '0', STR_PAD_LEFT);
                $user->update([
                    'name'       => $args['name'],
                    'email'      => $args['email'],
                    'password'   => $hashedPassword,
                    'code'       => $code,
                    'registered' => true
                ]);

                Mail::to($user->email)->send(new VerificationCodeMail($code));

                return [
                    '__typename' => 'UserData',
                    'success' => true,
                    'user' => $user,
                ];
            }

            $code = str_pad((string) rand(0000, 9999), 4, '0', STR_PAD_LEFT);
            $data = [
                'name'     => $args['name'],
                'email'    => $args['email'],
                'password' => $hashedPassword,
                'role'     => 2,
                'code'     => $code
            ];

            $user = User::create($data);

            Mail::to($user->email)->send(new VerificationCodeMail($code));
            return $this->success('Result');

        } catch (\Throwable $th) {
            Log::error($th);
            return $this->serverError('Result');
        }
    }
}
