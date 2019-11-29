<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;

class Cors
{
    protected $allowedOrigin;
    protected $allowedMethods;
    protected $allowedHeaders;
    protected $allowCredentials;
    protected $allowedOriginsWhitelist;

    public function __construct()
    {
        $corsConfig = Config::get('cors');
        $this->allowedOrigin = $corsConfig['allowedOrigins'];
        $this->allowedMethods = $corsConfig['allowedMethods'];
        $this->allowedHeaders = $corsConfig['allowedHeaders'];
        $this->allowCredentials = $corsConfig['allowCredentials'];
        $this->allowedOriginsWhitelist = $corsConfig['allowedOriginsWhitelist'];
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$this->isCorsRequest($request)) {
            return $next($request);
        }

        $this->allowedOrigin = $this->resolveAllowedOrigin($request);

        $this->allowedHeaders = $this->resolveAllowedHeaders($request);

        $headers = [
            'Access-Control-Allow-Origin' => $this->allowedOrigin,
            'Access-Control-Allow-Methods' => $this->allowedMethods,
            'Access-Control-Allow-Headers' => $this->allowedHeaders,
            'Access-Control-Allow-Credentials' => $this->allowCredentials,
        ];

        if ($request->getMethod() === 'OPTIONS') {
            return response('', 200)->withHeaders($headers);
        }

        $response = $next($request)->withHeaders($headers);

        return $response;
    }

    /**
     * Incoming request is a CORS request if the Origin
     * header is set and Origin !== Host
     *
     * @param  \Illuminate\Http\Request $request
     * @return bool
     */
    private function isCorsRequest($request)
    {
        $requestHasOrigin = $request->headers->has('Origin');
        if ($requestHasOrigin) {
            $origin = $request->headers->get('Origin');
            $host = $request->getSchemeAndHttpHost();
            if ($origin !== $host) {
                return true;
            }
        }

        return false;
    }

    /**
     * Dynamic resolution of allowed origin since we can't
     * pass multiple domains to the header. The appropriate
     * domain is set in the Access-Control-Allow-Origin header
     * only if it is present in the whitelist.
     *
     * @param  \Illuminate\Http\Request $request
     * @return null|string|string[]
     */
    private function resolveAllowedOrigin($request)
    {
        $allowedOrigin = $this->allowedOrigin;

        // If origin is in our allowedOriginsWhitelist
        // then we send that in Access-Control-Allow-Origin
        $origin = $request->headers->get('Origin');
        if (in_array($origin, $this->allowedOriginsWhitelist)) {
            $allowedOrigin = $origin;
        }

        return $allowedOrigin;
    }

    /**
     * Take the incoming client request headers
     * and return. Will be used to pass in Access-Control-Allow-Headers
     *
     * @param  \Illuminate\Http\Request $request
     * @return null|string|string[]
     */
    private function resolveAllowedHeaders($request)
    {
        $allowedHeaders = $request->headers->get('Access-Control-Request-Headers');

        return $allowedHeaders;
    }
}
