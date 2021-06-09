@extends("pages.administration.problem.problem")
@section('title', 'Problem Details')
@section('problem-sub-content')

<style type="text/css">
	.submit-btn:focus{
		outline: none;
		color: #ffffff;
	}
	.submit-btn:hover{
		outline: none;
		color: #ffffff;
	}

</style>

<div class="row">
        <div class="col-md-12">
            <div class="pull-right">
                <button class="btn submit-btn" style="margin-bottom: 15px;" onclick="new Modal('lg').load('{{route('administration.problem.test_submission.create',['slug' => request()->slug])}}','Create Test Submission')">Create Test Submission</button>

            </div>
        </div>
    </div>

<table class="table-custom">
	<tr>
		<th>#</th>
		<th>When</th>
		<th>Who</th>
		<th>Lang</th>
		<th>Verdict</th>
		<th>Time</th>
		<th>Memory</th>
	</tr>
	@foreach($submissions as $key => $submission)
		<tr>
			<td><a href="" onclick="new Modal('lg').load('{{route('administration.problem.submission.view',['slug'=> request()->slug,'submission_id' => $submission->id])}}','Submission #{{$submission->id}}')"><u>{{$submission->id}}</u></a></td>
			<td>{{$submission->created_at->format('M/d/Y h:i:s')}}</td>
			<td><a href="{{route('profile',[ 'handle' => $submission->user->handle])}}">{{$submission->user->handle}}</a></td>
			<td>{{$submission->language->name}}</td>
			<td>{!!$submission->verdictStatus()!!}</td>
			<td>{{$submission->time}}</td>
			<td>{{$submission->memory}}</td>
		</tr>
	@endforeach

</table>

@stop
