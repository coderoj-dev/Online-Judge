function formPrevent() {
    $('form').on('submit', function(e) {
        e.preventDefault();
    });
}

function Div(div) {
    var self = this;
    this.div = (div instanceof jQuery) ? div : $("#" + div);
    this.defaultLoad = function() {
        formPrevent();
    };
    this.getScrapeArea = function(html,id){
        var doc = new DOMParser().parseFromString(html, "text/html").getElementById(id);
        var html = doc != null ? doc.innerHTML : html;
        return html;
    }
    this.load = function(data, callback) {
        if ($.isPlainObject(data)) {
            this.url = data.url;
            this.loader = data.loader;
            this.changeUrl = data.changeUrl;
            this.data = data.data;
            this.scrapeArea = data.scrapeArea;
        }
        this.url = !this.url ? "" : this.url;
        this.loader = !this.loader ? "div" : this.loader;
        this.changeUrl = !this.changeUrl ? false : this.changeUrl;
        
        if (this.loader == "div") loader.div(this.div);
        else loader.top.start();

        if(this.changeUrl){
            url.change("","",this.url);
        }
        var html = $("#app-body").html();

        var requestData = $.isPlainObject(this.data) ? this.data : {};

        $.get(this.url, requestData , function(response) {
            if(this.changeUrl)document.title = $(response).filter('title').text();
            
            var html = self.scrapeArea != null ? self.getScrapeArea(response,self.scrapeArea) : response;
            self.div.html(html);

            self.defaultLoad();
            if ($.isFunction(callback)) callback(response);
        }).fail(function(error) {
            if (error.status == 500) {
                alert("Error Found\n");
                location.reload();
            } else {
                var html = self.scrapeArea != null ? self.getScrapeArea(error.responseText,self.scrapeArea) : response;
                self.div.html(html);
                toast.danger(error.status + " Error Found");
            }
        });
    }
}
