@extends("pages.administration.contest.contest")
@section('title', 'Contest Overview')
@section('contest-sub-content')


    <style type="text/css">
        .contstFormBlock {
            font-size: 15px;
            font-weight: bold;
        }

        fieldset {
            background-color: #f9f9f9;
            border: 1px solid #eeeeee;
            padding: 5px;
            border-radius: 5px;
            margin-top: 15px;
        }

        legend {
            background-color: gray;
            color: white;
            width: 180px;
            font-size: 15px;
            padding: 2px;
            margin-left: 15px;
            border-radius: 5px;
            font-weight: bold;
        }

        .labelHint {
            font-size: 12px;
            color: #aaaaaa;
            margin-top: 2px;
        }

        .footerSave {
            background-color: transparent;
            height: 15px;
            width: 100%;
            border: 0px solid #C2C7D0;
            border-width: 0px 0px 0px 0px;
            padding: 5px 10px 45px 55px;
            text-align: right;
        }

        .switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  border-radius: 3px;
  transition: .4s;

}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 3px;
  bottom: 4px;
  border-radius: 3px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}

    </style>


    <div class="form-horizontal">
        <form action="{{ route('administration.contest.update', ['contest_id' => request()->contest_id]) }}"
            id="updateContestForm" method="post" enctype="multipart/form-data">
            @csrf
            <fieldset>
                <legend>
                    <center>
                        Contest Info
                    </center>
                </legend>
                <div class="form-group">
                    <label class="control-label col-sm-3" for=" Contest Name ">
                        Contest Name:
                    </label>
                    <div class="col-sm-9">
                        <input class="form-control" name="name" placeholder="Contest Name" required
                            value="{{ $contest->name }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-3" for=" Contest Name ">
                        Contest Slug:
                    </label>
                    <div class="col-sm-9">
                        {{url('/')}}/.../<span style="border: 1px solid #dddddd;padding: 0px;font-weight: bold;border-radius: 5px; display: inline-block;min-width: 150px;">{{$contest->slug}}</span><br/>
                        <small class="form-text text-muted">
                        Slug can only be updated when you change contest name.<br/></small>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-3" for=" Contest Format ">
                        Contest Format:
                    </label>
                    <div class="col-sm-9">
                        <select class="form-control" name="format">
                            <option value="ioi" {{ $contest->format == 'ioi' ? 'selected' : '' }}>
                                IOI
                            </option>
                            <option value="icpc" {{ $contest->format == 'icpc' ? 'selected' : '' }}>
                                ICPC
                            </option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3" for=" Start Time ">
                        Start Time:
                    </label>
                    <div class="col-sm-9">
                        <input class="form-control" name="start" placeholder="Start Time" type="datetime-local"
                            value="{{ $contest->start->format('Y-m-d') . 'T' . $contest->start->format('H:i') }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3" for=" Duration ">
                        Duration:
                    </label>
                    <div class="col-sm-9">
                        <input class="form-control" min="1" name="duration" placeholder="Duration" required="" type="number"
                            value="{{ $contest->duration }}">
                        <small class="form-text text-muted">
                            Contest duration in minutes
                        </small>
                        <br />

                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3" for=" Contest Publish ">
                        Contest Publish:
                    </label>
                    <div class="col-sm-9">
                       <label class="switch">
                            <input type="checkbox" name="publish" {{ $contest->publish ? 'checked' : '' }}>
                            <span class="slider"></span>
                        </label>


                    </div>
                </div>
            </fieldset>
            <fieldset>
                <legend>
                    <center>
                        Contest Description
                    </center>
                </legend>
                <div class="form-group">
                    <label class="control-label col-sm-3" for=" Contest Banner ">
                        Contest Banner:
                    </label>
                    <div class="col-sm-9">
                        <input type="file" class="form-control" name="banner" placeholder="Contest Banner"
                            onchange="Contest.loadFileBanner(event)">
                        <br />
                        <img class="img-thumbnail" id="contestBannerPreview" src="{{ $contest->banner }}" alt="Banner"
                            style="height: 150px;margin-top: 10px" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3" for=" Contest Description ">
                        Contest Description:
                    </label>
                    <div class="col-sm-9">
                        <textarea id="contestDescriptionEditor" name="contestDescriptionEditor"
                            placeholder="Contest Description"></textarea>
                    </div>
                </div>
            </fieldset>
            <fieldset>
                <legend>
                    <center>
                        Contest Privacy
                    </center>
                </legend>
                <div class="form-group">
                    <label class="control-label col-sm-3" for=" Contest Visibility ">
                        Contest Visibility:
                    </label>
                    <div class="col-sm-9">
                        <select class="form-control" name="visibility">
                            <option value="public" {{ $contest->visibility == 'public' ? 'selected' : '' }}>
                                Public - any one can registration and participate
                            </option>
                            <option value="protected" {{ $contest->visibility == 'protected' ? 'selected' : '' }}>
                                Protected - any one can registration and participate but before registration need password
                            </option>
                            <option value="private" {{ $contest->visibility == 'private' ? 'selected' : '' }}>
                                Private - only invited user can participate
                            </option>
                        </select>
                    </div>
                </div>
                <div id="contestRegistraionFormInputArea" style="display: block">
                    <div id="contestPassword" style="display: none">
                        <div class="form-group">
                            <label class="control-label col-sm-3" for=" Contest Password ">
                                Contest Password:
                            </label>
                            <div class="col-sm-9">
                                <input type="password" class="form-control" name="password" placeholder="Contest Password">
                                <small class="form-text text-muted">
                                    User need this password before registration
                                </small>
                                <br />

                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3">
                            Contest Password:
                        </label>
                        <div class="col-sm-9">
                            <input value="{{$contest->password}}" class="form-control" style="margin-top: 10px;" name="password" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3" for=" Registration Auto Accept ">
                            Registration Auto Accept:
                        </label>
                        <div class="col-sm-9">
                             <label class="switch">
                                <input type="checkbox" name="registration_auto_accept" {{ $contest->registration_auto_accept ? 'checked' : '' }}>
                                <span class="slider"></span>
                                </label>
                        </div>
                    </div>
                </div>
            </fieldset>
            <fieldset>
                <legend>
                    <center>
                        Perticipate Info
                    </center>
                </legend>
                <div class="form-group">
                    <label class="control-label col-sm-3" for=" Contest Name ">
                        Perticipate Main Name
                    </label>
                    <div class="col-sm-9">
                        <input class="form-control" name="participate_main_name" placeholder="Participate Main Name"
                            value="{{ $contest->participate_main_name }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3" for=" Contest Name ">
                        Perticipate Sub Name
                    </label>
                    <div class="col-sm-9">
                        <input class="form-control" name="participate_sub_name" placeholder="Participate Sub Name"
                            value="{{ $contest->participate_sub_name }}">
                    </div>
                </div>

            </fieldset>
            <div class="footer navbar-fixed-bottom footerSave">
                <button id="saveContestDataBtn" class="btn btn-primary" type="submit" onclick="Contest.update()">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
<script type="text/javascript">

    Contest.setEditor('{!!base64_encode($contest->description)!!}');
</script>

@stop