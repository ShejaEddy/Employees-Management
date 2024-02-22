<?php

namespace App\Http\Middleware;

use App\Traits\BaseTraits;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthenticateMiddleware
{
    use BaseTraits;

    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('sanctum')->guest()) {
            return $this->respondError([], 'Unauthorized access, please login.', 401);
        }

        return $next($request);
    }
}
