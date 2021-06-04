function Button(button) {
    this.button = (button instanceof jQuery) ? button : $("#" + button);
    this.offPreHtml = this.button.html();
    this.html = function(txt) {
        if (txt) this.button.html(txt);
        return this.button.html();
    }
    this.on = function(txt) {
        this.html(!txt ? this.offPreHtml : txt);
        this.button.prop("disabled", false);
    };
    this.off = function(txt) {
        this.offPreHtml = this.html();
        this.html(!txt ? "" : txt);
        this.button.prop("disabled", true);
    }
}
