<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateInfoRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function createAdmin(RegisterRequest $request)
    {
        try {
            $hashedPassword = Hash::make($request->password);

            $data = [
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => $hashedPassword,
                'role'     => 1,
            ];

            $admin = User::create($data);
            return new UserResource($admin);
            
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->serverError();
        }
    }

    public function getAdminsList()
    {
        try {
            $admins = User::where('role', 1)->get();
            return $this->success(UserResource::collection($admins));

        } catch (\Throwable $th) {
            Log::error($th);
            return $this->serverError();
        }
    }

    public function getAdmin($id)
    {
        try {
            $admin = User::find($id);

            if (!$admin || $admin->role != 1)
                return $this->badRequest('admin could not be found');

            return new UserResource($admin);

        } catch (\Throwable $th) {
            Log::error($th);
            return $this->serverError();
        }
    }

    public function updateAdminInfo(UpdateInfoRequest $request)
    {
        try {
            $admin = User::find($request->user_id);

            if (!$admin || $admin->role != 1)
                return $this->badRequest('admin could not be found');

            $hashedPassword = null;
            if ($request->has('password'))
                $hashedPassword = Hash::make($request->password);

            $admin->update([
                'name' => $request->name ?? $admin->name,
                'email' => $request->email ?? $admin->email,
                'password' => $hashedPassword ?? $admin->password,
            ]);

            return $this->success();

        } catch (\Throwable $th) {
            Log::error($th);
            return $this->serverError();
        }
    }

    public function deleteAdmin($id)
    {
        try {
            $admin = User::find($id);

            if (!$admin || $admin->role != 1)
                return $this->badRequest('admin could not be found');

            $admin->delete();
            return $this->success();
            
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->serverError();
        }
    }
}
