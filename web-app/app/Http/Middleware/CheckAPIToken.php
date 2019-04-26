<?php

namespace App\Http\Middleware;

use Closure;
use \Redis;
use Response;

class CheckAPIToken
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
        $token_header = $request->header('token');
        if (empty($token_header)) {
            return Response::json([
                'error' => 'Unauthorized'
            ], 401);
        }
        $redis_token_value = boolval(Redis::get($token_header));
        if (empty($redis_token_value)) {
            return Response::json([
                'error' => 'Unauthorized'
            ], 401);
        }
        return $next($request);
    }
}
