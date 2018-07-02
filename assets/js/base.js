global.$ = global.jQuery = require('jquery');

$(document).ready(function(){
    // Toggle navbar menu via selector specified in data-submenu attr
    $("#top-navbar *[data-submenu]").click(function() {
        let target = $($(this).data("submenu"));
        let leftOffset = $(this).offset().left - target.outerWidth() + $(this).outerWidth();
        target.css("left", leftOffset);
        target.show();
    });
    // Hide menu when clicking outside of data-submenu
    $(document).click(function(evt){
        if (!$(evt.target).is(".submenu") && !$(evt.target).is("#top-navbar *[data-submenu]") && $(".submenu").has(evt.target).length == 0) {
            $(".submenu").hide();
        }
    });
});