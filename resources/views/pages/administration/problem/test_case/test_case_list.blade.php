@extends("pages.administration.problem.problem")
@section('title', 'Problem Overview')
@section('problem-sub-content')
<style type="text/css">
	
	.table-custom td{
		padding: 5px;
	}
	.ace_body{
		background-color: #000000;
	}
	.ace_cursor { 
    border-left: 4px solid !important;
}
.ace_emphasis {
    font-style: italic;
    font-weight: 600;  
    color: gold !important;
}

</style>
	<div style='text-align: right; margin-bottom: 10px;'><button class="btn btn-primary" onclick="new Modal('md').load('{{route('administration.problem.test_case.add',['slug' => request()->slug])}}')"><span class='glyphicon glyphicon-plus'></span> Add Test Case</button></div>
	<table width='100%' class="table-custom">
		<tr>
			<th>Order</th>
			<th>Input File</th>
			<th>Output File</th>
			<th>Sample</th>
			<th>Points</th>
			<th>Added Date</th>
			<th>Added By</th>
			<th></th>
		</tr>
		@php 
			$testCases = $problem->testCases()->get();
			$serial = 0;
		@endphp
		@foreach ($testCases as $key => $testCase)
		<tr>
			<td>{{++$serial}}</td>
			<td>
				<a title="View Input-{{$serial}} File" href="{{route('problem.test_case.input.view',['slug' => request()->slug,'test_case_serial' => $serial])}}" target="_blank">input-{{$serial}}.txt </a> | 
				<a title="Download Input File" target="_blank" href="{{route('problem.test_case.input.download',['slug' => request()->slug,'test_case_serial' => $serial])}}"><i class="fa fa-download"></i></a>
				<br/><small class="text-muted">(<span title="File Size">{{$testCase->inputLength()}} Bytes</span> | <span title="Last Update">{{$testCase->inputLastModified()->diffForHumans()}})</span></small> 
			</td>
			<td>
				<a title="View Output-{{$serial}} File" href="{{route('problem.test_case.output.view',['slug' => request()->slug,'test_case_serial' => $serial])}}" target="_blank">output-{{$serial}}.txt </a> | 
				<a title="Download Output File" target="_blank" href="{{route('problem.test_case.output.download',['slug' => request()->slug,'test_case_serial' => $serial])}}"><i class="fa fa-download"></i></a>
				<br/><small class="text-muted">(<span title="File Size">{{$testCase->outputLength()}} Bytes</span> | <span title="Last Update">{{$testCase->outputLastModified()->diffForHumans()}})</span></small> 
			</td>
			<td><input type="checkbox" name="{{route('problem.test_case.update_sample',['slug' => request()->slug,'test_case_id' => $testCase->id])}}" value="true" onchange="testCase.updateSample(this)" {{$testCase->sample ? "checked" : ""}}></td>
			<td>{{$testCase->point}}</td>
			<td>{{$testCase->created_at}}</td>
			<td><a href="{{route('profile',['handle' => $testCase->user->handle])}}">{{$testCase->user->handle}}</a></td>
			<td>
				<button value='' onclick="new Modal('md').load('{{route('problem.test_case.edit',['slug' => request()->slug,'test_case_id' => $testCase->id])}}','Edit Test Case')" class='btn btn-sm btn-success' onclick ='' id='updateTestCaseBtn'><span class='glyphicon glyphicon-pencil'></span></button>
				<button value='' class='btn btn-sm btn-danger' url="{{route('problem.test_case.delete',['slug' => request()->slug,'test_case_id' => $testCase->id])}}" onclick ='testCase.delete(this)' id='updateTestCaseBtn'><span class='glyphicon glyphicon-trash'></span></button>
			</td>
		</tr>
		@endforeach
	</table>
@stop
