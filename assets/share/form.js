$(document).ready(function(){
    $("[data-collection-add]").click(function(){
        let ele = $(this).data("collection-add");
        let prototype = $(ele).find(".collection-prototype")[0];
        let container = $(ele).find(".collection-container")[0];
        let clone = $(prototype).clone();
        $(container).append(clone);
        clone.show();
    });

    $("[data-collection-delete]").click(function(){
        let ele = $(this).data("collection-delete");
        $(ele).remove();
    });
});