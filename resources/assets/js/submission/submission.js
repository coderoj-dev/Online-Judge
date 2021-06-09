var submission = {
    loadSubmissionPageId: -1,
    submissionPageSocketStart: 0,
    submissionPageSocketBusy: 0,
    openToggleSourceCode: false,
    setSubmissionPage: function(submissionId) {
        this.loadSubmissionPageId = submissionId;
        this.submissionPageSocketStart = 1;
        this.openToggleSourceCode = false;
    },
    loadSubmissionData: function() {
        if (this.submissionPageSocketStart == 0) return;
        this.submissionPageSocketBusy = 1;
        submissionId = this.loadSubmissionPageId;
        $.get('/api/submission_verdict/' + submissionId, function(response) {
            $("#submission_time_" + response.id).html(response.time + " ms");
            $("#submission_memory_" + response.id).html(response.memory + " kb");
            $("#submission_verdict_" + response.id).html(response.verdict_status);
            $.each(response.test_cases, function(key, value) {
                $("#submission_test_case_verdict_" + value.id).html(value.verdict_status);
                $("#submission_test_case_time_" + value.id).html(value.time + " ms");
                $("#submission_test_case_memory_" + value.id).html(value.memory + " kb");
                $("#submission_test_case_point_" + value.id).html(value.passed_point);
                $("#submission_test_case_input_" + value.id).html(value.input);
                $("#submission_test_case_output_" + value.id).html(value.output);
                $("#submission_test_case_expected_output_" + value.id).html(value.expected_output);
                $("#submission_test_case_checker_log_" + value.id).html(value.checker_log);
                $("#submission_test_case_compiler_log_" + value.id).html(value.compiler_log);
                if (value.verdict_id <= 2) return false;
                if (value.verdict_id == 6 || value.verdict_id == 7) {
                    $("#submission_test_case_checker_log_area_" + value.id).hide();
                } else {
                    $("#submission_test_case_compiler_log_area_" + value.id).hide();
                }
                if (value.verdict_id >= 3 && value.verdict_id != 16) {
                    $("#view_test_case_btn_area_" + value.id).show();
                }
            });
            submissionPageSocketBusy = 0;
            if (response.verdict_id >= 3) submission.submissionPageSocketStart = 0;
        }).fail(function(error) {
            submissionPageSocketBusy = 0;
        });
    },
    viewTestCaseDetail: function(id) {
        var divId = "#submission_test_case_detail_" + id;
        if ($(divId).css('display') == 'none') {
            $(divId).show(500);
            $("#view_test_case_btn_" + id).removeClass('fa fa-angle-double-down');
            $("#view_test_case_btn_" + id).addClass('fa fa-angle-double-up');
        } else {
            $(divId).hide(300);
            $("#view_test_case_btn_" + id).removeClass('fa fa-angle-double-up');
            $("#view_test_case_btn_" + id).addClass('fa fa-angle-double-down');
        }
    },
    copySourceCode: function() {
        this.openToggleSourceCode = false;
        this.toggleSourceCode();
        var copyText = document.getElementById("sourceCodeTextArea");
        copyText.select();
        copyText.setSelectionRange(0, 99999)
        document.execCommand("copy");
        toast.info("Copied Source Code");
    },
    toggleSourceCode: function() {
        var openToggleDiv = this.openToggleSourceCode ? "sourceCodeText" : "sourceCodeTextArea";
        var closeToggleDiv = this.openToggleSourceCode ? "sourceCodeTextArea" : "sourceCodeText";
        $("#" + openToggleDiv).show();
        $("#" + closeToggleDiv).hide();
        this.openToggleSourceCode ^= 1;
    }
};
setInterval(function() {
    submission.loadSubmissionData();
}, 2000);


