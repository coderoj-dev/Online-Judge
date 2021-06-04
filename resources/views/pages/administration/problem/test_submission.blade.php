@extends("pages.administration.problem.problem")
@section('title', 'Problem Details')
@section('problem-sub-content')

<button onclick="new Modal('lg').load('{{route('administration.problem.test_submission.create',['slug' => request()->slug])}}')">Create Test Submission</button>

<i class='fa fa-refresh fa-spin fa-1x fa-fw'></i> hey
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
			<td><a href="#" onclick="new Modal('lg').load('{{route('administration.problem.submission.view',['slug'=> request()->slug,'submission_id' => $submission->id])}}')"><u>{{$submission->id}}</u></a></td>
			<td>{{$submission->created_at->format('M/d/Y h:i:s')}}</td>
			<td><a href="{{route('profile',[ 'handle' => $submission->user->handle])}}">{{$submission->user->handle}}</a></td>
			<td>{{$submission->language->name}}</td>
			<td>{!!$submission->verdict->statusClass()!!}</td>
			<td>{{$submission->time}}</td>
			<td>{{$submission->memory}}</td>
		</tr>
	@endforeach

</table>


@stop
