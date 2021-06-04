var auth = {
    loginPage: function(e) {
        new Modal("custom", 500).load($(e).attr('url'), "Login", function(response) {});
    },
    login: function() {
        new Form("login").submit({
            loadingText: "Login...",
            success: {
                callback: function(response) {
                    new Modal().close();
                    url.load();
                    toast.success("Login Success");
                }
            }
        });
        //console.log(form.data(), form.action());
    },
    registerPage: function(e) {
        new Modal("custom", 500).load($(e).attr('url'), "Registration", function(response) {});
    },
    register: function() {
        var form = new Form("register");
        form.submit({
            loadingText: "Processing...",
            success: {
                resetForm: true,
            }
        });
    },
    logout: function(e) {
        var btn = new Button($(e).attr('id'));
        btn.off("<i class='fa fa-refresh fa-spin fa-1x fa-fw'></i> Processing...");
        $.post($(e).attr('url'), app.setToken(), function(response) {
            toast.success("logout Success");
            url.load();
        }).fail(function(error) {
            btn.on();
            toast.danger(error.status + " Error Found");
        });;
    }
};