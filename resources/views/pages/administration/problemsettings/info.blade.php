@extends("pages.administration.problem.problem")
@section('title', 'Update Time and Memory')
@section('problem-sub-content')


<form style="width: 70%" action="{{route('administration.problem.settings.edit',['slug'=>$problem->slug])}}" class="form" id="update_problem" method="post">
    @csrf
    <div class="alert-area">
        <div class="alert alert-danger error-area">ok</div>
        <div class="alert alert-success success-area"></div>
    </div>
    <div class='row' style="margin-bottom: 5px;">
        <div class='col-md-4 label-area'>
            <label> Timelimit <font color="red">*</font>:</label>
        </div>
        <div class="col-md-8">
            <input type="number" class='form-control-input' value='{{ $problem->time_limit }}' name="time_limit" placeholder="Enter New Timelimit">
        </div>
    </div>
    <div class='row' style="margin-bottom: 5px;">
        <div class='col-md-4 label-area'>
            <label> Memorylimit <font color="red">*</font>:</label>
        </div>
        <div class="col-md-8">
            <input type="number" class='form-control-input' value='{{ $problem->memory_limit }}' name="memory_limit" placeholder="Enter New Memorylimit">
        </div>
    </div>
    <div class='row'>
       <div class="col-md-4"></div>
       <div class="col-md-8">
        <div style="">
            <button type="submit" class="btn btn-primary"  onclick="problem.settingsUpdate()"style="margin-top: 15px;">
            Update Problem Settings</button>
        </div>
    </div>
</div>
</form>

@stop

