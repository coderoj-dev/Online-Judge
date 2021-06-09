<style type="text/css">
    .submissionTd{
        padding: 10px 5px 10px 5px;
    }
    .submissionTr{
        border: 1px solid #eeeeee!important;
    }
    .submissionVerdictTd{
        text-align: right;
        padding-right: 25px;
    }
    .subBody::-webkit-scrollbar {
        width: 0.5em;
        height: 0.5em
    }
    .submissionHeader{
        padding: 10px; 
        font-size: 14px;
    }
    .submissionTd1{
        border-width: 0px;
        background-color: #f8f8f8!important;

    }
    .submissionTd2{
        border-width: 0px!important;
        background-color: #f9f9f9!important;
    }

    .submission-table th{
    	border: 1px solid #eeeeee;
    }
    .submission-table td{
    	border: 1px solid #f5f5f5;
        /*border-width: 0px;*/
    	padding: 13px 5px 13px 5px!important;
    }

    .testcase-details-tr td{
        
    }

    .testcase-table th{
        border: 1px solid #eeeeee;
    }

    .testcase-table td{
        border-width: 1px;
    }

    .testcase-tr td{
        border: 1px solid #eeeeee;
        padding: 12px 5px 12px 5px!important;
    }

    pre{
        border-radius: 5px;
        border-width: 0px;
        background-color: #ffffff;
    }

    .sourceCodeToggleArea{
        border: 1px solid #E7ECF1;
        padding: 4px 1px 4px 2px;
    }
    .sourceCodeTextArea{
        width: 100%;
        border-width: 0px 1px 1px 1px;
        border-color: #E7ECF1;
        resize: none;
        padding: 2px;
        display: none;

    }

    .sourceCodeTextArea:focus{
          outline: none;
    }

    .testCaseDetailArea{
        border: 1px solid #E7ECF1!important;
        padding: 5px;
        margin-bottom: 10px;
        border-top-width: 0px!important;
        font-size: 12px;
        text-align: left;
        display: none;
    }
    .testCaseDetailBodyArea{
        margin-bottom: 5px;
    }
    .testCaseDetailHeader{
        border: 1px solid #E7ECF1;
        padding: 4px 3px 4px 3px;
        font-weight: bold;
        background-color: #f5f5f5;
    }
    .testCaseDetailBody{
        background-color: #f9f9f9;
        padding: 3px;
        border: 1px solid #E7ECF1;
    }
    .submissionBox{
        box-shadow: 1px 1px 3px #DADADA;
    }
    .submission-live-judge{
        font-size: 15px;
        margin-bottom: 20px;
        text-align: center;
    }
    .submission-live-judge .label{
        font-size: 18px;
    }

</style>

<div class='row'>
    <div class='col-md-12 col-sm-12'>
        <div class="box" style="margin-bottom: 20px">
        	<div class="header">Submission </div>
           	<div class="body subBody" style="overflow-x: scroll;scrollbar-width: none;">
                <div id="result"></div>
           		<div style="position: left"></div>
                <table width="100%" id="submission_table" class="table-custom submission-table">
                	<tr class="submissionTr">
                		<th>#</th>
                        <th>When</th>
                        <th>Author</th>
                		<th>Problem</th>
                		<th>Language</th>
                		<th>CPU</th>
                		<th>Memory</th>
                		<th></th>
                	</tr>
                	<tr class="submissionTr">
                		<td><a href="">{{$submission->id}}</a></td>
                		<td><a href="">{{$submission->user->handle}}</a></td>
                		<td><a href="">{{$submission->problem->name}}</a></td>
                		<td>{{$submission->created_at}}</td>
                		<td>{{$submission->language->name}}</td>
                		<td id="submission_time_{{$submission->id}}">{{$submission->time}} ms</td>
                		<td id="submission_memory_{{$submission->id}}">{{$submission->memory}} kb</td>
                		<td style="width: 150px;" id="submission_verdict_{{$submission->id}}">{!!$submission->verdictStatus()!!}</td>
                	</tr>
            	</table>
           	</div>
        </div>
    </div>
</div>

<div class='row'>
    <div class='col-md-12 col-sm-12'>
        <div class="box" style="margin-bottom: 20px">
        	<div class="header">Test Cases </div>
           	<div class="body subBody" style="overflow-x: scroll;scrollbar-width: none;">
                <div id="result"></div>
           		<div style="position: left"></div>
                <table width="100%" id="submission_table" class="table-custom testcase-table">
                	<tr class="submissionTr">
                		<th style="width: 60px;"></th>
                		<th>#</th>
                		<th>Time</th>
                		<th>Memory</th>
                		<th>Points</th>
                		<th></th>
                	</tr>

                	<?php 
                		$testCases = $submission->testCases()->get();
                	?>
                	@foreach($testCases as $key => $testCase)
                	<tr class="submissionTr testcase-tr">
                		<td>
                            <div id="view_test_case_btn_area_{{$testCase->id}}" style="display: {{($testCase->verdict->id != 16 && $testCase->verdict->id >2) ? 'block':'none'}}">
                            
                                <button onclick="submission.viewTestCaseDetail({{$testCase->id}})"><span id="view_test_case_btn_{{$testCase->id}}" class="fa fa-angle-double-down"></span></button>
                            </div>
                        </td>
                		<td>{{$key+1}}</td>
                		<td id="submission_test_case_time_{{$testCase->id}}">{{$testCase->time}} ms</td>
                		<td id="submission_test_case_memory_{{$testCase->id}}">{{$testCase->memory}} kb</td>
                		<td id="submission_test_case_point_{{$testCase->id}}">{{$testCase->passed_point}}</td>
                		<td style="width: 150px;" id="submission_test_case_verdict_{{$testCase->id}}">{!!$testCase->verdict->statusClass()!!}</td>
                	</tr>
                    @if($testCase->verdict->id != 16)
                    <tr class="testcase-details-tr" style="border-width: 0px">
                        <td colspan="12" style="padding: 0px;border-width: 0px!important">
                        <div class="testCaseDetailArea" id="submission_test_case_detail_{{$testCase->id}}">
                            <div class="testCaseDetailBodyArea">
                                <div class="testCaseDetailHeader">Input</div>
                                <div class="testCaseDetailBody" id="submission_test_case_input_{{$testCase->id}}">
                                    {!!nl2br($testCase->input)!!}
                                </div>
                            </div>
                            <div class="testCaseDetailBodyArea">
                                <div class="testCaseDetailHeader">Output</div>
                                <div class="testCaseDetailBody" id="submission_test_case_output_{{$testCase->id}}">
                                    {!!nl2br($testCase->output)!!}
                                </div>
                            </div>
                            <div class="testCaseDetailBodyArea">
                                <div class="testCaseDetailHeader">Answer</div>
                                <div class="testCaseDetailBody" id="submission_test_case_expected_output_{{$testCase->id}}">
                                    {!!nl2br($testCase->expected_output)!!}
                                </div>
                            </div>
                            @if(($testCase->verdict->id != 6 && $testCase->verdict->id != 7) || ($testCase->verdict->id<3))
                            <div class="testCaseDetailBodyArea" id="submission_test_case_checker_log_area_{{$testCase->id}}">
                                <div class="testCaseDetailHeader">Checker Log</div>
                                <div class="testCaseDetailBody" id="submission_test_case_checker_log_{{$testCase->id}}">
                                    {!!nl2br($testCase->checker_log)!!}
                                </div>
                            </div>
                            @endif
                            @if(($testCase->verdict->id == 6 || $testCase->verdict->id == 7) || ($testCase->verdict->id<3))
                            <div class="testCaseDetailBodyArea" id="submission_test_case_compiler_log_area_{{$testCase->id}}">
                                <div class="testCaseDetailHeader">Compiler Log</div>
                                <div class="testCaseDetailBody" id="submission_test_case_compiler_log_{{$testCase->id}}">
                                    {!!nl2br($testCase->compiler_log)!!}
                                </div>
                            </div>
                            @endif
                        </div>
                        </td>
                    </tr>
                    @endif
                	@endforeach
            	</table>
           	</div>
        </div>
    </div>
</div>


<div class='row'>
    <div class='col-md-12 col-sm-12'>
        <div class="box" style="margin-bottom: 20px">
        	<div class="header">Source Code</div>
           	<div class="body">
           		<div class="sourceCodeToggleArea">Code: <a onclick="submission.toggleSourceCode()">(Toggle Highlighting)</a> <a onclick="submission.copySourceCode()">(Copy Source Code)</a></div>
                <?php 

			$sourceCode = $submission->source_code;
 			$geshi = new GeSHi($sourceCode, "c++");
            $geshi->enable_line_numbers(GESHI_FANCY_LINE_NUMBERS,2);
            $geshi->set_line_style('background: #F5FAFA;', 'background: #ffffff;',true);       
?>

            <textarea id="sourceCodeTextArea" class="sourceCodeTextArea" rows="26" readonly="yes">{{$submission->source_code}}</textarea>

            <div id="sourceCodeText">{!!$geshi->parse_code()!!}</div>
           	</div>
        </div>
    </div>
</div>


@if($submission->verdict_id <3)
<script type="text/javascript">
    submission.setSubmissionPage({{$submission->id}});
</script>
@endif