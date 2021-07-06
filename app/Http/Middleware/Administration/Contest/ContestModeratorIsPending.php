<?php

namespace App\Http\Middleware\Administration\Contest;

use App\Models\Contest;
use Closure;

class ContestModeratorIsPending
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
        $contest  = Contest::where(['id' => request()->contest_id])->firstOrFail();
        if(auth()->check()){
            if(auth()->user()->type <=20)return $next($request);
        }
        
        $contest  = auth()->user()->contests()->where('contest_id', $contest->id)->firstOrFail();
        $isAccept = $contest->pivot->is_accepted;
        if (!$isAccept) {
            return response(view('pages.administration.contest.moderator_accept', [
                'contest' => $contest,
            ]));
        }
        return $next($request);
    }
}
