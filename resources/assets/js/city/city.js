var city = {
    create: function () {
        var form = new Form("create_city");
        form.submit({
            loadingText: "creating...",
            success: {
                resetForm: true,
                callback: function(response) {
                    new Modal().close();
                    url.load();
                }
            }
        });
    },
    update: function () {
        var form = new Form("update_city");
        form.submit({
            loadingText: "updating...",
            success: {
                resetForm: true,
                callback: function(response) {
                    new Modal().close();
                    url.load();
                }
            }
        });
    },

    delete : function (el) {
    	var ok = confirm("Are you want to delete this city?");
    	if(!ok) return;
        var deleteUrl = el.attr('data-url');
        $.post(deleteUrl, app.setToken(), function (response) {
            url.load();
            toast.success(response.message);
        });
    }
    
};
