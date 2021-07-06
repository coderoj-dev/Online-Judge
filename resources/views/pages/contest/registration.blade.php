

<form action="{{route('contests.registration',request()->contest_slug)}}" id="contest_sign_up_form" method="post">
@csrf
@if($contest->visibility == "protected")
<label>Contest Password</label><br/>
<input type="text" class="form-control" name="contest_password"><br/>
@endif
<button class="btn btn-primary" type="submit" onclick="Contest.signUp()">Sign-Up</button>

</form>