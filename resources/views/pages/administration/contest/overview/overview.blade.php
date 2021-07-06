@extends("pages.administration.contest.contest")
@section('title', 'Contest Overview')
@section('contest-sub-content')

@php
    $totalProblems = $contest->problems()->count();

@endphp

<script type="text/javascript">
    Contest.setTimer({{$contest->timer()}},"{{$contest->status}}")
</script>

<center>
    <h2>{{$contest->name}}</h2>
    <h4><div class="listTitle" id="timerArea">
        {{$contest->timerReadAble()}}    
    </div> 
    <div class="listLabel" id="contestStatusTxt" style="margin-top: 5px;">
        @if($contest->status == "running")
        Contest Is Runnning
         @elseif($contest->status == "upcomming")
        Contest Is Not Start
        @else
         Contest Is End
        @endif

    </div> 
    </h4>                 
</center>

<div class="row" style="margin-top: 35px;">
    <div class="col-md-3">
        <label>Start Time</label><br>
        {{ $contest->start->format(' M d Y, g:i A') }}

    </div>
    <div class="col-md-3">
        <label>Duration</label><br>
        {{$contest->duration}} minutes
    </div>
    <div class="col-md-3">
        <label>End Time</label><br>
        {{ $contest->end->format(' M d Y, g:i A') }}
    </div>
    <div class="col-md-3">
        <label>Visibility</label><br>
        {{ ucfirst($contest->visibility) }}
    </div>
    <div class="col-md-3">
        <div style="margin-top: 20px;"></div>
        <label>Format</label><br>
        {{ strtoupper($contest->format) }}
    </div>
    <div class="col-md-3">
        <div style="margin-top: 20px;"></div>
        <label>Publish</label><br>
        {!! $contest->publish ? "<i class='fas fa-check'></i>":"<i class='fas fa-times'></i>" !!}
    </div>
    <div class="col-md-3">
        <div style="margin-top: 20px;"></div>
        <label>Status</label><br>
        {{ ucfirst($contest->status) }}
    </div>
    <div class="col-md-3">
        <div style="margin-top: 20px;"></div>
        <label>Timer</label><br>
        {{$contest->timerReadAble()}}    
    </div>
    <div class="col-md-12">
        <div style="margin-top: 20px;"></div>
        <label>Url</label><br>
        {{route('contests.info',['contest_slug' => $contest->slug])}}
        <a href="{{route('contests.info',['contest_slug' => $contest->slug])}}">
            <button class="btn btn-default" style="padding: 3px;"><i class="fa fa-external-link" aria-hidden="true"></i> Open</button>
        </a>
    </div>
</div>

<div style="margin-top: 30px;text-align: center;" >

<a href="{{route('contest.arena.standings',['contest_slug' => $contest->slug])}}">
    <button class="btn btn-primary"><i class="fa fa-trophy" aria-hidden="true"></i> Standings</button>
</a>
<a href="{{route('contest.arena.problems',['contest_slug' => $contest->slug])}}">
    <button class="btn btn-primary"><i class="fas fa-futbol"></i> Arena</button>
</a>

</div>

<input type="text" id="startcontesttimer" value="start" name="" hidden="">


@stop
