<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $locale = session('locale', config('app.locale', 'th'));
        if (!in_array($locale, ['th', 'en'], true)) {
            $locale = 'th';
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
