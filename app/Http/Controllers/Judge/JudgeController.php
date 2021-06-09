<?php

namespace App\Http\Controllers\Judge;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Judge\JudgeProcessService;
use App\Services\Judge\JudgeService;

class JudgeController extends Controller
{
    public function process(){
    	//(new JudgeProcessService())->process();
    	(new JudgeService())->Judge();
    }
}
