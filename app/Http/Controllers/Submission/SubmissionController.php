<?php

namespace App\Http\Controllers\Submission;

use App\Http\Controllers\Controller;
use App\Models\Problem;
use App\Models\Submission;
use Illuminate\Http\Request;

class SubmissionController extends Controller
{
    public function createTestSubmission()
    {

        $problem = Problem::where(['slug' => request()->slug])->firstOrFail();
        if (!$problem->languages()->where(['id' => request()->language_id, 'is_archive' => false])->exists()) {
            abort(419, 'Problem language is not found');
        }
        $language = $problem->languages()->where(['id' => request()->language_id, 'is_archive' => false])->firstOrFail();

        $data = [
            'source_code' => base64_decode(request()->source_code),
            'language_id' => request()->language_id,
            'type'        => "testing",
            'judge_type'  => "partial",
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

    public function submissionVerdict()
    {
        $submission = Submission::where(['id' => request()->submission_id])->firstOrFail();
        $testCases  = $submission->testCases()->get();
        $data       = [
            'id'             => $submission->id,
            'time'           => $submission->time,
            'memory'         => $submission->memory,
            'verdict_id'     => $submission->verdict_id,
            'verdict_status' => $submission->verdictStatus(),
            'test_cases'     => [],
        ];

        foreach ($testCases as $key => $testCase) {

            array_push($data['test_cases'], [
                'id'              => $testCase->id,
                'input'           => nl2br($testCase->input),
                'output'          => nl2br($testCase->output),
                'expected_output' => nl2br($testCase->expected_output),
                'time'            => $testCase->time,
                'memory'          => $testCase->memory,
                'passed_point'    => $testCase->passed_point,
                'checker_log'     => nl2br($testCase->checker_log),
                'compiler_log'    => nl2br($testCase->compiler_log),
                'verdict_id'      => $testCase->verdict_id,
                'verdict_status'  => $testCase->verdict->statusClass(),
            ]);
        }
        return response()->json($data);
    }
}
