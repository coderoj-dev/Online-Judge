@extends('pages.administration.settings.layout.base')
@section('languages-sub-content')

<style type="text/css">


</style>
<div class="pull-right" style="margin-bottom: 10px;">
    <button
    class="btn btn-primary" onclick="new Modal('md',600).load('{{ route('administration.settings.city.create') }}','Create City')">Create City</button>
</div>
<table class="table-custom">
    <tr>
       <th>Country Name</th>
       <th>City Name</th>
       <th>Time Zone</th>
       <th></th>
   </tr>
   @foreach ($cities as $city)
   <tr>
       <td>{{ $city->country->name }}</td>
       <td>{{ $city->name }}</td>
       <td>{{ $city->time_zone }}</td>
       <td>
        <button
        onclick="new Modal('md',600).load('{{ route('administration.settings.city.edit',['Id' => $city->id]) }}','Update City')"
        class="btn btn-sm btn-primary" title="Edit City"><i class="fa fa-pencil"></i></button>
        <button
        onclick="city.delete($(this))" data-url = "{{ route('administration.settings.city.delete',['Id' => $city->id]) }}" class="btn btn-sm btn-danger" title="Delete City"><i class="fa fa-trash"></i></button>
    </td>
</tr>
@endforeach
</table>

@stop
