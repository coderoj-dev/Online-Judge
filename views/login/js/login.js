document.onkeydown=function(evt){
    var keyCode = evt ? (evt.which ? evt.which : evt.keyCode) : event.keyCode;
    if(keyCode == 13){
        login();
    }
}
function login(){
	var data = {
		'handle': $("#handle").val(),
		'password': $("#password").val()
	}

	btnOff("btn_login","Login Process");
	$("#login_success").hide();
	$("#login_failed").hide();
	
	$.post("site_enter_action.php",buildData("login",data),function(response){
		
		// debug----------------
		//$("#login_failed").show();
		//$("#login_failed").html(response);

		response=JSON.parse(response);
		var login_div=!response.error?"login_success":"login_failed";

		$("#"+login_div).show();
		$("#"+login_div).html(response.errorMsg);
		
		if(response.error==0)
			location.reload();
		else btnOn("btn_login","Login Your ID");

	});
}