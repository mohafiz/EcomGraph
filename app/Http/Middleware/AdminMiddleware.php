<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Traits\ResponseTrait;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    use ResponseTrait;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = User::find(auth('sanctum')->id());

        if ($user->role != 2)
            return $this->badRequest('You are not authorized to do this action');

        return $next($request);
    }
}
