<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class MustBeKindhumans
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Socialite::driver('google')->user();

        if (!Str::endsWith($user->getEmail(), 'kindhumans.com')) {
            return redirect()->route('register')->withErrors(
                'Invalid email domain. Please be sure you are using a kindhumans.com email'
            );
        }
        return $next($request);
    }
}
