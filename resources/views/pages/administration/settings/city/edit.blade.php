<form action="{{ route('administration.settings.city.update',['Id' => $city->id]) }}" class="form" id="update_city" method="post">
    @csrf
    <div class="alert-area">
        <div class="alert alert-danger error-area">ok</div>
        <div class="alert alert-success success-area"></div>
    </div>
    <div class='row' style="margin-bottom: 5px;">
        <div class='col-md-4 label-area'>
            <label> Country Name<font color="red">*</font>:</label>
        </div>
        <div class="col-md-8">
         <select class="form-control" id="extracurricular" name="country_id">
           @foreach($countries as $country)
           <option value="{{ $country->id }}" {{$country->id==$city->country->id?"selected":""}}>{{ $country->name }}</option>
           @endforeach
       </select>
   </div>
</div>
<div class='row' style="margin-bottom: 5px;">
    <div class='col-md-4 label-area'>
        <label> City Name<font color="red">*</font>:</label>
    </div>
    <div class="col-md-8">
        <input type="text" class='form-control-input' name="name" placeholder="Enter City Name" value="{{ $city->name }}">
    </div>
</div>
<div class='row' style="margin-bottom: 5px;">
    <div class='col-md-4 label-area'>
        <label> Time Zone<font color="red">*</font>:</label>
    </div>
    <div class="col-md-8">
        <input type="text" class='form-control-input' name="time_zone" placeholder="Enter Time Zone" value="{{ $city->time_zone }}">
    </div>
</div>
<div class='row'>
    <div class="col-md-4"></div>
    <div class="col-md-8">
        <div>
            <button type="submit" class="btn submit-btn" onclick="city.update()" style="margin-top: 15px;">Update City</button>
        </div>
    </div>
</div>
</form>
