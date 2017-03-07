<?php

namespace App\Http\Middleware;

use Closure;

class MDadmin
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
        $currentUser = \Auth::user();
        if($currentUser->roleid != 1){
            return view('home')->with('msj', 'no tienes permiso en esta ruta');
        }
        return $next($request);
    }
}
