<?php

namespace App\Http\Middleware;

use App\Firebase\DataMapper;
use App\Firebase\FirebaseConnection;
use App\Model\FirebaseUser;
use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class ValidEvent
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
        /** @var FirebaseConnection $connection */
        $connection = App::make(\App\Firebase\FirebaseConnection::class);
        $event      = $request->route('event') ?: sha1(ValidEvent::class);
        /** @var FirebaseUser $user */
        $user  = Auth::user();
        $valid = $connection->getConnection()->getDatabase()->getReference(DataMapper::event($user->getUid(), $event)[0])->getValue() !== null;
        if (!$valid)
        {
            return redirect()->back()->with('cbk_msg', ['notify' => ["Event Tidak Valid"]]);
        }

        return $next($request);
    }
}
