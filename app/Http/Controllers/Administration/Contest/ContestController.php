<?php

namespace App\Http\Controllers\Administration\Contest;

use App\Http\Controllers\Controller;
use App\Http\Requests\Contest\ContestUpdateRequest;
use App\Http\Requests\Contest\GenerateContestUserRequest;
use App\Models\Contest;
use App\Models\User;
use App\Services\Contest\ContestService;
use Illuminate\Http\Request;

class ContestController extends Controller
{
    protected $contest;
    protected $contestService;
    public function __construct(ContestService $contestService)
    {
        $this->contestService = $contestService;
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
        return view('pages.administration.contest.overview.overview', ['contest' => $this->contest]);
    }

    public function edit()
    {
        return view('pages.administration.contest.edit.edit', ['contest' => $this->contest]);
    }

    public function update(ContestUpdateRequest $request)
    {
        $this->contestService->updateContest($this->contest, $request);
        return response()->json([
            'message' => "Contest Data Updated Successfully",
        ]);
    }

    public function problems()
    {
        return view('pages.administration.contest.problem.problem_list', ['problems' => $this->contest->problems]);
    }
    public function addProblem(Request $request)
    {
        return response()->json([
            'message' => $this->contestService->addProblem($this->contest, $request->slug),
        ]);
    }
    public function removeProblem(Request $request)
    {
        return response()->json([
            'message' => $this->contestService->removeProblem($this->contest, request()->problem_id),
        ]);
    }

    public function registrationList()
    {
        $tableColumn = [];
        $userDataField = $this->contest->user_data_field;

        $defaultColumn = ['registration_id', 'handle', 'name', 'email', 'registration_time', 'temp_user', 'temp_user_password', 'is_registration_accepted'];

        foreach ($defaultColumn as $key => $value) {
            array_push($tableColumn, ['data' => $value]);
        }

        foreach ($userDataField['registration'] as $key => $value) {
            array_push($tableColumn, ['data' => $value]);
        }

        return view('pages.administration.contest.registration.registration', ['tableColumn' => json_encode($tableColumn)]);
    }

    public function participant()
    {
        // return datatables()->of(User::where(['id' => 1])->get())->toJson();
        // return;
        $users         = $this->contest->registrations()->get();
        $userDataField = $this->contest->user_data_field;

        $datas = [];

        // dd($users);

        //'registration_time','temp_user','temp_user_password','registration_status'
        foreach ($users as $key => $user) {
            $data = [
                'registration_id'          => $user->pivot->id,
                'handle'                   => $user->handle,
                'name'                     => $user->name,
                'email'                    => $user->email,
                'registration_time'        => $user->pivot->created_at,
                'temp_user'                => $user->pivot->is_temp_user,
                'temp_user_password'       => $user->pivot->temp_user_password,
                'is_registration_accepted' => $user->pivot->is_registration_accepted,
            ];

            $registrationData = json_decode($user['registration_data']);
            foreach ($userDataField['registration'] as $key => $value) {
                $data[$value] = isset($registrationData[$value]) ? $registrationData[$value] : "";
            }

            array_push($datas, $data);
        }

        $datas = collect($datas);

        return datatables($datas)->toJson();
    }

    public function uploadParticipant(GenerateContestUserRequest $request)
    {

        $path    = request()->file('data_file')->getRealPath();
        $csvData = array_map('str_getcsv', file($path));

        $response = $this->contestService->generateTempUser($this->contest, [
            'handlePrefix'   => $request->handle_prefix,
            'passwordLength' => $request->password_length,
            'csvData'        => $csvData,
        ]);

        return $response;
    }

    public function moderators()
    {
        $moderators = $this->contest->moderator;
        // dd($this->contest->authUserRole);
        return view('pages.administration.contest.moderators', [
            'moderators' => $moderators,
            'role' =>  $this->contest->authUserRole
        ]);
    }
}
