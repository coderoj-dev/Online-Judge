@extends($layout)
@section('content')
@section('title', 'Administraion')

@php
  $titleSub = "Administration";
@endphp

<style type="text/css">
	.panel-sidebar-btn{
  	border: 1px solid #dddddd;
    background-color: #f9f9f9;
  	color: #606060;
  	font-weight: bold;
  	margin-bottom: 5px;
  	font-size: 13px;
  	padding: 12px;
  	border-radius: 5px;
  }
  .panel-sidebar-btn:hover{
  	background-color: #eeeeee;
    border: 1px solid #aaaaaa;
  	color: #000000;
  }
  .panel-sidebar-btn:focus{
  	outline: none;
  }
  .sidebar-btn{
    width: 100%;
    text-align: left;
  }
  .sidebar-btn-active{
  	background-color: #eeeeee;
    border: 1px solid #aaaaaa;
  	color: #000000;
  	font-size: 15px;
  }
</style>
<div class="row">
	<div class="col-md-3">
		<a href="{{ route('administration.settings.languages') }}" class="btn btn-info btn-sm m-2">Languages</a>
	</div>
	
</div>

@stop

