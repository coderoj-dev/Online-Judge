<?php

use App\Models\Verdict;
use Illuminate\Database\Seeder;

class VerdictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Verdict::truncate();
        Verdict::insert([
            [
                'id'         => 1,
                'short_name' => 'QUEUE',
                'name'       => 'In Queue',
            ],
            [
                'id'         => 2,
                'short_name' => 'RUNNING',
                'name'       => 'Running',
            ],
            [
                'id'         => 3,
                'short_name' => 'AC',
                'name'       => 'Accepted',
            ],
            [
                'id'         => 4,
                'short_name' => 'WA',
                'name'       => 'Wrong Answer',
            ],
            [
                'id'         => 5,
                'short_name' => 'TLE',
                'name'       => 'Time Limit Exceeded',
            ],
            [
                'id'         => 6,
                'short_name' => 'CE',
                'name'       => 'Compilation Error',
            ],
            [
                'id'         => 7,
                'short_name' => 'RE',
                'name'       => 'Runtime Error',
            ],
            [
                'id'         => 8,
                'short_name' => 'MLE',
                'name'       => 'Memory Limit Exceeded',
            ],
            [
                'id'         => 9,
                'short_name' => 'EFE',
                'name'       => 'Exec Format Error',
            ],
            [
                'id'         => 10,
                'short_name' => 'OLE',
                'name'       => 'Output Limit Exceeded',
            ],
            [
                'id'         => 11,
                'short_name' => 'LRE',
                'name'       => 'Language Restricted',
            ],
            [
                'id'         => 12,
                'short_name' => 'IE',
                'name'       => 'Internal Error',
            ],
            [
                'id'         => 13,
                'short_name' => 'PA',
                'name'       => 'Passed',
            ],
            [
                'id'         => 14,
                'short_name' => 'PP',
                'name'       => 'Partial Passed',
            ],
            [
                'id'         => 15,
                'short_name' => 'FA',
                'name'       => 'Failed',
            ],
            [
                'id'         => 16,
                'short_name' => 'SKIP',
                'name'       => 'Skipped',
            ],
        ]);

    }
}
