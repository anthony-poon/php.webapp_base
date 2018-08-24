global.$ = global.jQuery = require('jquery');
import EntityTable from "./share/entity_table";
import axios from 'axios';
import _ from 'underscore';

$(document).ready(function(){
    // Toggle navbar menu via selector specified in data-submenu attr
    _.each($("table[data-entity-table]"), function(el){
        let table = new EntityTable({
            "el": el
        });

        table.init();
    });
});