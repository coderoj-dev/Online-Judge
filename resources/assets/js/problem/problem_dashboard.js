var problem = {
    checkerEditor: "",
    create: function () {
        var form = new Form("create_problem");
        form.submit({
            loadingText: "creating...",
            success: {
                resetForm: true,
                callback: function () {
                    alert("ok");
                }
            }
        });
    },
    editor: function (problemData) {
        console.log(problemData);
        problemDetailsEditor.setEditor(problemData);
    },
    detailsUpadte: function (actionUrl) {
        var data = problemDetailsEditor.getEditorData();
        data['name'] = $("#problem_name").val();
        var btn = new Button("update-problem-details");
        btn.off("Updating....");
        $.post(actionUrl, app.setToken(data), function (response) {
            toast.success("Updated Details");
            btn.on();
        });
    },
    preview: function (e) {
        new Modal("custom", 750).load($(e).attr('url'), "Preview Problem", function (response) {});
    },
    copyTestCase: function (e) {
        copyer(e.value);
        toast.info("The example has been copied into the clipboard");
    },
    setCheckerCustomEditor: function (code) {
        this.checkerEditor = ace.edit("checkerEditor");
        this.checkerEditor.setOption("maxLines", 30);
        this.checkerEditor.setOption("minLines", 30);
        this.checkerEditor.setReadOnly(false);
        this.checkerEditor.setValue(atob(code), -1);
        this.checkerEditor.getSession().setMode("ace/mode/c_cpp");
    },
    updateCustomChecker: function (e) {
        code = this.checkerEditor.getValue()
        if (code == "") {
            alert("Checker can not be empty");
            return;
        }
        var data = {
            checker_type: 'custom',
            custom_checker: code
        };
        var btn = new Button("custom_checker_btn");
        btn.off("Saving....");
        $.post($(e).attr('url'), app.setToken(data), function (response) {
            toast.success("Save success custom checker");
            btn.on();
        });
    },
    updateDefaultChecker: function (e) {
        checker = $("#default_checker").val();
        if (checker == "") {
            alert("Checker can not be empty");
            return;
        }
        var data = {
            checker_type: 'default',
            default_checker: checker
        };
        var btn = new Button("default_checker_btn");
        btn.off("Saving....");
        $.post($(e).attr('url'), app.setToken(data), function (response) {
            toast.success("Save success default checker");
            btn.on();
        });
    },
    selectDefaultChecker: function () {
        $("#custom_checker_area").hide();
        $("#default_checker_area").show();
    },
    selectCustomChecker: function () {
        $("#default_checker_area").hide();
        $("#custom_checker_area").show();
    },
    addLanguages: function () {
        new Form("add_languages").submit({
            loadingText: "Add Languages",
            success: {
                callback: function (response) {
                    new Modal().close();
                    url.load();
                }
            }
        });
    },
    updateLanguages: function () {
        new Form("edit_problem_language").submit({
            loadingText: "Updating Languages",
            success: {
                callback: function (response) {
                    new Modal().close();
                    url.load();
                }
            }
        });
    },
    getModetatorsList: function (el) {
        $('#suggestion_box').html("");
        var geturl = el.attr('data-url');
        var addUrl = el.attr('data-add-url');
        var data = {};
        data['search'] = el.val();
        if(data['search'] == "")
        {
            return;
        }
        $.post(geturl, app.setToken(data), function (response) {
            console.log(response);
            var moderatorsList = JSON.parse(response);    
            $('#suggestion_box').html("");        
            $.each(moderatorsList, function () {
                $('#suggestion_box').append(
                    "<li class='list-group-item moderators_suggestion_li' onclick='problem.addProblemModerator($(this))' data-userId='"+ this.id +"' data-url='"+addUrl+"'>" +
                    "<img class='img-thumbnail moderators_suggestion_li_img' src='" + this.avatar + "' style='width: 50px;'><b> " +
                    this.handle + "</b></li>"
                );
            });
        });

    },
    addProblemModerator : function(el){
        var userId = el.attr('data-userId');
        var addurl = el.attr('data-url');
        var data = {
            'userId': userId,
        }
        $.post(addurl, app.setToken(data), function (response) {
            url.load();
            toast.success("Successfully Add Moderator");
        });
    },
    deleteProblemModerator : function(el) {
        var ok = confirm("Are you want to delete moderator?");
        if(ok)
        {
            var delUrl = el.attr('data-url');
            var userId = el.attr('data-userId');
            var data = {
                'userId': userId,
            }
            $.post(delUrl, app.setToken(data), function (response) {
                url.load();
                toast.success("Successfully Removed Moderator");
            });
        }
        
    },
    cancelProblemModerator : function(el) {
        var delUrl = el.attr('data-url');
        var data = {}
        $.post(delUrl, app.setToken(data), function (response) {
            url.load();
            toast.success("Successfully Removed Moderator");
        });
    },
    acceptProblemModerator : function (el) {
        var acceptUrl = el.attr('data-url');
        var userId = el.attr('data-userId');
        console.log(userId);
        var data = {
            'userId' : userId
        };
        $.post(acceptUrl, app.setToken(data), function (response) {
            url.load();
            toast.success("Your are now moderator");
        });
    }
};
var testCase = {
    selectInputType: function (e) {
        var type = e.value;
        $("#testCaseInputEditorArea").hide();
        $("#testCaseInputUploadArea").hide();
        if (type == "editor") $("#testCaseInputEditorArea").show();
        if (type == "upload") $("#testCaseInputUploadArea").show();
    },
    selectOutputType: function (e) {
        var type = e.value;
        $("#testCaseOutputEditorArea").hide();
        $("#testCaseOutputUploadArea").hide();
        if (type == "editor") $("#testCaseOutputEditorArea").show();
        if (type == "upload") $("#testCaseOutputUploadArea").show();
    },
    setInputEditorLimit: function (e) {
        var editorVal = $('#testCaseInput').val();
        var maxLen = 5000;
        var txtLen = editorVal.length;
        if (txtLen >= maxLen) {
            alert("Input File Is To Large. If You Need Large Input You Try Upload Option.");
        }
    },
    setOutputEditorLimit: function (e) {
        var editorVal = $('#testCaseOutput').val();
        var maxLen = 5000;
        var txtLen = editorVal.length;
        if (txtLen >= maxLen) {
            alert("Output File Is To Large. If You Need Large Output You Try Upload Option.");
        }
    },
    updateSample: function (e) {
        var sample = $(e).prop("checked") == true ? 1 : 0;
        var data = {
            'sample': sample
        };
        $.get($(e).attr('name'), data, function (response) {
            toast.success(response.message);
        });
    },
    addTestCase: function () {
        new Form("add_test_case").submit({
            loadingText: "Saving...",
            success: {
                callback: function (response) {
                    new Modal().close();
                    url.load();
                }
            }
        });
    },
    updateTestCase: function () {
        new Form("update_test_case").submit({
            loadingText: "Saving...",
            success: {
                callback: function (response) {
                    new Modal().close();
                    url.load();
                }
            }
        });
    },
    delete: function (e) {
        var ok = confirm("Are you want to delete this test case");
        if (!ok) return;
        $.get($(e).attr('url'), function (response) {
            toast.success(response.message);
            url.load();
        });
    },

}
