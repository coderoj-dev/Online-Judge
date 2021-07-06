<?php

namespace App\Http\Controllers\Contest;

use App\Http\Controllers\Controller;
use App\Http\Requests\Contest\ContestCreateRequest;
use App\Models\Contest;
use Illuminate\Http\Request;

class ContestController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("pages.contest.dashboard.contest_create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\Problem\ProblemCreateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ContestCreateRequest $request)
    {

        $contest = Contest::create($request->all());
        return response()->json([
            'message' => 'Contest Successfully Created',
            'url'     => route("administration.contest.overview", [
                'contest_id' => $contest->id,
            ]),
        ]);
    }

    public function getContestList()
    {
        $contests = Contest::where(['publish' => 1])->orderBy('start', 'desc')->get();
        return view("pages.contest.contest_list", ['contests' => $contests]);
    }

    public function contestInfo()
    {
        $contest = Contest::where(['slug' => request()->contest_slug])->firstOrFail();
        return view("pages.contest.contest_info", ['contest' => $contest]);
    }

    public function viewRegistration()
    {
        $contest = Contest::where(['slug' => request()->contest_slug])->firstOrFail();
        if (!$contest->canRegistration()) {
            abort(419);
        }

        return view("pages.contest.registration", ['contest' => $contest]);
    }

    public function createRegistration()
    {
        $contest = Contest::where(['slug' => request()->contest_slug])->firstOrFail();
        if (!$contest->canRegistration()) {
            return response()->json([
                'message' => 'You Can Not Sign Up This Contest',
            ], 419);
        }

        if ($contest->visibility == "protected") {
            if (request()->contest_password != $contest->password) {
                return response()->json([
                    'message' => 'Password Is Not Valid',
                ], 419);
            }
        }

        $contest->registrations()->attach(auth()->user()->id, [
            'is_registration_accepted' => $contest->registration_auto_accept,
        ]);

        $contest->registrationCacheData()->save();

        return response()->json([
            'message' => 'Sign Up Success',
        ]);
    }
}
