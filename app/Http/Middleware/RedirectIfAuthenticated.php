<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        // dd($guards);
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // If the guard is 'doctors', redirect to doctor's home
                if ($guard === 'doctor') {
                    return redirect()->route('doctorindex');
                }
                // If the guard is 'web' (for regular users), redirect to regular home
                if ($guard === 'web') {
                    return redirect(RouteServiceProvider::HOME);
                }
            }
        }
        //  dd(auth()->check());
        return $next($request);
    }
}
