<?php

namespace App\Http\Controllers\Administration\Contest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contest;
use App\Models\User;


class ContestModeratorController extends Controller
{
    //
    private $contestData;
    public function __construct()
    {
     
        if (isset(request()->contest_id)) {

            $this->contestData = Contest::where(['id' => request()->contest_id])->firstOrFail();

        }
    }
    
    public function getModeratorsList()
    {

        $existing   = $this->contestData->moderator;

        $moderators = User::where('handle', 'like', request()->search . '%')->whereRaw('type <= 30')->get();
        $moderators = $moderators->diff($existing)->take(2)->take(5);
        return json_encode($moderators, 200);
        
    }

    public function addModerator()
    {
        if ($this->contestData->authUserRole != "owner") {
            abort(401, "You Can Not Add Moderator. Only Problem owner can add moderator");
        }
        $this->contestData->moderator()->attach(request()->userId, [
            'role'        => 'moderator',
            'is_accepted' => 0,
        ]);
        return response()->json([
            'message' => "Moderator Added Successfully",
        ]);
    }

    public function deleteModerator()
    {
        if ($this->contestData->authUserRole != "owner") {
            abort(401, "You Can Not Add Moderator");
        }
        $user = $this->contestData->moderator()->where('user_id', request()->userId)->firstOrFail();

        if ($user->pivot->role == "owner") {
            abort(401, "You Can Not Be Deleted");
        }
        $this->contestData->moderator()->detach(request()->userId);
        return response()->json([
            'message' => "Moderator Added Successfully",
        ]);
    }

    public function leaveModerator()
    {
        if ($this->contestData->authUserRole == "owner") {
            abort(401, "You Can Not Leave From this Problem");
        }
        $this->contestData->moderator()->detach(auth()->user()->id);
        return response()->json([
            'message' => "You Leave From {$this->contestData->name}",
            'url'     => route('administration.contests'),
        ]);

    }

    public function cancelModeratorRequest()
    {
        # code...
        $this->contestData->moderator()->detach(auth()->user()->id);
        return response()->json([
            'message' => "Moderator Detach Successfully",
            'url'     => route('administration.contest.problems'),
        ]);

    }

    public function acceptModetator()
    {
        $user = User::find(request()->userId);
        $user->contests()->updateExistingPivot($this->contestData, ['is_accepted' => 1]);
        return response()->json([
            'message' => "Moderator accept successfully",
        ]);
    }

    public function requestForModerator()
    {
        if (!auth()->check()) {
            abort(401, "You need to login your account");
        }
        if (!auth()->user()->moderatorRequest) {
            ModeratorRequest::create([
                'user_id' => auth()->user()->id,
                'type'    => 30,
                'message' => request()->message,
            ]);
        }

        return response()->json([
            'message' => "Your Request Is Sent",
        ]);

    } 
}
