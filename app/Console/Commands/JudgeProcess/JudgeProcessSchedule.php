<?php

namespace App\Console\Commands\JudgeProcess;

use Illuminate\Console\Command;
use App\Services\Judge\JudgeProcessService;

class JudgeProcessSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'judge_process:cron1';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        (new JudgeProcessService())->process();
        return 0;
    }
}
