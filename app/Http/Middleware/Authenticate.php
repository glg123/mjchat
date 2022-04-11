<?php

namespace App\Http\Middleware;

use App\Helpers\JsonResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Laravel\Sanctum\Exceptions\MissingAbilityException;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param \Illuminate\Http\Request $request
     * @return string|null
     */
    protected function redirectTo($request)
    {


        if (!$request->expectsJson()) {
            return JsonResponse::fail('Credentials not match', 401);

            return route('login');
        }
    }

    protected function unauthenticated($request, array $guards)
    {
        return JsonResponse::fail('Credentials not match', 401);

    }
}
