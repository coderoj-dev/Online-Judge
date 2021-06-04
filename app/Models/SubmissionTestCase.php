<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubmissionTestCase extends Model
{
    protected $fillable = [
        'submission_id', 'verdict_id', 'hash_file', 'input', 'output', 'expected_output', 'time', 'memory', 'checker_log', 'compiler_log','point'
    ];

    public function verdict()
    {
        return $this->belongsTo(Verdict::class);
    }
}
