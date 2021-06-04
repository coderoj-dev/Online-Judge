<?php

namespace App\Http\Controllers\Submission;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Submission;
use App\Models\Problem;

class SubmissionController extends Controller
{
    public function createTestSubmission(){

    	$problem = Problem::where(['slug' => request()->slug])->firstOrFail();
    	if(!$problem->languages()->where(['id' => request()->language_id,'is_archive' => false])->exists()){
    		abort(419, 'Problem language is not found');
    	}
    	$language = $problem->languages()->where(['id' => request()->language_id,'is_archive' => false])->firstOrFail();

    	$data = [
    		'source_code' => base64_decode(request()->source_code),
    		'language_id' => request()->language_id,
    		'type' => "testing",
    		'judge_type' => "partial",
    		'problem_id' => $problem->id,
    	];

    	//dd($data);
    	$submission = Submission::create($data);
        return response()->json([
            'message' => 'Submission Create Success',
            'submission_id' => $submission->id
        ]);
    }
}
