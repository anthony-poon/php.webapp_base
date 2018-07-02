import Vue from 'vue';
import App from "./vue_component/RestTable";
import _ from 'underscore';
import voca from 'voca';

export default class RestTable {
    constructor(options) {
        this.url = options.url;
        this.el = options.el;
        if (_.isEmpty(options.col) || _.isArray(options.col)) {
            this.col = [];
            _.each(options.col, (v, index, list) => {
                if (_.isObject(v)) {
                    this.col.push(_.defaults(v, {
                        "header": voca.titleCase(v.key),
                        "class": "col_" + index
                    }));
                } else {
                    this.col.push({
                        "header": voca.titleCase(v),
                        "class": "col_" + this.col.length,
                        "key": v
                    })
                }

            })
        } else {
            throw "'col' property must be an Array";
        }
        this.vue = new Vue({
            el: this.el,
            template: `<App
                v-bind:tCol=tCol
                v-bind:tBody=tBody
                v-bind:onRowClick=onRowClick
            />`,
            data: {
                "tBody": {},
                "tCol": options.column || [],
                "onRowClick": options.onRowClick || function(){},
            },
            components: {
                App
            }
        });
    }

    ajax(options) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: this.url,
                method: options.method || "GET",
                data: options.data || null,
                dataType: "json",
                success: (ajaxObj) => {
                    this.data = ajaxObj;
                    let keys = [];
                    if (_.isEmpty(this.col)) {
                        _.each(ajaxObj, (v, k, list) => {
                            // Extract all unique prop in each object. Duplicated value will be dismissed
                            keys = _.union(keys, _.keys(v));
                        });

                        _.each(keys, (v, k)=> {
                            this.col.push({
                                "header": v,
                                "class": "col_" + k,
                                "key": v
                            })
                        })
                    }

                    resolve(ajaxObj, this);
                    this.vue.tBody = this.data;
                    this.vue.tCol = this.col;
                },
                error: (ajaxObj) => {
                    reject(ajaxObj);
                }
            });
        });
    }
}