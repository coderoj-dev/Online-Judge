<?php

namespace App\Http\Middleware\Administration\Problem;

use Closure;
use App\Models\Problem;

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
        $problem = Problem::where(['slug' => request()->slug])->firstOrFail();
        $problem = auth()->user()->problems()->where('problem_id',$problem->id)->firstOrFail();
        $owner = $problem->moderator()->where('role','owner')->firstOrFail();
        $isAccept = $problem->pivot->is_accepted;
        if(!$isAccept)
        {
            // echo "testing";
            // return;
            return response(view('pages.administration.problem.moderator_accept',['problem' => $problem,'owner' => $owner]));
            // return view('pages.administration.problem.moderator_accept');
            // abort(401);
        }
        return $next($request);
    }
}
