// start toast script

var toast = {
    success: function(msg) {
        this.createToast("success", msg)
    },
    danger: function(msg) {
        this.createToast("danger", msg)
    },
    info: function(msg) {
        this.createToast("info", msg)
    },
    warning: function(msg) {
        this.createToast("warning", msg)
    },
    createToast:function(toastType,toastMsg){
        var toastIconList = {};
        toastIconList['success'] = 'check-circle';
        toastIconList['danger'] = 'times-circle';
        toastIconList['warning'] = 'exclamation-circle';
        toastIconList['info'] = 'info-circle';
        var toastIcon = toastIconList[toastType];
        var dom = '<div class="toast"><div class="alert alert-' + toastType + '-alt alert-dismissable fade in " role="alert"><i class="fas fa-' + toastIcon + ' toast-icon"></i>' + toastMsg + '<button type="button" class="toast-close" data-dismiss="alert" aria-label="Close">×</button></div></div>';
        var jdom = $(dom);
        jdom.hide();
        $("body").append(jdom);
        jdom.fadeIn();
        setTimeout(function() {
            jdom.fadeOut(function() {
                jdom.remove();
            });
        }, 2000);
    }
};

var failError = {
    toast: function(error) {
        toast.danger(error.responseJSON.message);
    }
}


