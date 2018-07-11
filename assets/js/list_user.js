import 'datatables.net/js/jquery.dataTables'
import 'datatables.net-bs4';
import 'datatables.net-buttons-bs4/js/buttons.bootstrap4';
import 'datatables.net-select-bs4/js/select.bootstrap4';
import 'datatables.net-responsive-bs4/js/responsive.bootstrap4';
import axios from 'axios';
import * as URI from 'urijs';
import "urijs/src/URITemplate";

$(document).ready(function(){
    window.table = $("#user-table").DataTable({
        "select": "row",
        "responsive": true,
        "dom":  "<'row user-table_btn_grp'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        "buttons": [
            {
                text: "Create",
                className: "create-btn",
                action: function() {
                    console.log(Param);
                    location.href = Param.addPath;
                }
            },
            {
                text: "Delete",
                className: "delete-btn",
                // https://datatables.net/extensions/buttons/custom
                action: function(e, dt, node, config) {
                    axios.delete(Param.deletePath, {
                        data: dt.rows(".selected").ids().toArray()
                    }).then((ajax) => {
                        location.reload();
                    }).catch((ajax) => {
                        console.log(ajax);
                        alert("Deletion failed")
                    })
                }
            },
            {
                text: "Edit",
                className: "edit-btn",
                action: function(e, dt, node, config) {
                    console.log(URI);
                    let id = dt.rows(".selected").ids().toArray()[0];
                    let url = URI.expand(Param.editPath, {
                        id: id
                    });
                    location.href = url;
                }
            },
        ],

    });
    table.buttons(".delete-btn").disable();
    table.buttons(".edit-btn").disable();
    // https://datatables.net/reference/api/select.info()
    table.on('select', function(e, dt, type, indexes) {
        if (table.rows(".selected")[0].length === 0) {
            table.buttons(".delete-btn").disable();
            table.buttons(".edit-btn").disable();
        } else if (table.rows(".selected")[0].length === 1) {
            table.buttons(".delete-btn").enable();
            table.buttons(".edit-btn").enable();
        } else {
            table.buttons(".delete-btn").enable();
            table.buttons(".edit-btn").disable();
        }
    });
    table.on('deselect', function(e, dt, type, indexes) {
        if (table.rows(".selected")[0].length === 0) {
            table.buttons(".delete-btn").disable();
            table.buttons(".edit-btn").disable();
        } else if (table.rows(".selected")[0].length === 1) {
            table.buttons(".delete-btn").enable();
            table.buttons(".edit-btn").enable();
        } else {
            table.buttons(".delete-btn").enable();
            table.buttons(".edit-btn").disable();
        }
    });
});

