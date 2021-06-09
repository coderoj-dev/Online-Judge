<?php
namespace App\Services\Judge;

use App\Models\Submission;
use Illuminate\Support\Facades\Http;

class JudgeService
{
    protected $multiProcessStart;
    protected $submission;
    protected $testCase;
    protected $apiUrl = "http://judge-server-2021.coderoj.com/api";

    public function judge()
    {
        $this->getSubmissionData();
    }

    public function getSubmissionData()
    {
        $this->submission = Submission::where(['judge_status' => 'judging', 'verdict_id' => 2])->first();
        if (empty($this->submission)) {
            return 0;
        }

        $this->testCase = $this->submission->testCases()->skip($this->submission->run_on_test - 1)->first();

        if ($this->testCase->token == "") {
            $this->createToken();
        } else {
            $tokenData = $this->getTokenData();
            $this->updateJudgeData($tokenData);
        }

        return 1;
    }

    public function createToken()
    {
        $url  = $this->apiUrl . "/submissions";
        $data = [
            'language_argument' => 'cpp',
            'time_limit'        => $this->submission->problem->time_limit,
            'memory_limit'      => $this->submission->problem->memory_limit,
            'input'             => $this->testCase->readInput(),
            'expected_output'   => $this->testCase->readExpectedOutput(),
            'source_code'       => $this->submission->source_code,
            'checker_type'      => $this->submission->problem->checker_type,
            'default_checker'   => $this->submission->problem->default_checker,
            'custom_checker'    => $this->submission->problem->custom_checker,
        ];

        $response = Http::asForm()->post($url, $data);
        $response = json_decode($response);

        if (isset($response->token)) {
            $this->testCase->update([
                'token' => $response->token,
            ]);
        }
    }

    public function getTokenData()
    {
        $url      = $this->apiUrl . "/submissions/" . $this->testCase->token;
        $response = Http::get($url);
        return json_decode($response);
    }

    public function updateJudgeData($tokenData)
    {
        if ($tokenData->verdict[0]->id <= 2) {
            return;
        }

        $submission = $this->submission;
        $testCase   = $this->testCase;

        $verdictId = $tokenData->verdict[0]->id;

        $submission->passed_point += $verdictId == 3 ? $testCase->point : 0;

        $judgeFinish = $submission->run_on_test == $submission->total_test_case;
        $judgeFinish |= ($verdictId > 3 && $submission->judge_type == 'binary');

        if ($judgeFinish == 1) {
            $submission->judge_status = "judge_finish";
            $submission->time         = max($tokenData->time, $submission->testCases()->max('time'));
            $submission->memory       = max($tokenData->memory, $submission->testCases()->max('memory'));
            if ($submission->judge_type == "binary") {
                $submission->verdict_id = $verdictId;
            } else {
                if ($submission->passed_point == 0) {
                    $submission->verdict_id = 15;
                } else if ($submission->passed_point < $submission->total_point) {
                    $submission->verdict_id = 14;
                } else {
                    $submission->verdict_id = 13;
                }

            }
        } else {
            $submission->run_on_test += 1;
        }

        $testCase->input           = $tokenData->input;
        $testCase->output          = $tokenData->output;
        $testCase->expected_output = $tokenData->expected_output;
        $testCase->time            = $tokenData->time;
        $testCase->memory          = $tokenData->memory;
        $testCase->checker_log     = $tokenData->checker_log;
        $testCase->compiler_log    = $tokenData->compiler_log;
        $testCase->verdict_id      = $verdictId;

        if ($judgeFinish == 0) {
            $nextTestCase = $submission->testCases()->skip($submission->run_on_test - 1)->first();
            $nextTestCase->update([
                'verdict_id' => 2,
            ]);
        }

        if ($judgeFinish == 1) {
            $testCases = $submission->testCases()->skip($submission->run_on_test)->take(PHP_INT_MAX)->get();
            foreach ($testCases as $key => $skipTestCase) {
                $skipTestCase->update([
                    'verdict_id' => 16,
                ]);
            }
        }

        $testCase->save();
        $submission->save();

    }

}
