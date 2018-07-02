import RestTable from './share/rest_table';
import URI from 'urijs';

window.rt = new RestTable({
    el: "#rest-table",
    url: $("#rest-table").data("api-url"),
    onRowClick: function(evt, obj) {
        location.href = location.href + "/" + obj.id;
    }
});

rt.getAjax().then(function(ajaxObj, rt){
});
