<?php

namespace App\Http\Middleware;

use Closure;

class Acl
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
        // echo $request->getPathInfo();
        $role = 'admin';
        $actions = $request->route()->getAction()['roles'];

        if(in_array($role, $actions)) {
            return $next($request);
        } else {
            return abort(401);
        }
    }
}