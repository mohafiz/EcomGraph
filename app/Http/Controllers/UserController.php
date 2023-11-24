<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function getUsersList()
    {
        try {
            $users = User::where('role', 2)->get();
            return $this->success(UserResource::collection($users));

        } catch (\Throwable $th) {
            Log::error($th);
            return $this->serverError();
        }
    }

    public function getUser($id)
    {
        try {
            $user = User::find($id);

            if (!$user || $user->role != 2)
                return $this->badRequest('user could not be found');

            return new UserResource($user);

        } catch (\Throwable $th) {
            Log::error($th);
            return $this->serverError();
        }
    }

    public function deleteUser($id)
    {
        try {
            $user = User::find($id);

            if (!$user || $user->role != 1)
                return $this->badRequest('user could not be found');

            $user->delete();
            return $this->success();
            
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->serverError();
        }
    }
}
