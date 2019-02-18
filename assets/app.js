import EntityTable from "./share/entity_table";
import _ from 'underscore';
import fHelper from './share/form_helper'
import "./app.scss";
import "babel-polyfill";
import "@fortawesome/fontawesome-free/js/all"
import "@fortawesome/fontawesome-free/css/all.css"
import "bootstrap";
import ReactDom from "react-dom";
import React from "react";
import NotificationBar from "./share/NotificationBar/NotificationBar"
$(document).ready(function(){
    // Toggle navbar menu via selector specified in data-submenu attr
    _.each($("table[data-entity-table]"), function(el){
        let table = new EntityTable({
            "el": el
        });
        table.init();
    });
    // JavaScript helper for Symfony form
    fHelper.bindDOMElement();

    ReactDom.render(
        <NotificationBar
            isVisible={false}
        />
    , document.getElementById("js-notification-bar"));
});