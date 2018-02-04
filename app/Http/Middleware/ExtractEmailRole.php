<?php

namespace App\Http\Middleware;

use Closure;

class ExtractEmailRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $email = $request->get('email', null);
        if (!is_null($email))
        {
            $extracted = explode('--', $email);
            if (count($extracted) >= 2)
            {
                $request->merge(['email' => $extracted[0], 'role' => $extracted[1]]);
            }
        }

        return $next($request);
    }
}
