<?php

namespace Alamia\RestApi\Http\Middleware;

use Closure;

class ForceCors
{
    public function handle($request, Closure $next)
    {
        if ($request->isMethod('OPTIONS')) {
            $response = response('', 204);
        } else {
            $response = $next($request);
        }

        $origin = $request->headers->get('Origin');
        $allowedOrigins = explode(',', env('CORS_ALLOWED_ORIGINS', 'http://localhost:3000'));

        // If the current request origin is in our allowed list, use it.
        // Otherwise, fallback to the first allowed origin or the App URL.
        $allowOrigin = in_array($origin, $allowedOrigins) ? $origin : ($allowedOrigins[0] ?? env('APP_URL'));

        $headers = [
            'Access-Control-Allow-Origin'      => $allowOrigin,
            'Access-Control-Allow-Methods'     => 'POST, GET, OPTIONS, PUT, DELETE, PATCH',
            'Access-Control-Allow-Headers'     => 'Content-Type, X-Auth-Token, Origin, Authorization, X-XSRF-TOKEN, Accept, X-Requested-With',
            'Access-Control-Allow-Credentials' => 'true',
        ];

        foreach ($headers as $key => $value) {
            $response->headers->set($key, $value);
        }

        return $response;
    }
}
