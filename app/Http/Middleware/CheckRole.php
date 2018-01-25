<?php

namespace App\Http\Middleware;

use Closure;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $role
     * @return mixed
     */
    public function handle($request, Closure $next, $role = null)
    {
        if (!($request->user()->getRole() === $role))
        {
            return redirect()->back()->with('cbk_msg', ['notify' => ["Anda Tidak Memiliki Hak Akses"]]);
        }

        return $next($request);
    }
}
