<?php

namespace Alamia\RestApi\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureJsonApiHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Ensure Accept header is set to JSON:API
        if (!$request->headers->has('Accept')) {
            $request->headers->set('Accept', 'application/vnd.api+json');
        }

        // Ensure Content-Type is JSON:API for POST/PUT/PATCH requests
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH'])) {
            if (!$request->headers->has('Content-Type')) {
                $request->headers->set('Content-Type', 'application/vnd.api+json');
            }
        }

        $response = $next($request);

        // Set JSON:API Content-Type header on response
        if ($response instanceof Response) {
            $response->headers->set('Content-Type', 'application/vnd.api+json');
        }

        return $response;
    }
}
