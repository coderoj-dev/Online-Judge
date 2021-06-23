<form action="{{ route('administration.settings.country.update',['Id' => $country->id]) }}" class="form" id="update_country" method="post">
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
            <input type="text" class='form-control-input' name="name" placeholder="Enter Country Code" value="{{ $country->name }}">
        </div>
    </div>
    <div class='row' style="margin-bottom: 5px;">
        <div class='col-md-4 label-area'>
            <label> Country Code<font color="red">*</font>:</label>
        </div>
        <div class="col-md-8">
            <input type="text" class='form-control-input' name="code" placeholder="Enter Country Code" value="{{ $country->code }}">
        </div>
    </div>
    <div class='row' style="margin-bottom: 5px;">
        <div class='col-md-4 label-area'>
            <label> Contry Short Name<font color="red">*</font>:</label>
        </div>
        <div class="col-md-8">
            <input type="text" class='form-control-input' name="short_name" placeholder="Enter Country Short Name" value="{{ $country->short_name }}">
        </div>
    </div>
    <div class='row' style="margin-bottom: 5px;">
        <div class='col-md-4 label-area'>
            <label> Contry Flag<font color="red">*</font>:</label>
        </div>
        <div class="col-md-8">
            <input type="text" class='form-control-input' name="flag" placeholder="Enter Country flag" value="{{ $country->flag }}">
        </div>
    </div>
    <div class='row'>
        <div class="col-md-4"></div>
        <div class="col-md-8">
            <div>
                <button type="submit" class="btn submit-btn" onclick="country.update()" style="margin-top: 15px;">Update Country</button>
            </div>
        </div>
    </div>
</form>
