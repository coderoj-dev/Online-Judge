<?php

namespace App\Http\Middleware\Administration\Problem;

use App\Models\Problem;
use Closure;

class ModeratorIsPending
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
        $problem  = Problem::where(['slug' => request()->slug])->firstOrFail();
        if(auth()->check()){
            if(auth()->user()->type <=20)return $next($request);
        }
        
        $problem  = auth()->user()->problems()->where('problem_id', $problem->id)->firstOrFail();
        $isAccept = $problem->pivot->is_accepted;
        if (!$isAccept) {
            return response(view('pages.administration.problem.moderator_accept', [
                'problem' => $problem,
            ]));
        }
        return $next($request);
    }
}
