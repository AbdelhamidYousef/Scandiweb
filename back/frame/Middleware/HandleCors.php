<?php

namespace Frame\Middleware;

use Closure;
use Frame\Http\Request;
use Frame\Http\Response;

class HandleCors
{
    /**
     * Handle the incoming request.
     *
     * @param  \Frame\Http\Request  $request
     * @param  \Closure  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if ($this->isPreflightRequest($request)) {
            return $this->allowPreflight($request);
        }

        $response = $next($request);

        $response->setHeader('Access-Control-Allow-Origin', '*');

        return $response;
    }

    /**
     * Determine if the request is a preflight request.
     * 
     * @param  \Frame\Http\Request  $request
     * @return bool
     */
    private function isPreflightRequest(Request $request): bool
    {
        return ($request->method() === 'OPTIONS' && $request->hasHeader('Access-Control-Request-Method'));
    }

    /**
     * Allow the CORS preflight request.
     * 
     * @param  \Frame\Http\Request  $request
     * @return \Frame\Http\Response
     */
    protected function allowPreflight(Request $request): Response
    {
        $response = new Response('', 204);

        $response->setHeader('Access-Control-Allow-Origin', '*');
        $response->setHeader('Access-Control-Allow-Methods', $request->header('Access-Control-Request-Method'));
        $response->setHeader('Access-Control-Allow-Headers', $request->header('Access-Control-Request-Headers'));

        return $response;
    }
}
