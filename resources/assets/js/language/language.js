var language = {
    create: function () {
        var form = new Form("create_language");
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
        var form = new Form("edit_language");
        form.submit({
            loadingText: "Updating...",
            success: {
                resetForm: true,
                callback: function(response) {
                    new Modal().close();
                    url.load();
                }
            }
        });
    },
    toggleArchive: function (e) {
        var archiveStatus = e.attr("data-archive-status");
        $msg = "";
        if (archiveStatus == 1) {
            $msg = "Are you want to restore form archive this language?";
        } else {
            $msg = "Are you want to archive this language?";
        }
        var ok = confirm($msg);
        if (!ok) return;
        $.get(e.attr('url'), function (response) {
            toast.success(response.message);
            url.load();
        });
    },

    loadArchived : function (e) {
        var location = e.attr("location");
        if(e.prop("checked") == true)
        {
            location = location + "?archived=1";
        }
        // alert(location);
        url.load(location);
    }
};
