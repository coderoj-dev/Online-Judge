<?php

namespace App\Http\Controllers\Administration\Contest;

use App\Http\Controllers\Controller;
use App\Models\Contest;

class ContestController extends Controller
{
    protected $contest;

    public function __construct()
    {
        if (isset(request()->contest_id)) {
            $this->contest = Contest::where(['id' => request()->contest_id])->firstOrFail();
        }
    }
    public function contestList()
    {
        $contests = auth()->user()->contests()->paginate(15);
        return view("pages.contest.dashboard.contest_list", ['contests' => $contests]);
    }

    public function overview()
    {
    	return view('pages.administration.contest.overview.overview',['contest' => $this->contest]);
    }

    public function edit()
    {
        return view('pages.administration.contest.edit.edit', ['contest' => $this->contest]);
    }

    public function moderators()
    {
        $moderators = $this->contest->moderator()->orderBy('created_at')->get();
     
        return view('pages.administration.contest.moderator.moderators', [
            'contest'    => $this->contest,
            'moderators' => $moderators,
            'role'       => $this->contest->authUserRole,
        ]);
    }
}
