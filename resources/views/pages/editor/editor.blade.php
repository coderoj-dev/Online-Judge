<style type="text/css">
    .editorArea{
        background-color: #ffffff;
        border: 1px solid #D1D9E5;
        border-radius: 5px;
    }
    .editorAreaTop{
        padding: 10px;
    }
    .editorAreaBottom{
        padding: 10px;
    }
    .editor{
        border: 1px solid #D1D9E5;
        border-width: 1px 0px 1px 0px;
    }
    .editorError{
        color: #A94442;
        font-weight: bold;
    }
    .editorLanguageSelect{
        padding: 5px;
    }
    .editorIcon{
        font-size: 15px;
        margin-right: 10px;
        cursor: pointer;
        background-color: #7f8c8d;
        color: #ffffff;
        padding: 8px;
        text-align: center;
        border-radius: 100%;
        width: 30px;
        height: 30px;
        font-weight: bold;
    }
    .editorIcon:hover{
        background-color: #2C3542;
        color: #ecf0f1;
    }
    .editorBtnArea{
        text-align: center;
    }
</style>


<div class="editorArea">
    <div class="editorAreaTop">
        <div class="row">
            <div class="col-md-3">
                <div class="editorBtnArea">
                    <i class="fa fa-copy editorIcon" title="Copy"></i>
                    <input type="file" name="file" id="sourceCode" onchange="submissionEditor.loadSourceCodeFile(event)"  style="display: none;" />
                    <i class="far fa-folder-open editorIcon" id="openFile" onclick="$('#sourceCode').trigger('click')" title="Open File"></i>
                    <i class="fas fa-file-download editorIcon" onclick="submissionEditor.download(submissionEditor.checkerEditor.getValue(), submissionEditor.fileNames[selectLanguage], 'text/plain')" title="Download"></i>
                </div>
            </div>
            <div class="col-md-5"></div>
            <div class="col-md-4">
                 <select class='form-control editorLanguageSelect' id='submissionLanguage' onclick='submissionEditor.changeLanguage()'>
                    <option value="-1">Select Language</option>
                @foreach ($languages as $key => $language)
                    @if(!$language->is_archive)
                    <option value="{{$language->id}}">{{$language->name}}</option>
                    @endif
                @endforeach
                </select>
            </div>
        </div>
    </div>
    <div id="sourceCodeEditor" class="editor"></div>
    <div class="editorAreaBottom">
       <div class="row">
           <div class="col-md-8">
           </div>
           <div class="col-md-4">
                <div class="pull-right">
                    <button style="width: 150px;" url="{{$submitUrl}}" class="btn btn-default" id="btn-create-submission" onclick="submissionEditor.createSubmission(this)">Submit Solution</button>
                </div>
           </div>
       </div>
    </div>
</div>


<form action="{{$submitUrl}}" method="post">
    @csrf
    <select class='form-control editorLanguageSelect' name="language_id" onclick='submissionEditor.changeLanguage()'>
                    
                @foreach ($languages as $key => $language)
                    
                    <option value="{{$language->id}}">{{$language->name}}</option>
                    
                @endforeach
                </select>
    <br/>
    <textarea row="15" name="source_code"></textarea><br/>
    <button type="submit">Submit</button>
</form>

<script type="text/javascript">
    submissionEditor.setUpEditor("cpp");
</script>
