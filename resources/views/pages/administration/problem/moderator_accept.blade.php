@extends($layout)

@section('content')
<div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8 offset-2">
        <div class="card">
            <div class="card-body">
                Are you accept <strong>{{ $problem->name }}</strong> added by <strong>{{ $owner->handle }}</strong>?
                <button class="btn-success btn-sm" data-url="{{ route('administration.problem.accept_moderator',['slug' => request()->slug]) }}" onclick="problem.acceptProblemModerator($(this))" data-userId = "{{ auth()->user()->id }}">Accept</button> <button onclick='problem.deleteProblemModerator($(this))' class='btn btn-sm btn-danger' data-userId = "{{ auth()->user()->id }}" data-url = "{{ route('administration.problem.delete_moderator',['slug' => request()->slug]) }}">Delete</button>
            </div>
        </div>
    </div>
    <div class="col-md-2"></div>
</div>
@stop