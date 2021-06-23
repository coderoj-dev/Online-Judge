@extends('pages.administration.settings.layout.base')
@section('languages-sub-content')

<style type="text/css">


</style>
<div class="pull-right" style="margin-bottom: 10px;">
    <button
    class="btn btn-primary" onclick="new Modal('md',600).load('{{ route('administration.settings.country.create') }}','Create Country')">Create Country</button>
</div>
<table class="table-custom">
    <tr>
        <th>Country Name</th>
        <th>Country Code</th>
        <th>Short Name</th>
        <th>Flag</th>
        <th></th>
    </tr>
    @foreach ($countries as $country)
    <tr>
        <td>{{ $country->name }}</td>
        <td>{{ $country->code }}</td>
        <td>{{ $country->short_name }}</td>
        <td> <img src="{{ $country->flag }}" height="50" width="70"></td>
        <td>
            <button
            onclick="new Modal('md',600).load('{{ route('administration.settings.country.edit',['Id' => $country->id]) }}','Update Country')"
            class="btn btn-sm btn-primary" title="Edit Country"><i class="fa fa-pencil"></i></button>
            <button
            onclick="country.delete($(this))" data-url = "{{ route('administration.settings.country.delete',['Id' => $country->id]) }}" class="btn btn-sm btn-danger" title="Delete Country"><i class="fa fa-trash"></i></button>
        </td>
    </tr>
    @endforeach
</table>

@stop
