import RestTable from './share/rest_table';
import URI from 'urijs';
import MicroModel from 'micromodal';

let rt = new RestTable({
    el: "#rt-table",
    url: $("#rt-table").data("api-url"),
    onRowClick: function(evt, obj) {
        location.href = location.href + "/" + obj.id;
    }
});

rt.ajax().then(function(ajaxObj, rt){
});

$("#rt-add-btn").click(function(){

});
