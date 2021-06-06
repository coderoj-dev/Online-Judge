<?php

namespace App\Services\Problem;

use App\Models\Problem;

class ProblemService
{
    /**
     * Create New User
     *
     * @param  array $data
     * @return void
     */
    public function createNewProblem($data)
    {
        return Problem::create($data);
    }

    public function update($data)
    {
        if (isset($data['_token'])) {
            unset($data['_token']);
        }

        return Problem::where(['slug' => request()->slug])->update($data);
    }

    public function getProblemData($slug)
    {
        return Problem::where(['slug' => $slug])->firstOrFail();
    }

    public function addLanguages(Problem $problem, $data)
    {
        // dd($data);
        $pivot = array();

        foreach ($data['languages'] as $language_id) {
            $pivot[$language_id] = [
                'time_limit' => (double)max($data['time_limit'][$language_id],0.1), 
                'memory_limit' => (double)max($data['memory_limit'][$language_id],0.1)
            ];
        }
        $problem->languages()->sync($pivot);
        return $problem;
    }

    public function updateTimeAndMemory(Problem $problem, $data)
    {
        $problem->time_limit   = $data['time_limit'];
        $problem->memory_limit = $data['memory_limit'];
        $problem->save();
        return $problem;
    }

}
