<style type="text/css">


.timer{
	width: 18px;
	padding: 5px;
	font-weight: bold;
	font-size: 18px;
	background-color: #E74C3C;
	border-color: #E74C3C;
	border-width: 1px;
	border-style: solid;
	border-radius: 5px;
}


.contest_head_menu{
	text-align: center;
	color: #ffffff;
	background-color: #2C3542;
	border-radius: 0px 0px 150px 150px;
    height: 110px;
 	padding-bottom: 3px;
 	margin-bottom: 25px;
 	margin-top: -25px;

}
.contestStatus{
	font-size: 20px;
	font-family: "Lucida Console", Monaco, monospace;
 	margin-top: 5px;
}

.contest_time{
	font-size: 15px;
}
.dashboard_left_panel_head{
	background-color: var(--menu_header);
	color: #ffffff;
	padding: 15px;
	font-size: 17px;
	font-weight: bold;

}

.contestNav{
	background: #ffffff;
	
}

.contestTitleArea{
	margin : 0px 0px 0px 0px;
	text-align: center;
	color: #ffffff;
	padding: 30px 20px 15px 0px;
	background-image: url("{{asset("assets/img/geometry.png")}}");
	border: 1px solid #DFDFDF;
	border-width: 0px 0px 1px 0px;
}
.contestTitle{
	font-size: 35px;
	color: #3E3F3A;
 	font-family: "Exo 2";
 	font-weight: bold;
 	margin-bottom: 5px;
}
.contestTime{
	font-size: 18px;
	color: #52544e;
	font-weight: bold;
 	font-family: "Exo 2";
}
.contestBanner{
	background: #2B9B9F;
	color: #ffffff;
	font-size: 40px;
	text-align: center;
}
.contestArea{
	background-color: #ffffff;
	margin-top: -45px;
	box-shadow: 0 0 5px 1px #aaaaaa;

}

.contest_body{
	padding: 20px 10px 20px 10px;
	border: 1px solid #DDDDDD;
	border-top-width: 0px;


}
.contestBox{
	background-color: #ffffff;
	/*border: 1px solid #DFDFDF;*/
	border-radius: 2px;
	margin-bottom: 15px;
}
.contestBoxHeader{
	background: linear-gradient(to bottom right, #ffffff, #eeeeee);
	color: #2F353B;
	padding: 10px 10px 10px 10px;
	font-weight: bold;
	font-size: 14px;
	border-radius: 0px;
	margin-bottom: 1px;
	border: 1px solid  #E7ECF1;
	font-family: "Exo 2";
}
.contestBoxBody{
	background-color: #ffffff;
	border: 1px solid #E7ECF1;
	padding: 10px;
	border-radius: 0px;
}

.progress{
    height: 12px;
    background: #d4d7e6;
    border-radius: 40px;
    box-shadow: none;
    overflow: visible;
}
.progress .progress-bar{
    border-radius: 30px;
    box-shadow: none;
    position: relative;

}

.progress .progress-bar:after{
    content: "";
    width: 25px;
    height: 25px;
    border-radius: 50%;
    background: #cbcbcb;
    border: 6px solid #000;
    position: absolute;
    bottom: -6px;
    right: 0;
    z-index: 1;
}
.contestProgressBarArea{
	margin-top: 15px;
	margin-bottom: -30px;
}

.contestAreaBody{
	padding: 7px;
}

	.problemListBox{
		margin-bottom: 2px;
		padding: 8px;
		border: 1px solid #EEEEEE;
	}
	.problemNo{
		font-size: 35px;
		margin-top: -6px; 
		margin-right: 16px; 
		text-align: center; 
		width: 50px;
	}

	.p_list{
  		padding: 15px;
  		border-style: solid;
  		border-width: 1px;
  		border-color: #E7ECF1;
  		font-size: 18px;
  		margin-bottom: 5px;
  	}
  	.contestInfoRight{
  		text-align: center;
  		color: #000000;
  		position: -webkit-sticky;
  		position: sticky;
  		top: 118px;
  		font-weight: bold;
  		z-index: 99999!important;
  		

  	}
  	.contestInfoRightTitle{
  		border: 1px solid #E7ECF1;
  		border-width: 0px 0px 1px 0px;
  		padding-bottom: 10px;
  		margin-bottom: 10px;
  		color: #2F353B;
  	}
  	.contestTimeRight{
  		text-align: center;
  	}
  	.contestTimeRightSpan{
  		color: #ffffff;
  		background: var(--bg-color);
  		border-radius: 10px;
  		padding: 6px;
  		margin-right: 1px;
  		font-size: 17px;
  	}


</style>

<div class="contestTitleArea">
	<div class="row"></div>
	<div class="contestTitle">{{$contest->name}}</div>
	<div class="contestTime">
		@if($contest->status != "past")
		<div id="timerArea">
			{{$contest->timerReadAble()}} 
		</div>
		@endif
	<div id="contestStatusTxt">
		@if($contest->status == "running")
        Contest Is Runnning
        @elseif($contest->status == "upcomming")
        Contest Is Not Start
        @else
        Contest Is End
        @endif
	</div>
</div>
</div>

<script type="text/javascript">
	Contest.setTimer({{$contest->timer()}},"{{$contest->status}}")
</script>
<input type="text" id="startcontesttimer" value="start" name="" hidden="">