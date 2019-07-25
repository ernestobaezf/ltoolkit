<?php
/**
 * @author Ernesto Baez 
 * @author Ernesto Baez  04/05/19 2:44 PM
 */

namespace l5toolkit\Http\Middleware;


use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  Request $request
     * @param  Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        // Check header request and determine localization
        $local = $request->hasHeader('X-localization') ? $request->header('X-localization') :
            ($user ? ($user->preferredLocale() ?: 'en'):'en');

        // set laravel localization
        app()->setLocale($local);
        // continue request

        return $next($request);
    }
}
