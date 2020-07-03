<?php
class JudgeProcess {
   
   	public $threadId;
   	public $submissionProcessId;
   	public $submissionProblemId;
   	public $testCaseReady;
   	public $submissionInfo=array();
   	public $testCaseList=array();
   	public $recursionStartTime;
	public $isPreviousData = 0;
 	
 	public function __construct(){
     	$this->DB=new Database();
     	$this->Site=new Site();
     	$this->conn=$this->DB->conn;
     	$this->setThreadId();
 	}

 	public function setThreadId(){

 		/*******************************************************************************
		*	@ create thread id by using randome function and range of this function is 0 to 100000
 		********************************************************************************/

 		$this->threadId=rand(1,100000);
 	}

 	public function processSingleSubmission(){

 		/*******************************************************************************
		*	@ process a single submission
 		********************************************************************************/
 		
 		$this->getSubmissionInQueue();
 		if($this->submissionProcessId!=-1){
 			$this->setSubmissionProcessThread();
 			$this->setTestCase();
 			$this->saveDatabaseTestCase();
 			$this->resetSubmissionProcessThread();
 			$this->clearData();
 		}
 	}

 	public function processMultipleSubmission($isStart=true){

 		/*******************************************************************************
		*	@ process multiple submission. total submission execute in this function is 50
		* 	@ 50 times call single submission function 
 		********************************************************************************/
 		if($isStart)$this->recursionStartTime = strtotime($this->DB->date());
        $now = strtotime($this->DB->date());
        if(($now-$this->recursionStartTime)>=55)return;

 		$this->processSingleSubmission();
	    
	    if($this->isPreviousData==0)sleep(1);
        else usleep(100000);//sleep 0.1 second for cron job database problem
        $this->processMultipleSubmission(false);

	    return;
 	}

 	public function getTotalRunningSubmission(){
 		$sql="select count(*) as total from submissions where judgeComplete=0 and testCaseReady=1";
 		$info=$this->DB->getData($sql);
 		if(isset($info[0]))return $info[0]['total'];
 		return 0;
 	}

 	public function getSubmissionInQueue(){

 		/*******************************************************************************
		*	@ get submission Id whose test case is not ready.so we call testCaseReady = -1;
		*		-> -1 =	not ready because no token genate in this submission test case
		*		-> 0 = processing this submission
		*		-> 1 = ready for judge
 		********************************************************************************/
 		$totalRunning = $this->getTotalRunningSubmission();
 		if($totalRunning>6)return $this->submissionProcessId=-1;

 		$sql="select submissions.*,problems.cpuTimeLimit,problems.memoryLimit from submissions join problems on problems.problemId=submissions.problemId where testCaseReady=-1 limit 1";
 		$info=$this->DB->getData($sql);
 		
 		if(isset($info[0])){
 			$info=$info[0];
 			$this->submissionInfo=$info;
 			$this->submissionProcessId=$info['submissionId'];
 		}
 		else $this->submissionProcessId=-1;
 	}

 	public function setSubmissionProcessThread(){

 		/*******************************************************************************
		*	@ set submission table testCaseReady = 0
		*		-> -1 =	not ready because no token genate in this submission test case
		*		-> 0 = processing this submission
		*		-> 1 = ready for judge
		*	@ use testCaseReady = 0 when this submission processing because
		*	  when this submission processing in this moment other cronjob script execute
		*	  this script again work this submissionId but if we set submission Processing
		*	  feature then this thread know this submission is already processing and
		*	  this time this thread search another submission for ready.
 		********************************************************************************/

 		$data=array();
 		$data['submissionId']=$this->submissionProcessId;
 		$data['testCaseReady']=0;
 		$data['threadId']=$this->threadId;
 		$this->DB->pushData("submissions","update",$data);
 	}

 	public function setTestCase(){

 		/*******************************************************************************
		*	@ generate all test case list for this submission 
		*	@ store #testCaseList this submission all test case
		*	@ each test case send curl request in api
		*		if test case sucessfully submit in api
		*			-> then continue store this #sendRequestToken;
		*		otherwise
		*			-> stop storing test case , break a loop , set #testCaseReady=0 
 		********************************************************************************/

 		$problemId=$this->submissionInfo['problemId'];
 		$sql="select testCaseId from test_case where problemId=$problemId order by testCaseId ASC";
 		$info=$this->DB->getData($sql);
 		
 		$this->testCaseReady=1;

 		$testCaseList=array();

 		foreach ($info as $key => $value) {
 			array_push($testCaseList, $value['testCaseId']);
 		}
 		$this->testCaseList=$testCaseList;
 	}


 	
 	public function saveDatabaseTestCase(){

 		/*******************************************************************************
		*	@ store all test case in database
 		********************************************************************************/
 		
 		if($this->testCaseReady==0)
 			return;
 		$data = array();
 		$submissionId=$this->submissionProcessId;
 		$sql="select threadId from submissions where submissionId=$submissionId";
 		$info=$this->DB->getData($sql);
 		if($info['0']['threadId']!=$this->threadId)
 		    return;
 		$data['submissionId'] = $this->submissionProcessId;
 		$c=0;
 		print_r($this->testCaseList);
 		foreach ($this->testCaseList as $key => $value) {
 			$c++;
 			$data['testCaseSerialNo']=$c;
 			$data['testCaseId']=$value;
 			$res=$this->DB->pushData("submissions_on_test_case","insert",$data);
 		}
 	}

 	public function resetSubmissionProcessThread(){
 		
 		/*******************************************************************************
		*	@ again set status of submission.
		*	@ if submission is ready then
		*		-> testCaseReady = 1
		*	  other wise
		*	  	-> testCase again go not ready state and set testCaseReady = -1
		*	@ if this submission problem has no test case then this submission direct
		*	  set accept verdict and id of accept verdict = 3
 		********************************************************************************/

 		$data = array();
 		$data['submissionId'] = $this->submissionProcessId;
 		$data['testCaseReady'] = $this->testCaseReady?1:-1;
 		
 		if($this->testCaseReady==1){
 			$data['totalTestCase']=count($this->testCaseList);

 			//handle if total test case is zero then its return AC;
 			if($data['totalTestCase']==0){
 				$data['submissionVerdict']=3;
 				$data['judgeComplete']=1;
 			}
		}
		print_r($data);
 		$this->DB->pushData("submissions","update",$data);
 	}

 	public function clearData(){

 		/*******************************************************************************
		*	@ when function is finished then our need to clear all global data
			  for avoid previous storing.
 		********************************************************************************/
 		
 		unset($this->testCaseList);
 		unset($this->submissionInfo);
 	}



 	
 
}
?>