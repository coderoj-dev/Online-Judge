<?php

namespace App\Http\Middleware\Administration;

use Closure;

class Administration
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
        if (!auth()->check()) {
            abort(401,"You need to login your account");
        }
        $user = auth()->user();
        $role = $user ? $user->type : abort(401);
        if($role > 30)
        {
            return response(view('pages.administration.request_for_moderator'));
        }
        return $next($request);
    }
}
