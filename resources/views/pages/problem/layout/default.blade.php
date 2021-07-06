
<style type="text/css">
	.problem-top-area{
		margin-bottom: 30px;
	}
	.problem-top-area .name{
		font-size: 25px;
		font-weight: bold;
		margin-bottom: 15px;
	}
	.problem-top-area .limit{
		margin-bottom: 15px;
	}
	.problem-statement-block{
		margin-bottom: 25px;
		font-size: 14px;
	}
	.problem-statement-block .title{
		font-size: 19px;
		font-weight: bold;
		margin-top: 30px;
		margin-bottom: 5px;
		color: #373c42;
	}

	.th_input_ex {
	font-size: 14px;
	background-color: #f8f8f8;
	padding: 5px 10px 5px 10px !important;
}

.td_pre {
	width: 100%;
	font-size: 13px;
	padding: 3px 3px 3px 10px !important;
	background-color: #ffffff;
}

.cpyBtn{
	padding: 1px 5px 1px 5px;
	font-size: 13px;
	border: 1px solid #DDDDDD;
	background-color: #f8f8f8;
	font-weight: bold;
	color: #A49A8F;
}
.cpyBtn:hover{
	background-color: #f3f3f3;
	color: #A49A8F;
}
.cpyBtn:focus{
	background-color: #f2f2f2;
	color: #A49A8F;
	outline: none;
}
.problem{
	
}

</style>
<div class="problem">
<div class="problem-top-area">
 	<div class="name">{{isset($contest_serial) ? $contest_serial.". " : ""}}{{$problem->name}}</div>
 	<div class="limit">
 		Time: {{(float)number_format($problem->time_limit/1000,2)}} s<br>
		Memory: {{(float)number_format($problem->memory_limit/1024,2)}} MB
	</div>
</div>

@if(trim($problem->problem_description != ""))
<div class="problem-statement-block">
	{!!$problem->problem_description!!}
</div>
@endif

@if(trim($problem->input_description != ""))
<div class="problem-statement-block">
	<div class="title">Input</div>
	{!!$problem->input_description!!}
</div>
@endif

@if(trim($problem->constraint_description != ""))
<div class="problem-statement-block">
	<div class="title">Constraint</div>
	{!!$problem->constraint_description!!}
</div>
@endif

@if(trim($problem->output_description != ""))
<div class="problem-statement-block">
	<div class="title">Output</div>
	{!!$problem->output_description!!}
</div>
@endif
@php
	$testCases = $problem->testCasesSample()->get();
@endphp
@if(count($testCases) != 0)
<div class="problem-statement-block">
	<div class="title">Examples</div>
	
	@foreach ($testCases as $key => $testCase)
	@php
		$input = $testCase->input();
		$input = strlen($input) >= 250 ? substr($input,0,250)."..." : $input;

		$output = $testCase->output();
		$output = strlen($output) >= 250 ? substr($output,0,250)."..." : $output;

	@endphp
	<table class='table table-bordered'>
         <thead>
            <tr>
                <th class='th_input_ex' style='width: 50%'>Input <div class='pull-right'><button value='problemSampleInput_{{$key+1}}' class='btn-sm cpyBtn' onclick='problem.copyTestCase(this)'>copy</button></div></th>
                <th class='th_input_ex' style='width: 50%'>Output<div class='pull-right'><button class='btn-sm cpyBtn' value='problemSampleOutput_{{$key+1}}' onclick='problem.copyTestCase(this)'>copy</button></div></th>
            </tr>
        </thead>
        <tbody style='background-color: #EFEFEF'>
            <tr>
                <td class='td_pre' style='width: 50%; padding: 0px;'>
                    <div id='problemSampleInput_{{$key+1}}'>{!!nl2br(htmlspecialchars($input))!!}</div>  
                </td>
                <td style='padding:0px;' class='td_pre'>
                    <div id='problemSampleOutput_{{$key+1}}'>{!!nl2br(htmlspecialchars($output))!!}</div>
                </td>
            </tr>
        </tbody>
    </table>
    @endforeach
</div>
@endif


@if(trim($problem->notes != ""))
	<div class="problem-statement-block">
		<div class="title">Notes</div>
		{!!$problem->notes!!}
	</div>
@endif

</div>

<script type="text/javascript">
	if(typeof MathJax !== 'undefined') {MathJax.Hub.Queue(["Typeset",MathJax.Hub]);}
</script>