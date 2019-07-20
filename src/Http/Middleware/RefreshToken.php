<?php

namespace Linlak\Jwt\Http\Middleware;

use Closure;

class RefreshToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $request->refreshToken();
        return $next($request);
    }
}
