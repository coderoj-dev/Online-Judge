<?php

namespace App\Services\Submission;

use App\Models\Submission;
use App\Models\Problem;

class SubmissonService
{
    public function createSubmission($data)
    {
        $data['source_code'] = base64_decode($data['source_code']);

        $problem = Problem::where(['problem_slug' => request()->slug])->firstOrFail();


       // return Language::create($data);
    }

}
