<?php

namespace App\Models;

use App\Models\Problem;
use App\Models\SubmissionTestCase;
use App\Models\Traits\Submission\SubmissionType;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use SubmissionType;

    protected $fillable = [
        'problem_id', 'verdict_id', 'language_id', 'user_id', 'type', 'judge_type', 'judge_status', 'source_code', 'time_limit', 'memory_limit', 'time', 'memory', 'run_on_test', 'total_test_case', 'total_point', 'passed_point', 'created_at', 'updated_at',
    ];

    protected $appends = ['type_string'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($submission) {
            $submission->verdict_id = 1;
            $submission->time       = 0;
            $submission->memory     = 0;
            $submission->type       = $submission->getSubmissionType();
            $submission->user_id    = auth()->user()->id;

            $problem  = Problem::where(['id' => $submission->problem_id])->firstOrFail();
            $language = $problem->languages()->where(['id' => $submission->language_id, 'is_archive' => false])->firstOrFail();

            $submission->time_limit   = (int) ceil($language->pivot->time_limit * $problem->time_limit);
            $submission->memory_limit = (int) ceil($language->pivot->memory_limit * $problem->memory_limit);

        });

        // auto-sets values on creation
        static::created(function ($submission) {
            $submission->createTestCase();
        });
    }

    public function createTestCase()
    {
        $problem   = Problem::where(['id' => $this->problem_id])->firstOrFail();
        $testCases = $problem->testCases()->get();
        //dd($testCases);
        $totalPoint = 0;
        foreach ($testCases as $key => $testCase) {
            $totalPoint += $testCase->point;
            SubmissionTestCase::create([
                'submission_id' => $this->id,
                'verdict_id'    => 1,
                'hash_file'     => $testCase->hash_id,
                'point'         => $testCase->point,
            ]);
        }

        $this->total_test_case = count($testCases);
        $this->total_point     = $totalPoint;
        $this->verdict_id      = count($testCases) == 0 ? 3 : 1;
        $this->judge_status    = "test_case_ready";
        $this->save();
    }

    public function getTypeAttribute()
    {
        return "hamza";
    }

    public function testCases()
    {
        return $this->hasMany(SubmissionTestCase::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function problem()
    {
        return $this->belongsTo(Problem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function verdict()
    {
        return $this->belongsTo(Verdict::class);
    }

    public function verdictStatus(){
        return $this->verdict->statusClass([
            'running_on_test' => true,
            'total_test_case' => $this->total_test_case,
            'run_on_test' => $this->run_on_test,
            'judge_type' => $this->judge_type,
            'total_point' => $this->total_point,
            'passed_point' => $this->passed_point
        ]);
    }

    

}
