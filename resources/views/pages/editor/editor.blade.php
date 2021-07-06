<style type="text/css">
   
.code-editor{
    line-height: 1.2;
    border: 2px solid #EEEEEE;
    border-radius: 3px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
          box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
    -webkit-transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
       -o-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
          transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
    
}

.ace_editor{
    padding-bottom: 2px;
}

.btn.-default {
    color: #2f353b;
    background-color: #e1e5ec;
    -webkit-box-shadow: inset 0 -2px 0 #c5cdda;
    box-shadow: inset 0 -2px 0 #c5cdda;
}


</style>
@php
    $languages = $problem->languageList();
    $userLanguage = auth()->user()->lastSubmissionLanguage();
    $userLanguageId = !empty($userLanguage) ? $userLanguage->id : -1;
@endphp
<script type="text/javascript">
    submissionEditor.setUpEditor("cpp");
</script>
<div class="row">
    <div class="col-md-2">
        <label>Languages</label>
    </div>
    <div class="col-md-10">
        <select style="width: 300px" class='form-control' id='submissionLanguage' onclick='submissionEditor.changeLanguage()'>
                    <option value="-1">Select Language</option>
            @foreach ($languages as $key => $language)
                @if(!$language->is_archive)
                    <option value="{{$language->id}}" {{$userLanguageId == $language->id ? "selected" : ""}}>{{$language->name}}</option>
                @endif
            @endforeach
        </select>
    </div>

    <div class="col-md-2">
        <div style="margin-top: 15px;"></div>
        <label>Source</label>
    </div>
    <div class="col-md-10">
        <div style="margin-top: 15px;"></div>
        <div id="sourceCodeEditor" class="code-editor" style="width: 650px;height: 80px"></div>
        <input type="file" name="file" id="sourceCode" onchange="submissionEditor.loadSourceCodeFile(event)"  style="display: none;" />
        <button style="margin-top: 5px; padding: 5px; background-color: #E1E5EC" class="btn -default" onclick="$('#sourceCode').trigger('click')" title="Open File"><i class="fa fa-upload"></i> Open</button>
    </div>
    <div class="col-md-10 col-md-offset-2">
        <div style="margin-top: 15px;"></div>
        <button style="width: 150px;" url="{{$submitUrl}}" class="btn btn-primary" id="btn-create-submission" onclick="submissionEditor.createSubmission(this)">Submit Solution</button>
    </div>
</div>



