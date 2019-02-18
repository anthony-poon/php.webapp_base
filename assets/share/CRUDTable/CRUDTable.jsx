import React from "react";
import {FontAwesomeIcon} from "@fortawesome/react-fontawesome";
import {faEdit, faTimes} from "@fortawesome/free-solid-svg-icons";

export default class CRUDTable extends React.Component{
    constructor(props) {
        super(props)
    }

    render() {
        let rows = $.map(this.props.rows, (rows) => {
            let cell = $.map(rows.cell, (val, index) => {
                return <td key={index} className={"align-middle"}>{ val }</td> ;
            });
            return (
                <tr key={rows.key}>
                    { cell }
                    <td className={"text-right"}>
                        <button className={"btn btn-link"} onClick={() => {
                            this.props.onEdit(rows.payload);
                        }}>
                            <FontAwesomeIcon icon={faEdit}/>
                        </button>
                        <button className={"btn btn-link text-danger"} onClick={() => {
                            this.props.onDelete(rows.payload);
                        }}>
                            <FontAwesomeIcon icon={faTimes}/>
                        </button>
                    </td>
                </tr>
            )
        });
        let headers = $.map(this.props.headers, (header, index) => {
            return <td key={index}>{ header }</td>
        });
        return (
            <div>
                <div className={"my-3 d-flex align-items-center"}>
                    <h5 className={"flex-grow-1 text-secondary"}>
                        { this.props.title }
                    </h5>
                    <div>
                        <button className={"btn btn-outline-primary"} onClick={this.props.onCreate}>
                            Add
                        </button>
                    </div>
                </div>
                <table className={"table"}>
                    <thead className={"thead-light"}>
                        <tr>
                            { headers }
                        </tr>
                    </thead>
                    <tbody>
                        { rows }
                    </tbody>
                </table>
            </div>
        );
    }
}