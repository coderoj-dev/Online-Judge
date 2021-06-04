function Modal(type, width) {
    var modalDefaultWidth = {};
    modalDefaultWidth['sm'] = 350;
    modalDefaultWidth['md'] = 600;
    modalDefaultWidth['lg'] = 950;
    this.id = $("#modal");
    this.modal = $("#modal");
    this.body = $("#modal-body");
    this.headerTitle = $("#modal-header-title");
    this.width = parseInt(modalDefaultWidth[type]) ? parseInt(modalDefaultWidth[type]) : parseInt(modalDefaultWidth["md"]);
    this.width = !parseInt(width) ? this.width : parseInt(width);
    this.setUp = function(title) {
        this.headerTitle.html(title);
        $('.modal-dialog').css('max-width', this.width);
        loader.div(this.body);
    }
    this.open = function(title) {
        this.setUp(title);
        //modal is already open then not open again modal
        if(!this.modal.hasClass('in')){
            this.modal.modal("show");
        }
    }
    this.html = function(html) {
        this.body.html(html);
    }
    this.close = function() {
        this.modal.modal("hide");
    }
    this.load = function(url, title, callback) {
        this.open(title);
        new Div(this.body).load({
            url: url,
            data: {
                'modal' : true
            }
        }, function(response) {
            if ($.isFunction(callback)) callback(response);
            formPrevent();
        });
    }
}
$(document).on('hide.bs.modal', '#modal', function(response) {
    setTimeout(function() {
        new Modal().html("");
    }, 300);
});