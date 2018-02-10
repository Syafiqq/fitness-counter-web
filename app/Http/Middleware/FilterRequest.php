<?php

namespace App\Http\Middleware;

use App\Firebase\PopoMapper;
use Closure;

class FilterRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param string|null $type
     * @return mixed
     */
    public function handle($request, Closure $next, $type = null)
    {
        switch ($type)
        {
            case 'json' :
                return $this->handleJSON($request, $next);
        }

        return $next($request);
    }

    /**
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handleJSON($request, Closure $next)
    {
        if ($request->isJson() && $request->expectsJson())
        {
            return $next($request);
        }
        else
        {
            return $request->wantsJson()
                ? response()->json(PopoMapper::jsonResponse(401, 'Unauthorized'), 401)
                : redirect()->back()->with('cbk_msg', ['notify' => ["Unauthorized Access"]]);
        }
    }
}
