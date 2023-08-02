<?php

namespace App\Http\Middleware;

use Closure;

class CheckSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $sessionLifetime = env('CAPTCHA', 15); // 15 minutes
        $lastActivity = session('last_activity');

        if ($lastActivity && time() - $lastActivity > $sessionLifetime * 60) {
            // Session expired, redirect to captcha page
            return redirect('/captcha');
        }

        // Update the last activity timestamp
        session(['last_activity' => time()]);
        return $next($request);
    }
}
