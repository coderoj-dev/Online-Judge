@extends("pages.administration.contest.contest")
@section('title', 'Contest Moderators')
@section('contest-sub-content')
    <style media="screen">
        .moderators_suggestion_li {
            padding: 5px;
            border: 1px solid #C2C7D0;
            border-width: 0px 1px 1px 1px;
            margin-top: 1px;
            width: 100%;
            font-size: 16px;
            cursor: pointer;
            border-radius: 0px;
        }

        .moderators_suggestion_li:hover {
            background-color: #A0B4C3;
        }

        .moderators_suggestion_box {
            position: inherit;
            width: 100%;
            margin-top: -8px;
            border-radius: 0px;
        }

        .moderators_suggestion_li_img {
            height: 50px;
            width: 50px;
            margin-right: 5px;
        }

        .userListCard {
            background-color: #ffffff;
            border: 1px solid #eeeeee;
            padding: 10px 0px 10px 0px;
        }

        .moderatorImg {
            width: 80px;
            height: 80px;
        }

        .userListBody {
            margin-top: 15px;
        }

        .userListBody a {
            font-weight: bold;
            font-size: 16px;
        }

        .userPermission {
            font-size: 13px;
            color: #363636;
            font-family: serif;
        }

        /*card start*/
        .card-counter {
            box-shadow: 2px 2px 10px #DADADA;
            margin: 5px;
            margin-bottom: 15px;
            padding: 20px 10px;
            background-color: #fff;
            height: 100px;
            border-radius: 1px;
            transition: .3s linear all;
            border: 1px solid #F4F4F4;
        }

        .card-counter:hover {
            box-shadow: 4px 4px 20px #DADADA;
            transition: .3s linear all;
        }

        .card-counter.custom {
            background-color: #ffffff;
            color: #2C3542;
        }

        .card-counter.primary {
            background-color: #007bff;
            color: #FFF;
        }

        .card-counter.danger {
            background-color: #ef5350;
            color: #FFF;
        }

        .card-counter.success {
            background-color: #66bb6a;
            color: #FFF;
        }

        .card-counter.info {
            background-color: #26c6da;
            color: #FFF;
        }

        .card-counter i {
            font-size: 5em;
            opacity: 0.2;
        }

        .card-counter .count-numbers {
            position: absolute;
            right: 35px;
            top: 20px;
            font-size: 32px;
            display: block;
        }

        .card-counter .count-name {
            position: absolute;
            right: 35px;
            top: 65px;
            font-style: italic;
            text-transform: capitalize;
            opacity: 0.5;
            display: block;
            font-size: 18px;
        }

        /*end card*/

    </style>
    <div class='row'>
        <div class='col-md-6 border'>
            <div>
                
                @foreach ($moderators as $moderator)
                    <div class='boxx none_border'>
                        <div class='box_bodyy' style='padding-left: 15px;'>
                            <div class='row userListCard'>
                                <div class='col-md-3 col-sm-3'>
                                    <img class='img-thumbnail moderatorImg' src='{{ $moderator->avatar }}'>
                                </div>
                                <div class='col-md-9 col-sm-9' >
                                    <div class='userListBody'>
                                        <div class='pull-right'>
                                             
                                            @if ($role == 'owner')
                                                @if (auth()->user()->id != $moderator->id)
                                                    <button onclick='Contest.deleteContestModerator($(this))'
                                                        class='btn btn-sm btn-danger' data-userId = "{{ $moderator->id }}" data-url = "{{ route('administration.contest.moderator.delete_moderator',['contest_id' => request()->contest_id]) }}"><span class='glyphicon glyphicon-trash'></span> Remove</button>
                                                @endif
                                            @elseif (auth()->user()->id == $moderator->id && $role == 'moderator')
                                            <button onclick='Contest.leaveFromModerator($(this))'
                                            class='btn btn-sm btn-danger' data-url = "{{ route('administration.contest.moderator.leave_moderator',['contest_id' => request()->contest_id]) }}">Leave</button>

                                            @endif

                                        </div>
                                      
                                        <a href="{{ route('profile',['handle'=>$moderator->handle]) }}">{{ $moderator->handle }}</a><br />
                                        <div style="margin-top: 5px;"></div>
                                        <span class='label label-{{$moderator->pivot->role == "owner" ? "success" : "info"}}'><i class="fas fa-shield-alt"></i> {{ $moderator->pivot->role }} </span>
                                        @if(!$moderator->pivot->is_accepted)
                                        <span style="margin-left: 5px;" title="User need to accept this moderator request" class='label label-warning'><i class="fa fa-clock-o"></i> Pending</span>
                                        @endif
                                        <br/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        @if($role == "owner")
      
        <div class="col-md-6">
            <div class='box_body'>

                <input type='text' onkeyup='Contest.getModetatorsList($(this))' autocomplete='off' class='form-control'
                    id='search_moderators' placeholder='Enter Contest Moderator Handle' name="search"
                    data-url="{{ route('administration.contest.moderator.get_moderators_list', ['contest_id' => request()->contest_id]) }}"
                    data-add-url="{{ route('administration.contest.moderator.add_moderator', ['contest_id' => request()->contest_id]) }}">

                <div id='suggestion_box' class='moderators_suggestion_box'>
                </div>
             
            </div>
        </div>
        @endif
    </div>
@stop
