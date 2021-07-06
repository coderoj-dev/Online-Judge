@extends("pages.administration.contest.contest")
@section('titleSub', 'Contest Problems')
@section('contest-sub-content')
    <div class="row">
        <div class="col-md-12">
            <div class="contestBox">
                <div class="contestBoxBody">
                    @include('pages.problem.layout.default',['problem' => $problem,'contest_serial' => 'A'])
                </div>
            </div>
        </div>
    </div>
@stop
