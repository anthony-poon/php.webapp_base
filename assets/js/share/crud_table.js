import 'datatables.net/js/jquery.dataTables'
import 'datatables.net-bs4';
import 'datatables.net-buttons-bs4/js/buttons.bootstrap4';
import 'datatables.net-select-bs4/js/select.bootstrap4';
import 'datatables.net-responsive-bs4/js/responsive.bootstrap4';

export default class CRUDTable {
    constructor(options) {
        this.element = options.element;
        this.createAction = options.createAction;
        this.readAction = options.readAction;
        this.updateAction = options.updateAction;
        this.deleteAction = options.deleteAction;
        this.btn = [];
        if (this.createAction) {
            this.btn.push({
                text: "Create",
                className: "create-btn",
                action: this.createAction
            })
        }
        if (this.readAction) {
            this.btn.push({
                text: "View",
                className: "read-btn",
                action: this.readAction
            })
        }
        if (this.updateAction) {
            this.btn.push({
                text: "Edit",
                className: "update-btn",
                action: this.updateAction
            })
        }
        if (this.deleteAction) {
            this.btn.push({
                text: "Delete",
                className: "delete-btn",
                action: this.deleteAction
            })
        }
    }

    init() {
        this.table = $(this.element).DataTable({
            "select": "row",
            "responsive": true,
            "dom":  "<'row table_btn_grp'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            "buttons": this.btn,
        });
        this.table.buttons(".delete-btn").disable();
        this.table.buttons(".update-btn").disable();
        this.table.on('select', (e, dt, type, indexes) => {
        if (this.table.rows(".selected")[0].length === 0) {
                this.table.buttons(".read-btn").disable();
                this.table.buttons(".update-btn").disable();
                this.table.buttons(".delete-btn").disable();
            } else if (this.table.rows(".selected")[0].length === 1) {
                this.table.buttons(".read-btn").enable();
                this.table.buttons(".update-btn").enable();
                this.table.buttons(".delete-btn").enable();
            } else {
                this.table.buttons(".read-btn").disable();
                this.table.buttons(".update-btn").disable();
                this.table.buttons(".delete-btn").enable();
            }
        });
        this.table.on('deselect', (e, dt, type, indexes) => {
            if (this.table.rows(".selected")[0].length === 0) {
                this.table.buttons(".read-btn").disable();
                this.table.buttons(".update-btn").disable();
                this.table.buttons(".delete-btn").disable();
            } else if (this.table.rows(".selected")[0].length === 1) {
                this.table.buttons(".read-btn").enable();
                this.table.buttons(".update-btn").enable();
                this.table.buttons(".delete-btn").enable();
            } else {
                this.table.buttons(".read-btn").disable();
                this.table.buttons(".update-btn").disable();
                this.table.buttons(".delete-btn").enable();
            }
        });
    }
}