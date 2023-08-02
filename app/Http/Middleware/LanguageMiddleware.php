<?php

namespace App\Http\Middleware;

use Closure;
use App;
use App\Language;
use Illuminate\Http\Request;

class LanguageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        session()->put('lang', $this->getCode());
        app()->setLocale(session('lang', $this->getCode()));
        App::setLocale($this->getCode());
        return $next($request);
    }

    public function getCode()
    {
        if (session()->has('lang')) {
            return session('lang');
        }
        $language = Language::where('is_default', 1)->first();
        return $language ? $language->code : 'en';
    }
}
