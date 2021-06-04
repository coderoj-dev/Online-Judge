var profile = {
    changePassword: function() {
        new Form("change_password").submit({
            loadingText: "Changing Password...",
            success: {
                resetForm: true,
            }
        });
        //console.log(form.data(), form.action());
    },
    changeName: function() {
        new Form("change_name").submit({
            loadingText: "Changing Name...",
            success: {
                resetForm: false,
            }
        });
        //console.log(form.data(), form.action());
    },
    loadFileAvatar: function(event) {
        var output = document.getElementById('img-preview');
        if (!event.target.files[0]) {
            output.src = $('#img-preview-default').attr('src');
        } else output.src = URL.createObjectURL(event.target.files[0]);
        output.onload = function() {
            URL.revokeObjectURL(output.src) // free memory
        }
    },
    changeAvatar: function() {
        new Form("change_avatar").submit({
            loadingText: "Changing avatar...",
            success: {
                resetForm: true,
            }
        });
        //console.log(form.data(), form.action());
    },
};