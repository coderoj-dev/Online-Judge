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
    	border: 1px solid #eeeeee;
    	padding: 10px 5px 10px 5px!important;
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
        display: none;
        font-size: 12px;
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
                		<th>Author</th>
                		<th>Problem</th>
                		<th>Time</th>
                		<th>Language</th>
                		<th>Time</th>
                		<th>Memory</th>
                		<th></th>
                	</tr>
                	<tr class="submissionTr">
                		<td><a href="">{{$submission->id}}</a></td>
                		<td><a href="">{{$submission->user->handle}}</a></td>
                		<td><a href="">{{$submission->problem->name}}</a></td>
                		<td>{{$submission->created_at}}</td>
                		<td>{{$submission->language->name}}</td>
                		<td id="submission_cpu">{{$submission->time}} s</td>
                		<td id="submission_verdict">{{$submission->memory}}</td>
                		<td style="width: 150px" id="submission_verdict">{!!$submission->verdict->statusClass()!!}</td>
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
                <table width="100%" id="submission_table" class="table-custom submission-table">
                	<tr class="submissionTr">
                		<th></th>
                		<th>#</th>
                		<th>Time</th>
                		<th>Memory</th>
                		<th>Point</th>
                		<th></th>
                	</tr>

                	<?php 
                		$testCases = $submission->testCases()->get();
                	?>
                	@foreach($testCases as $key => $testCase)
                	<tr class="submissionTr">
                		<td><span class="fa fa-angle-double-down"></span></td>
                		<td>{{$key+1}}</td>
                		<td id="submission_cpu">{{$testCase->time}} s</td>
                		<td id="submission_verdict">{{$testCase->memory}}</td>
                		<td>{{$testCase->point}}</td>
                		<td style="width: 150px" id="submission_verdict">{!!$testCase->verdict->statusClass()!!}</td>
                	</tr>
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
           		<div class="sourceCodeToggleArea">Code: <a onclick="toggleSourceCode()">(Toggle Highlighting)</a> <a onclick="copySourceCode()">(Copy Source Code)</a></div>
                <?php 

			$sourceCode = $submission->source_code;
 			$geshi = new GeSHi($sourceCode, "c++");
            $geshi->enable_line_numbers(GESHI_FANCY_LINE_NUMBERS,2);
            $geshi->set_line_style('background: #F5FAFA;', 'background: #ffffff;',true);
               echo $geshi->parse_code();

?>
           	</div>
        </div>
    </div>
</div>


