import React from "react";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faCheck, faInfoCircle, faExclamationTriangle, faTimes } from "@fortawesome/free-solid-svg-icons";
import { faComment } from "@fortawesome/free-regular-svg-icons";

export default class NotificationBar extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            type: "info",
            isVisible: this.props.isVisible,
            text: null,
        }
    }

    componentDidMount() {
        $(document).on("notification", (evt, param = {}) => {
            console.log(param);
            this.setState({
                type: param.type ? param.type : this.state.type,
                text: param.text ? param.text: null,
                isVisible: true
            });
            setTimeout(() => {
                this.setState({
                    isVisible: false
                })
            }, 3000);
        })
    }

    render() {
        let icon = null;
        let bgColor = null;
        switch (this.state.type) {
            case "success":
                icon = faCheck;
                bgColor = "bg-success";
                break;
            case "primary":
                icon = faComment;
                bgColor = "bg-primary";
                break;
            case "info":
                icon = faInfoCircle;
                bgColor = "bg-info";
                break;
            case "danger":
                icon = faExclamationTriangle;
                bgColor = "bg-danger";
                break;
            default:
                throw "Invalid notification type."
        }
        return (
            <div className={[
                "notification-bar",
                bgColor,
                this.state.isVisible ? "d-block" : "d-none"
            ].join(" ")}>
                <div className="container py-2">
                    <div className="row align-items-center">
                        <span className="col-auto">
                            <FontAwesomeIcon icon={icon}/>
                        </span>
                        <span className="ml-3 col">
                            { this.state.text }
                        </span>
                        <span className="col-auto">
                            <button className={"btn btn-link text-light"} onClick={() => {
                                this.setState({
                                    isVisible: false
                                })
                            }}>
                                <FontAwesomeIcon icon={faTimes}/>
                            </button>
                        </span>
                    </div>
                </div>
            </div>
        );
    }
}

NotificationBar.defaultProps = {
    isVisible: false,
    type: "info"
};