var Form = function (form, options) {
    var _this = this;
    this.form = (form instanceof jQuery) ? form : $("#" + form);
    this.options = options;
    this.submitButton = new Button($("button[type=submit]", this.form));
    this.id = this.form.attr("id");
    this.errorArea = $("#" + this.id + " .error-area");
    this.successArea = $("#" + this.id + " .success-area");
    this.data = function () {
        return new FormData(document.getElementById(this.id));
    };
    this.action = function() {
        return this.form.attr('action');
    };
    this.submit = function(options) {
        this.submitButton.off("<i class='fa fa-refresh fa-spin fa-1x fa-fw'></i>" + options.loadingText);
        if (options.success == null) {
            options.success = {}
        }
        $.ajax({
            url: this.action(),
            data: this.data(),
            processData: false,
            contentType: false,
            type: 'POST',
            success: function (response) {
                _this.errorArea.hide();
                _this.successArea.show();
                _this.successArea.html("<strong>" + response.message + "</strong>");
                toast.success(response['message']);
                //clear form
                if (options.success.resetForm == true) _this.form.trigger("reset");
                //call callback function
                if ($.isFunction(options.success.callback)) options.success.callback(response);
                _this.submitButton.on();
            }
        }).fail(function (Error) {
            var error = JSON.parse(Error.responseText);
            _this.successArea.hide();
            _this.errorArea.show();
            _this.errorArea.html("<strong>" + error['message'] + "</strong>");
            toast.danger(error['message']);
            errorMsg = "";
            $.map(error['errors'], function (errors, field) {
                console.log(errors[0], field);
                errorMsg += "<li>" + errors[0] + "</li>";
            });
            _this.errorArea.append("<ul>" + errorMsg + "</ul>");
            _this.submitButton.on();
        });


    }
}
