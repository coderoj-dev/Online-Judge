<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class SubmissionTestCase extends Model
{
	protected $appends = ['input_file,expected_output_file','passed_point'];
    protected $fillable = [
        'submission_id', 'verdict_id', 'hash_file','token', 'input', 'output', 'expected_output', 'time', 'memory', 'checker_log', 'compiler_log', 'point',
    ];

    public function getInputFileAttribute()
    {
        return public_path() . '/file/test_case/input/' . $this->hash_file . ".txt";
    }

    public function getPassedPointAttribute()
    {
        return $this->verdict_id == 3 ? $this->point : 0;
    }

    public function getExpectedOutputFileAttribute()
    {
        return public_path() . '/file/test_case/output/' . $this->hash_file . ".txt";
    }

    public function readInput()
    {
    	return File::exists($this->input_file) ? File::get($this->input_file) : "";
    }

    public function readExpectedOutput()
    {
    	return File::exists($this->expected_output_file) ? File::get($this->expected_output_file) : "";
    }

    public function verdict()
    {
        return $this->belongsTo(Verdict::class);
    }
}
