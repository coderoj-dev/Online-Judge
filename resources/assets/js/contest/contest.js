var Contest = {
	create: function() {
        var form = new Form("create_contest");
        form.submit({
            loadingText: "Creating...",
            success: {
                resetForm: true,
                callback: function(response) {
                    url.load(response.url);
                    new Modal().close();
                }
            }
        });
    },
   
    getModetatorsList: function(el) {
        $('#suggestion_box').html("");
        var geturl = el.attr('data-url');
        var addUrl = el.attr('data-add-url');

        var data = {};
        data['search'] = el.val();
        if (data['search'] == "") {
            return;
        }
        
        $.post(geturl, app.setToken(data), function(response) {
            console.log(response);
            var moderatorsList = JSON.parse(response);

            $('#suggestion_box').html("");
            $.each(moderatorsList, function() {
                $('#suggestion_box').append("<li class='list-group-item moderators_suggestion_li' onclick='Contest.addContestModerator($(this))' data-userId='" + this.id + "' data-url='" + addUrl + "'>" + "<img class='img-thumbnail moderators_suggestion_li_img' src='" + this.avatar + "' style='width: 50px;'><b> " + this.handle + "</b></li>");
            });
        });
    },
    addContestModerator: function(el) {
        var userId = el.attr('data-userId');
        var addurl = el.attr('data-url');
        var data = {
            'userId': userId,
        }
        $.post(addurl, app.setToken(data), function(response) {
            url.load();
            toast.success("Successfully Add Moderator");
        });
    },
    deleteContestModerator: function(el) {
        var ok = confirm("Are you want to delete moderator?");
        if (ok) {
            var delUrl = el.attr('data-url');
            var userId = el.attr('data-userId');
            var data = {
                'userId': userId,
            }
            $.post(delUrl, app.setToken(data), function(response) {
                url.load();
                toast.success("Successfully Removed Moderator");
            });
        }
    },
    cancelContestModerator: function(el) {
        var delUrl = el.attr('data-url');
        var data = {}
        $.post(delUrl, app.setToken(data), function(response) {
            url.load(response.url);
            toast.success(response.message);
        });
    },
    leaveFromModerator: function(el) {
        var ok = confirm("Are you want to Leave From moderator?");
        if (ok) {
            var delUrl = el.attr('data-url');
            var data = {};
            $.post(delUrl, app.setToken(data), function(response) {
                url.load(response.url);
                toast.success(response.message);
            });
        }
    },
    acceptContestModerator: function(el) {
        var acceptUrl = el.attr('data-url');
        var userId = el.attr('data-userId');
        console.log(userId);
        var data = {
            'userId': userId
        };
        $.post(acceptUrl, app.setToken(data), function(response) {
            url.load();
            toast.success("Your are now moderator");
        });
    },
    requestForModerator: function(e) {
        var ok = confirm("Are you want to send moderator request?");
        if (!ok) return;
        var data = {
            'message': $("#moderator_message").val()
        };
        new Button("sendReqBtn").off("Sending...");
        $.post(e.attr('data-url'), app.setToken(data), function(response) {
            url.load();
            toast.success("Your Request Sent To Admin");
        });
    },

   
};

