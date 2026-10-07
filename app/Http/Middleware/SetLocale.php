<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Use the locale stored in the session, falling back to the app default.
     */
    public function handle(Request $request, Closure $next): Response
    {
        App::setLocale($request->session()->get('locale', config('app.locale')));

        return $next($request);
    }
}
