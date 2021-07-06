@extends("pages.administration.contest.contest")
@section('titleSub', 'Contest Problems')
@section('contest-sub-content')
    <style type="text/css">
        .table-custom td {
            padding: 5px;
        }

    </style>
    <div style='text-align: right; margin-bottom: 10px;'><button class="btn btn-primary"
            onclick="Contest.addProblem($(this))"
            url="{{ route('administration.contest.add_problem', ['contest_id' => request()->contest_id]) }}"><span
                class='glyphicon glyphicon-plus'></span> Add Problem</button></div>
    <table width='100%' class="table-custom">
        <tr>
            <th></th>
            <th>Title</th>
            <th>Owner</th>
            <th>Test Cases</th>
            <th>Added By</th>
            {{-- <th>Added At</th> --}}
            <th></th>
        </tr>
        @foreach ($problems as $k => $problem)
            <tr>
                <td>{{ chr($k + 65) }}</td>
                <td>
                    <a
                        href="{{ route('administration.contest.viewProblem', ['contest_id' => request()->contest_id, 'problem' => $problem, 'sl' => chr($k + 65)]) }}">{{ $problem->name }}</a>

                </td>
                <td>{{ $problem->owner()->handle }}</td>
                <td>{{ $problem->testCases()->count() }}</td>
                <td>
                    {{-- @php
                        dd($problem->problemContestAddedBy(request()->contest_id));
                    @endphp --}}
                    Added By

                </td>
                <td>
                    <button class="btn btn-danger" onclick="Contest.removeProblem($(this))"
                        url="{{ route('administration.contest.remove_problem', ['contest_id' => request()->contest_id, 'problem_id' => $problem->id]) }}">
                        Remove
                    </button>
                </td>
            </tr>
        @endforeach

    </table>
@stop
