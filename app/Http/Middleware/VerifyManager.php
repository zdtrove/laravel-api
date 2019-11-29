<?php

namespace App\Http\Middleware;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class VerifyManager extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     *
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        // Try to catch exception
        try {
            // Check restrict IP
            $ipAllowedList = explode(',', env('IP_ALLOWED'));
            $user = auth()->user();
            if (in_array($request->ip(), $ipAllowedList)
                && $user->role === ADMIN
                && $user->adminRoles->contains('role', ADMIN_ROLE_MANAGER)) {
                return $next($request);
            } else {
                throw new AccessDeniedHttpException(__('api.messages.access_denied'));
            }
        } catch (\Exception $e) {
            throw new AccessDeniedHttpException(__('api.messages.access_denied'));
        }
    }
}
