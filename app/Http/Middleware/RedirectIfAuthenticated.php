<?php

namespace App\Http\Middleware;

use App\Model\FirebaseUser;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check())
        {
            /** @var FirebaseUser $user */
            $user = Auth::user();

            return redirect()->route("{$user->getRole()}.dashboard.home");
        }

        return $next($request);
    }
}
