<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        try {
            $hashedPassword = Hash::make($request->password);

            $data = [
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => $hashedPassword,
                'role'     => 2,
            ];

            $user = User::create($data);
            return new UserResource($user);

        } catch (\Throwable $th) {
            Log::error($th);
            return $this->serverError();
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password))
                return $this->badRequest('check your email and password');

            $token = $user->createToken('access_token')->plainTextToken;
            return (new UserResource($user))->additional(['token' => $token]);
            
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->serverError();
        }
    }
}
