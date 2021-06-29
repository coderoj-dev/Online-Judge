var Contest = {
    create: function () {
        var form = new Form("create_contest");
        form.submit({
            loadingText: "Creating...",
            success: {
                resetForm: true,
                callback: function (response) {
                    url.load(response.url);
                    new Modal().close();
                }
            }
        });
    },
    update: function () {
        var form = new Form("updateContestForm");
        form.submit({
            loadingText: "Updating...",
            success: {
                resetForm: false,
                callback: function (response) {
                    // url.load();
                    // new Modal().close();
                }
            }
        });
    },
    loadFileBanner: function (event) {
        var output = document.getElementById('contestBannerPreview');
        if (!event.target.files[0]) {
            // output.src = $('#img-preview-default').attr('src');
        } else output.src = URL.createObjectURL(event.target.files[0]);

        output.onload = function () {
            URL.revokeObjectURL(output.src) // free memory
        }
    },
    addProblem: function (e) {
        var problemSlug = prompt("Enter problem Slug");
        if (problemSlug == null) return;
        var addUrl = e.attr("url");
        var data = {
            'slug': problemSlug
        };
        $.post(addUrl, app.setToken(data), function (response) {
            url.load();
            toast.success(response.message);
        }).fail(function (error) {
            toast.danger("Problem Added Error");
        });
        // console.log(problemSlug);
    },
    removeProblem: function (e) {
        var ok = confirm("Are you want to remove problem");
        if (!ok) return;
        var removeUrl = e.attr("url");
        $.post(removeUrl, app.setToken(), function (response) {
            url.load();
            toast.success(response.message);
        });
        // console.log(problemSlug);
    },
    getModetatorsList: function (el) {
        $('#suggestion_box').html("");
        var geturl = el.attr('data-url');
        var addUrl = el.attr('data-add-url');
        var data = {};
        data['search'] = el.val();
        if (data['search'] == "") {
            return;
        }
        $.post(geturl, app.setToken(data), function (response) {
            console.log(response);
            var moderatorsList = response.moderators;
            $('#suggestion_box').html("");
            $.each(moderatorsList, function () {
                $('#suggestion_box').append("<li class='list-group-item moderators_suggestion_li' onclick='Contest.addContestModerator($(this))' data-userId='" + this.id + "' data-url='" + addUrl + "'>" + "<img class='img-thumbnail moderators_suggestion_li_img' src='" + this.avatar + "' style='width: 50px;'><b> " + this.handle + "</b></li>");
            });
        });
    },
    addContestModerator: function (el) {
        var userId = el.attr('data-userId');
        var addurl = el.attr('data-url');
        var data = {
            'userId': userId,
        }
        $.post(addurl, app.setToken(data), function (response) {
            url.load();
            toast.success(response.message);
        });
    },
    deleteContestModerator: function (el) {
        var ok = confirm("Are you want to delete moderator?");
        if (ok) {
            var delUrl = el.attr('data-url');
            var userId = el.attr('data-userId');
            var data = {
                'userId': userId,
            }
            $.post(delUrl, app.setToken(data), function (response) {
                url.load();
                toast.success(response.message);
            });
        }
    },
    acceptProblemModerator: function (el) {
        var acceptUrl = el.attr('data-url');
        var userId = el.attr('data-userId');
        console.log(userId);
        var data = {
            'userId': userId
        };
        $.post(acceptUrl, app.setToken(data), function (response) {
            url.load();
            toast.success(response.message);
        });
    },
    cancelProblemModerator: function (el) {
        var delUrl = el.attr('data-url');
        var data = {}
        $.post(delUrl, app.setToken(data), function (response) {
            url.load(response.url);
            toast.success(response.message);
        });
    },
    leaveFromModerator: function (el) {
        var ok = confirm("Are you want to Leave From moderator?");
        if (ok) {
            var delUrl = el.attr('data-url');
            var data = {};
            $.post(delUrl, app.setToken(data), function (response) {
                url.load(response.url);
                toast.success(response.message);
            });
        }
    },
};
