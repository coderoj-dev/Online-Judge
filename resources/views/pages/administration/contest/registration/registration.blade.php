@extends("pages.administration.contest.contest")
@section('title', 'Contest Overview')
@section('contest-sub-content')
<script type="text/javascript">
$(document).ready(function() {
     registrationDataTable = $('#registrationTable').DataTable({
        'processing': true,
        'responsive': true,
        'serverSide': true,
        'serverMethod': 'post',
        'scrollX': true,
        "lengthMenu": [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
        "order": [[ 1, "desc" ]],
        
        'ajax': {
            'url':"{{route('administration.contest.registrations.datatable_api',['contest_id' => request()->contest_id])}}",
            'data': {
                '_token' : app.token
            }
        },
        'columns': {!!$tableColumnJson!!}
   });
    
} );
  function checkAllRegistrationList(e){
    $("input[name='contestRegistrationList[]']").attr('checked', e.checked);
  }
</script>

<style type="text/css">
    table{
        font-size: 14px;
    }
    table th{
        border: 1px solid #eeeeee;
        background-color: #f8f8f8;
        padding: 8px 3px 8px 3px;
        font-weight: bold;
    }
    table td{
        text-align: center;
    }
</style>


<div class="pull-right" style="margin-bottom: 20px;">


    <button class="btn btn-sm btn-info" url="{{route('administration.contest.registrations.send_mail_view',['contest_id' => request()->contest_id])}}" onclick="Contest.viewMail($(this))">Send Email</button>


      <button class="btn btn-sm btn-success" status="Accepted" url="{{route('administration.contest.registrations.update_registration_status',['contest_id' => request()->contest_id])}}" onclick="Contest.updateParticipantRegistration($(this))"><i class="fa fa-check"></i> Accepted</button>
      <button class="btn btn-sm btn-warning" status="Pending" url="{{route('administration.contest.registrations.update_registration_status',['contest_id' => request()->contest_id])}}" onclick="Contest.updateParticipantRegistration($(this))"><i class="fa fa-clock-o"></i> Pending</button>
      <button class="btn btn-sm btn-danger" status='Delete' url="{{route('administration.contest.registrations.update_registration_status',['contest_id' => request()->contest_id])}}"  onclick="Contest.updateParticipantRegistration($(this))"><i class="fas fa-trash"></i> Delete</button>
      <button class="btn btn-sm btn-primary" onclick="new Modal('md').load('{{route('administration.contest.registrations.add_participants',['contest_id' => request()->contest_id])}}','Add Participants')">Add Participants</button>
      <button class="btn btn-sm btn-primary" onclick="new Modal('md').load('{{route('administration.contest.registrations.create_temp_user',['contest_id' => request()->contest_id])}}','Generate Temp User')">Generate Temp User</button>

</div>

<br/>
<table cellspacing="0" class="table table-striped table-bordered dt-responsive nowrap" id="registrationTable" style="" width="100%">
    <thead>
        <tr>
            @foreach($tableColumn as $key => $value)
            <th class="contestTd1">
                {!!$value['data'] == "action" ? "<input type='checkbox' id='checkAllRegistrationList' onchange='Contest.checkAllRegistrationList(this)'>" : $value['data']!!}
            </th>
            @endforeach
        </tr>
    </thead>
</table>
@stop
