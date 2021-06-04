<?php

namespace App\Http\Controllers\Administration\Problem;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Problem\ProblemService;
use App\Models\User;

class ModeratorController extends Controller
{
    private $problemData;
    protected $problemService;
    public function __construct(ProblemService $probServc)
    {
        $this->problemService  = $probServc;
        // $this->languageService = $languageService;
        if (isset(request()->slug)) {
            $this->problemData = $this->problemService->getProblemData(request()->slug);
        }
    }
    public function getModeratorsList()
    {
        $existing = $this->problemData->moderator;
        $moderators = User::where('handle','like',request()->search.'%')->get();
        $moderators = $moderators->diff($existing)->take(2)->take(5);
        return json_encode($moderators,200);
    }

    public function addModerator()
    {

        $this->problemData->moderator()->attach(request()->userId,[
            'role' => 'moderator',
            'is_accepted' => 0
        ]);
        response()->json([
            'message' => "Moderator Added Successfully",
        ]);
    }

    public function deleteModerator()
    {
        $this->problemData->moderator()->detach(request()->userId);
        response()->json([
            'message' => "Moderator Removed Successfully",
        ]);
    }
}
