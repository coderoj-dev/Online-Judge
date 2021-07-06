@extends($layout)
@section('title', 'Contests')
@section('content')

<div class="row">
	<div class="col-md-12">
		<div class="box">
			<div class="header">
				<span class="glyphicon glyphicon-list-alt"></span> contests </div>
			<div class="body" style="min-height: 500px">
				
				<div class="pull-right" style="margin-bottom: 10px;">
					<button class="btn btn-primary" onclick="new Modal('custom',600).load('{{route('administration.contest.create')}}','Create contest')">Create Contest</button>
				</div>
				<table class="table-custom">
					<tr>
						<th>#</th>
						<th style="text-align: left;padding-left: 15px">Name</th>
						<th>Owner</th>
						<th>Role</th>
						<th>Created At</th>
						<th></th>
					</tr>

					@foreach($contests as $key=>$contest)
						<tr>
							<td style="width: 5%">{{$contest->id}}</td>
							<td style="width: 45%;text-align: left;padding-left: 15px;"><a href="{{route('administration.contest.overview',['contest_id' => $contest->id])}}">{{$contest->name}}</a></td>
							<td style="width: 15%"><a href="{{route('profile',['handle' => $contest->owner()->handle])}}">{{$contest->owner()->handle}}</a></td>
							<td style="width: 15%">
								<span class="label label-{{$contest->pivot->role == "owner" ? "success":"info"}}"><i class="fas fa-shield-alt"></i> {{$contest->pivot->role}}</span>
								@if(!$contest->pivot->is_accepted)
									<span class="label label-warning"><i class="fa fa-clock-o"></i> pending</span>
								@endif
							</td>
							<td><font title="{{$contest->created_at}}">{{$contest->created_at->diffForHumans()}}</font></td>
							<td>
								<a style="" href="{{route('administration.contest.overview',['contest_id' => $contest->id])}}"><button title="Edit contest" style="padding: 5px;" class="btn btn-sm btn-primary"><i class="fas fa-pencil-square-o"></i> Enter Panel</button></a>
							</td>
						</tr>
					@endforeach
				</table>
				<div style="text-align: center;">
    				{!! $contests->appends(request()->input())->links() !!}
				</div>
			</div>
		</div>
	</div>
</div>
@stop
