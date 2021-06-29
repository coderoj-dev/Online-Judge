<?php

namespace App\Http\Controllers\Administration\Contest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contest;
use App\Models\User;

class ModeratorController extends Controller
{
    protected $contest;
    // protected $contestService;
    public function __construct()
    {
        // $this->contestService = $contestService;
        if (isset(request()->contest_id)) {
            $this->contest = Contest::where(['id' => request()->contest_id])->firstOrFail();
        }
    }
    public function getModeratorsList()
    {
        $existing   = $this->contest->moderator;
        $moderators = User::where('handle', 'like', request()->search . '%')->whereRaw('type <= 30')->get();
        $moderators = $moderators->diff($existing)->take(2);
        return response()->json([
            'moderators' => $moderators
        ]);
    }
    public function addModerator()
    {
        if ($this->contest->authUserRole != "owner") {
            abort(401, "You Can Not Add Moderator. Only Problem owner can add moderator");
        }
        $this->contest->moderator()->attach(request()->userId, [
            'role'        => 'moderator',
            'is_accepted' => 0,
        ]);
        return response()->json([
            'message' => "Moderator Added Successfully",
        ]);
    }
    public function deleteModerator()
    {
        if ($this->contest->authUserRole != "owner") {
            abort(401, "You Can Not Delete Moderator");
        }
        $user = $this->contest->moderator()->where('user_id', request()->userId)->firstOrFail();

        if ($user->pivot->role == "owner") {
            abort(401, "You Can Not Be Deleted");
        }
        $this->contest->moderator()->detach(request()->userId);
        return response()->json([
            'message' => "Moderator Deleted Successfully",
        ]);
    }

    public function cancelModeratorRequest()
    {
        # code...
        $this->contest->moderator()->detach(auth()->user()->id);
        return response()->json([
            'message' => "You Leave From A Contest Moderator",
            'url'     => route('administration.contests'),
        ]);
    }

    public function acceptModetator()
    {
        $user = User::find(request()->userId);
        $user->contests()->updateExistingPivot($this->contest, ['is_accepted' => 1]);
        return response()->json([
            'message' => "Your Are Now Moderator Of This Contest",
        ]);
    }

    public function leaveModerator()
    {
        if ($this->contest->authUserRole == "owner") {
            abort(401, "You Can Not Leave From this Contest");
        }
        $this->contest->moderator()->detach(auth()->user()->id);
        return response()->json([
            'message' => "You Leave From {$this->contest->name}",
            'url'     => route('administration.contests'),
        ]);
    }
}
