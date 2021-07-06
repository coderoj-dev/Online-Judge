<?php

namespace App\Http\Middleware\Contest\Arena;

use App\Models\Contest;
use Closure;

class CheckContestPublish
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
        $contest = Contest::where(['slug' => request()->contest_slug])->firstOrFail();

        if(auth()->check()){
            if(auth()->user()->type <=20)return $next($request);
        }
        
        if (!$contest->isModerator()) {
            if (!$contest->publish) {
                abort(404);
            }
        }

        return $next($request);
    }
}
