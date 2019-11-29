<?php

namespace App\Http\Middleware;

use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class VerifyJWTToken extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     *
     * @param array $roles
     * @return mixed
     */
    public function handle($request, \Closure $next, $roles = [])
    {
        $roles = array_slice(func_get_args(), 2);

        // Check current token
        $this->checkForToken($request);
        // Try to catch exception
        try {
            // Get payload
            $payload = auth()->payload();

            // Check exist user from token
            if (!auth()->guard($payload['guard'])->authenticate()) {
                throw new UnauthorizedHttpException('jwt-auth', __('api.messages.token_invalid'));
            }

            // Token is valid, set user for current Auth and continue request
            $authUser = auth()->guard($payload['guard'])->user();
            if ($authUser->role == ADMIN) {
                // load/cache admin roles for faster serving if user is admin
                $authUser->load('adminRoles');
            }
            auth()->setUser($authUser);

            // Check role of request
            if ($authUser->hasRole($roles)) {
                return $next($request);
            }
        } catch (\Exception $e) {
            if ($e instanceof TokenExpiredException) {
                // Token expired. User not logged.
                try {
                    $payload = auth()->factory()->buildClaimsCollection()->toPlainArray();
                    $newToken = auth()->guard($payload['guard'])->refresh(true);

                    // Token refreshed and continue.
                    auth()->setUser(auth()->guard($payload['guard'])->setToken($newToken)->user());
                    $response = $next($request);

                    // Append new token to response
                    $data = $response->getData(true);
                    $data['refresh_token'] = $newToken;
                    $response->setData($data);

                    // Response with new token on header Authorization.
                    return $this->setAuthenticationHeader($response, $newToken);
                } catch (JWTException $e) {
                    throw new UnauthorizedHttpException('jwt-auth', $e->getMessage(), $e->getPrevious(), 401);
                }
            }

            throw new UnauthorizedHttpException('jwt-auth', __('api.messages.token_invalid'));
        }

        throw new AccessDeniedHttpException(__('api.messages.access_denied'));
    }
}
