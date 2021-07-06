var submissionEditor = {
    submissionEditor: "",
    setUpEditor: function(language) {
        this.submissionEditor = ace.edit("sourceCodeEditor");
        this.submissionEditor.setOption("maxLines", 22);
        this.submissionEditor.setOption("minLines", 22);
        this.submissionEditor.setOption("fontSize", 14);
        this.submissionEditor.setShowPrintMargin(false);

        this.submissionEditor.setReadOnly(false);
        this.submissionEditor.getSession().setMode("ace/mode/c_cpp");
    },
    changeLanguage: function() {
        var language = $("#submissionLanguage option:selected").text();
        language = language.toLowerCase();
        var mode = "c_cpp";
        if (language == "java") mode = "java";
        if (language.startsWith("python")) mode = "python";
        mode = "ace/mode/" + mode;
        this.submissionEditor.getSession().setMode(mode);
    },
    loadSourceCodeFile: function(event) {
        var reader = new FileReader();
        reader.onload = function() {
            $.get(reader.result, function(data) {
                submissionEditor.submissionEditor.setValue(data);
                submissionEditor.submissionEditor.clearSelection();
            }, "text");
        };
        if (event.target.files[0]) {
            reader.readAsDataURL(event.target.files[0]);
        }
    },
    createSubmission: function(e) {
        var sourceCode = this.submissionEditor.getValue();
        if (sourceCode.length >= 20000) {
            toast.danger("Source Code Length Is Very Large " + sourceCode.length);
            return;
        }
        if (sourceCode.length == 0) {
            toast.danger("Source code can not be empty");
            return;
        }
        var language = $("#submissionLanguage").val();
        if (language == -1) {
            toast.danger("You can not select language");
            return;
        }
        var btn = new Button("btn-create-submission");
        btn.off("Processing");
        var data = {
            'language_id': language,
            'source_code': btoa(sourceCode),
        };
        $.post($(e).attr('url'), app.setToken(data), function(response) {
            new Modal('lg').load(response.view_submission_url, "Submission #" + response.submission_id);
            url.load();
        }).fail(function(error) {
            btn.on();
            var error = JSON.parse(error.responseText);
            toast.danger(error.response);
        });
    },
}