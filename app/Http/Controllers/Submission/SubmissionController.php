<?php

namespace App\Http\Controllers\Submission;

use App\Events\TestEvent;
use App\Http\Controllers\Controller;
use App\Models\Contest;
use App\Models\Language;
use App\Models\Problem;
use App\Models\Submission;
use App\Models\Verdict;
use Illuminate\Http\Request;

class SubmissionController extends Controller
{
    public function createTestSubmission()
    {

        $problem = Problem::where(['slug' => request()->slug])->firstOrFail();

        $data = [
            'source_code' => base64_decode(request()->source_code),
            'language_id' => request()->language_id,
            'type'        => 1,
            'judge_type'  => "binary",
            'problem_id'  => $problem->id,
        ];

        //dd($data);
        for ($i = 1; $i <= 1; $i++) {
            $submission = Submission::create($data);
        }

        return response()->json([
            'message'             => 'Submission Create Success',
            'submission_id'       => $submission->id,
            'view_submission_url' => route('administration.problem.submission.view', [
                'slug'          => request()->slug,
                'submission_id' => $submission->id,
            ]),
        ]);
    }

    public function languageExit(Problem $problem)
    {
        $language = [];

        if ($problem->language_auto_update) {
            $language = Language::where(['id' => request()->language_id, 'is_archive' => 0])->first();
        } else {
            $language = $problem->languages()->where(['id' => request()->language_id, 'is_archive' => 0])->first();
        }

        if (empty($language)) {
            abort(419, 'Problem language is not found');
        }
        return $language;
    }

    public function createPracticeSubmission()
    {
        $problem = Problem::where(['slug' => request()->slug])->firstOrFail();

        $data = [
            'source_code' => base64_decode(request()->source_code),
            'language_id' => request()->language_id,
            'type'        => 2,
            'judge_type'  => "binary",
            'problem_id'  => $problem->id,
        ];

        for ($i = 1; $i <= 1; $i++) {
            $submission = Submission::create($data);
        }

        return response()->json([
            'message'             => 'Submission Create Success',
            'submission_id'       => $submission->id,
            'view_submission_url' => route('submissions.view', [
                'submission_id' => $submission->id,
            ]),
        ]);
    }

    public function createContestSubmission(Contest $contest, Problem $problem)
    {

        if ($contest->status == "past") {
            abort(419, 'Contest Is Finished');
        }

        if (!$contest->isParticipant()) {
            response()->json([
                'message'             => 'You can not submit this contest'
            ],419),
        }

        $data = [
            'source_code' => base64_decode(request()->source_code),
            'language_id' => request()->language_id,
            'type'        => 3,
            'judge_type'  => "binary",
            'problem_id'  => $problem->id,
        ];

        $submission = Submission::create($data);

        $contest->submissions()->attach($submission->id);

        if ($submission->verdict_id == 3) {
            $contest->rankList()->save();
        }

        return response()->json([
            'message'             => 'Submission Create Success',
            'submission_id'       => $submission->id,
            'view_submission_url' => route('contest.arena.submissions.view', [
                'contest_slug'  => request()->contest_slug,
                'submission_id' => $submission->id,
            ]),
        ]);
    }

    public function practiceSubmissionList()
    {
        $submissions = Submission::where(['type' => 2])
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
                if (request()->slug != "") {
                    $q->where('slug', request()->slug);
                }
            })
            ->orderBy('id', 'DESC')->paginate(25);

        return view('pages.submission.practice_submission_list', [
            'submissions' => $submissions,
            'verdicts'    => Verdict::all(),
            'languages'   => Language::all(),
        ]);
    }

    public function viewSubmission()
    {
        $submission = Submission::where(['type' => '2', 'id' => request()->submission_id])->firstOrFail();

        return view('pages.submission.view_submission', [
            'submission'           => $submission,
            'testCaseDetailsRoute' => route("submissions.view.test_case_details", [
                'submission_id' => request()->submission_id,
            ]),
        ]);
    }

    public function viewSubmissionTestCaseDetails()
    {
        $submission = Submission::where(['type' => '2', 'id' => request()->submission_id])->firstOrFail();

        $testCase = $submission->testCases()->where(['id' => request()->test_case_id])->firstOrFail();
        return view('pages.submission.submission_test_case_details', [
            'testCase' => $testCase,
        ]);
    }

    public function testEvent()
    {
        $options = array(
            'cluster' => 'mt1',
            'useTLS'  => true,
        );
        $pusher = new \Pusher\Pusher(
            'dcab8d83af4cb3d194e7',
            '7b0eba9a2b63bd31a33c',
            '1224987',
            $options
        );

        $data['message'] = 'this is ok';
        $pusher->trigger('my-channel', 'my-event', $data);
    }
}
