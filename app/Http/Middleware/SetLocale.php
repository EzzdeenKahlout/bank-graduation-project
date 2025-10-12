<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $locale = session('locale');
        
        if (!$locale && auth()->check()) {
            $locale = auth()->user()->preferred_language;
            session(['locale' => $locale]);
        }
        
        if (!$locale) {
            $locale = config('app.locale');
        }
        
        if (in_array($locale, ['ar', 'en'])) {
            App::setLocale($locale);
        }
        
        return $next($request);
    }
}