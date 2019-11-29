<?php namespace App\Http\Middleware;

use Closure;

class Language
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Make sure current locale exists.
        $locale = $request->input('locale');

        // Get fallback if locale not exist
        if (!array_key_exists($locale, app()->config->get('app.support_locales'))) {
            $locale = app()->config->get('app.fallback_locale');
        }

        // Set new locale for app
        app()->setLocale($locale);

        return $next($request);
    }
}
