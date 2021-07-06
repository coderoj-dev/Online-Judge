<?php

namespace App\Http\Controllers\Administration\Contest;

use App\Http\Controllers\Controller;
use App\Http\Requests\Contest\ContestUpdateRequest;
use App\Http\Requests\Contest\GenerateContestUserRequest;
use App\Models\Contest;
use App\Models\Language;
use App\Models\Submission;
use App\Models\User;
use App\Models\Verdict;
use App\Services\Contest\ContestService;
use App\Services\Notification\NotificationService;
use Illuminate\Http\Request;
use App\Models\Problem;

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
        return view('pages.administration.contest.problem.problem_list', [
            'problems' => $this->contest->problems,
            'contest'  => $this->contest,
        ]);
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

    public function moderators()
    {
        $moderators = $this->contest->moderator;
        // dd($this->contest->authUserRole);
        return view('pages.administration.contest.moderators', [
            'moderators' => $moderators,
            'contest'    => $this->contest,
            'role'       => $this->contest->authUserRole,
        ]);
    }

    public function registrationList()
    {
        $tableColumn = $this->contestService->getDatatableColumn();
        return view('pages.administration.contest.registration.registration', [
            'tableColumn'     => $tableColumn,
            'tableColumnJson' => json_encode($tableColumn),
            'contest'         => $this->contest,
        ]);
    }

    // submission area

    public function getProblemListWithKeySerial()
    {
        $problems = $this->contest->problems()->get();
        $tmp      = [];

        foreach ($problems as $key => $value) {
            $tmp[chr($key + 65)] = $value;
        }

        return $tmp;
    }

    public function getProblemListWithKeyId()
    {
        $problems = $this->contest->problems()->get();
        $tmp      = [];

        foreach ($problems as $key => $value) {
            $tmp[$value->id]               = $value;
            $tmp[$value->id]['problem_no'] = chr($key + 65);
        }

        return $tmp;
    }

    public function submissionList()
    {
        $problems      = $this->getProblemListWithKeySerial();
        $problemsKeyId = $this->getProblemListWithKeyId();

        if (request()->problem != "") {
            request()->problem_id = isset($problems[request()->problem]) ? $problems[request()->problem]->id : -1;
        }

        $problemsIdList = [];
        foreach ($problems as $key => $value) {
            array_push($problemsIdList, $value['id']);
        }

        $users = $this->contest->registrationCacheData()->get();

        $userIdList = [];
        foreach ($users as $key => $value) {
            if ($value->is_registration_accepted) {
                array_push($userIdList, $value->id);
            }
        }

        $submissions = $this->contest->submissions()
            ->whereIn('problem_id', $problemsIdList)
            ->whereIn('user_id', $userIdList)
            ->whereBetween('created_at', [$this->contest->start, $this->contest->end])
            ->whereHas('verdict', function ($q) {
                if (request()->verdict != "") {
                    $q->where('name', request()->verdict);
                }
            })
            ->whereHas('language', function ($q) {
                if (request()->language != "") {
                    $q->where('name', request()->language);
                }
            })
            ->whereHas('user', function ($q) {
                if (request()->handle != "") {
                    $q->where('handle', request()->handle);
                }
            })
            ->whereHas('problem', function ($q) {
                if (request()->problem != "") {
                    $q->where('id', request()->problem_id);
                }
            })
            ->orderBy('id', 'DESC')->paginate(15);

        return view('pages.administration.contest.submission.submission_list', [
            'contest'       => $this->contest,
            'submissions'   => $submissions,
            'problems'      => $this->getProblemListWithKeySerial(),
            'verdicts'      => Verdict::all(),
            'problemsKeyId' => $problemsKeyId,
            'users'         => $users,
            'languages'     => Language::orderBy('name', 'asc')->get(),
        ]);
    }
    public function viewSubmission()
    {
        $problems = $this->getProblemListWithKeyId();

        $problemsIdList = [];
        foreach ($problems as $key => $value) {
            array_push($problemsIdList, $value['id']);
        }

        $users = $this->contest->registrationCacheData()->get();

        $userIdList = [];
        foreach ($users as $key => $value) {

            if ($value->is_registration_accepted) {
                array_push($userIdList, $value->id);
            }
        }

        $submission = $this->contest->submissions()
            ->where(['id' => request()->submission_id])
            ->whereIn('problem_id', $problemsIdList)
            ->whereIn('user_id', $userIdList)
            ->firstOrFail();

        //dd($problems);

        return view('pages.administration.contest.submission.view_submission', [
            'contest'    => $this->contest,
            'submission' => $submission,
            'users'      => $users,
            'problemKey' => $problems[$submission->problem_id]['problem_no'],
        ]);
    }

    public function viewTestCase()
    {
        $submission = Submission::where(['id' => request()->submission_id])->firstOrFail();
        $testCase   = $submission->testCases()->where(['id' => request()->test_case_id])->firstOrFail();
        return view('pages.submission.submission_test_case_details', [
            'testCase' => $testCase,
        ]);
    }

    public function datatableApi()
    {
        return $this->contestService->getDatatableData($this->contest);
    }

    public function viewGenerateTempUser()
    {
        return view('pages.administration.contest.registration.create_temp_user');
    }

    public function GenerateTempUser(GenerateContestUserRequest $request)
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

    public function viewAddParticipants()
    {
        return view('pages.administration.contest.registration.add_participants');
    }

    public function addParticipants()
    {
        $participantsList = request()->participants_list;
        $participantsList = explode("\n", $participantsList);

        foreach ($participantsList as $key => $value) {
            $participantsList[$key] = trim($value);
        }

        $validUsers      = User::whereIn("handle", $participantsList)->get();
        $errorHandleList = [];
        $validUsersList  = [];

        foreach ($validUsers as $key => $user) {
            array_push($validUsersList, $user->handle);
        }
        foreach ($participantsList as $key => $value) {
            if (trim($value) == "") {
                continue;
            }

            if (!in_array($value, $validUsersList)) {
                array_push($errorHandleList, $value);
            }
        }

        if (!empty($errorHandleList)) {
            $handleList = implode("<br/>", $errorHandleList);
            $cnt        = count($errorHandleList);
            return response()->json([
                'message' => "$cnt Handle Are Not Found<br/>$handleList",
            ], 419);
        }

        foreach ($validUsers as $key => $user) {
            if (!$this->contest->registrations()->where(['id' => $user->id])->exists()) {
                $this->contest->registrations()->attach($user->id, [
                    'is_registration_accepted' => 1,
                ]);
            }
        }

        $this->contest->registrationCacheData()->save();
        $this->contest->rankList()->save();

        $cnt = count($validUsersList);
        return response()->json([
            'message' => "Successfully Added {$cnt} Participants",
        ]);
    }

    public function updateRegistrationStatus()
    {
        $userList = request()->user_list;

        $data = [];

        if (request()->status == "Delete") {
            $this->contest->registrations()->detach($userList);
        } else {
            foreach ($userList as $key => $value) {
                $data[$value] = [
                    'is_registration_accepted' => request()->status == "Accepted" ? 1 : 0,
                ];
            }
            $this->contest->registrations()->syncWithoutDetaching($data);
        }

        $this->contest->registrationCacheData()->save();
        $this->contest->rankList()->save();

        $total  = count($userList);
        $status = request()->status;
        return response()->json([
            'message' => "Successfully {$status} {$total} Registrations",
        ]);
    }

    public function buildMailData($mailType = "email")
    {
        $userData   = $this->contest->registrationCacheData()->get();
        $mailData   = [];
        $userList   = request()->user_list;
        $totalValid = 0;
        $totalMail  = 0;
        foreach ($userList as $key => $userId) {
            if (!isset($userData[$userId])) {
                continue;
            }

            $mail = view("pages.administration.contest.mail.welcome", [
                'contest' => $this->contest,
                'user'    => $userData[$userId],
            ])->render();

            $to      = isset($userData[$userId]->$mailType) ? $userData[$userId]->$mailType : "";
            $isValid = filter_var($to, FILTER_VALIDATE_EMAIL) ? 1 : 0;
            array_push($mailData, [
                'to'       => $to,
                'body'     => $mail,
                'is_valid' => $isValid,
            ]);

            $totalValid += $isValid;
            $totalMail++;
        }

        $res = [
            'totalMail'  => $totalMail,
            'totalValid' => $totalValid,
            'data'       => $mailData,
        ];

        return $res;
    }

    public function sendMail()
    {
        $mailData = $this->buildMailData(request()->email_type);
        foreach ($mailData['data'] as $key => $value) {
            if ($value['is_valid'] == 0) {
                continue;
            }

            NotificationService::sendMail([
                'to'      => $value['to'],
                'message' => $value['body'],
                'subject' => "Welcome to " . $this->contest->name,
            ]);
        }
        return response()->json([
            'message' => "Successfully send {$mailData['totalValid']} mail",
        ]);
    }

    public function viewSendMail()
    {
        $contest       = $this->contest;
        $userDataField = $this->contest->user_data_field;
        $emailOption   = [];
        array_push($emailOption, 'email');
        foreach ($userDataField['registration'] as $key => $value) {
            if ($value == 'email') {
                continue;
            }

            array_push($emailOption, $value);
        }
        $mailData = $this->buildMailData(request()->email_type);

        return view("pages.administration.contest.mail.view_send_mail", [
            'emailOption' => $emailOption,
            'mailData'    => $mailData,
            'mailType'    => request()->email_type,
        ]);
    }
    public function viewProblem($contest_id, Problem $problem, $sl)
    {
        return view('pages.administration.contest.problem.problem_view', ['problem' => $problem, 'sl' => $sl]);
    }
}
