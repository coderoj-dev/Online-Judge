Enter the handles of the participants (one per line) that you want to add to the contest.

<form action="{{route('administration.contest.registrations.add_participants',['contest_id' => request()->contest_id])}}" id="add-participants-form" method="post">
	@csrf
	<div class="col-md-12" style="">
         <div class="alert-area">
            <div class="alert alert-danger error-area"></div>
            <div class="alert alert-success success-area"></div>
         </div>
     </div>
	<textarea class="form-control" id="participants_list" name="participants_list" rows="15" style="margin-top: 15px;border: 1px solid #aaaaaa"></textarea>
	<button class="btn btn-primary" type="submit" onclick="Contest.addParticipants(this)" style="margin-top: 15px;">Add</button>
</form>

