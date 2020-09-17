<?php

namespace App\Http\Middleware;

use Closure;

class CorsMiddleware
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
        $allowedOrigins = [
            'http://localhost:8081',
            'http://vietnamfb.com',
            'https://vietnamfb.com',
        ];

        if($request->server('HTTP_ORIGIN')){
            if (in_array($request->server('HTTP_ORIGIN'), $allowedOrigins)) {
                return $next($request)
                    ->header('Access-Control-Allow-Origin', $request->server('HTTP_ORIGIN'))
                    ->header('Access-Control-Allow-Credentials', 'true')
                    ->header('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE, HEAD')
                    ->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Authorization, X-Requested-With, X-Auth-Token');
            }
        }

        return $next($request);

    }
}
