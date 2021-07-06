@extends($layout)
@section('title', 'Problems')
@section('content')

<style type="text/css">
	

</style>

<div class="row">
	<div class="col-md-12">
		<div class="box">
			<div class="header">
				<span class="glyphicon glyphicon-list-alt"></span> Contests </div>
			<div class="body" style="min-height: 500px">
				
				<div class="pull-right" style="margin-bottom: 10px;">
					<button class="btn btn-primary" onclick="new Modal('md',500).load('{{route('problem.create')}}','Create Problem')">Create Problem</button>
				</div>
				<table class="table-custom">
					<tr>
						<th>#</th>
						<th style="text-align: left;padding-left: 15px">Name</th>
						<th>Tests</th>
						<th>Owner</th>
						<th>Role</th>
						<th>Created At</th>
						<th></th>
					</tr>

					@foreach($problems as $key=>$problem)
						<tr>
							<td style="width: 5%">{{$problem->id}}</td>
							<td style="width: 42%;text-align: left;padding-left: 15px;"><a href="{{route('administration.problem.overview',['slug' => $problem->slug])}}">{{$problem->name}}</a></td>
							<td style="width: 5%"><span>{{$problem->testcases()->count()}}</span></td>
							<td style="width: 15%"><a href="{{route('profile',['handle' => $problem->owner()->handle])}}">{{$problem->owner()->handle}}</a></td>
							<td style="width: 13%">
								<span class="label label-{{$problem->pivot->role == "owner" ? "success":"info"}}"><i class="fas fa-shield-alt"></i> {{$problem->pivot->role}}</span>
								@if(!$problem->pivot->is_accepted)
									<span class="label label-warning"><i class="fa fa-clock-o"></i> pending</span>
								@endif
							</td>
							<td><font title="{{$problem->created_at}}">{{$problem->created_at->diffForHumans()}}</font></td>
							<td>
								<a style="" href="{{route('administration.problem.overview',['slug' => $problem->slug])}}"><button title="Edit Problem" style="padding: 5px;" class="btn btn-sm btn-primary"><i class="fas fa-pencil-square-o"></i> Enter Panel</button></a>
							</td>
						</tr>
					@endforeach
				</table>
				<div style="text-align: center;">
    				{!! $problems->appends(request()->input())->links() !!}
				</div>
			</div>
		</div>
	</div>
</div>
@stop
