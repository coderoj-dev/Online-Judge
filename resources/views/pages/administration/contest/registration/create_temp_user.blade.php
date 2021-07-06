<form class="form" action="{{route('administration.contest.registrations.create_temp_user',['contest_id' => request()->contest_id])}}" method="post" id="generateTempUser"  enctype="multipart/form-data">
	@csrf
	<div class="col-md-12" style="margin-top: -5px">
         <div class="alert-area">
            <div class="alert alert-danger error-area"></div>
            <div class="alert alert-success success-area"></div>
         </div>
     </div>
     <div class='row' style="margin-bottom: 15px;">
       	<div class='col-md-4 label-area'>
            <label> Handle Prefix<font color="red"> *</font>:</label>
        </div>
        <div class="col-md-8">
                <input type="text" class='form-control' name="handle_prefix" >
                <small class="text-muted"> Only (A-Z), (a-z), (_) allowed and prefix length must be (4 - 30)</small>
        </div>
    </div>
    <div class='row' style="margin-bottom: 15px;">
       	<div class='col-md-4 label-area'>
            <label> Password Length<font color="red"> *</font>:</label>
        </div>
        <div class="col-md-8">
                <input type="number" class='form-control' name="password_length">
                <small class="text-muted"> Password length must be (4 - 16)</small>
        </div>
    </div>

    <div class='row' style="margin-bottom: 25px;">
       	<div class='col-md-4 label-area'>
            <label> User Data File<font color="red"> *</font>:</label>
        </div>
        <div class="col-md-8">
        	<input type="file" name="data_file" accept=".csv" class='form-control'>
        	<small class="text-muted"> .csv formate is allowed</small>
        </div>
    </div>
    <div class='row' style="margin-top: 5px;">
       	<div class='col-md-4 label-area'>
           
        </div>
        <div class="col-md-8">
        	<button class="btn btn-primary" type="submit" onclick="Contest.generateTempUser(this)">Generate Temp User</button>
        </div>
    </div>

	
</form>