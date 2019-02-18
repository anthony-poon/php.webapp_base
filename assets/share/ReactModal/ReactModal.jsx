import React from "react";
import "./main.scss";
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { faTimes } from "@fortawesome/free-solid-svg-icons"

export default class ReactModal extends React.Component{
    constructor(props) {
        super(props);
    }

    render() {
        if (this.props.isVisible) {
            $("body").css("overflow-y", "hidden");
        } else {
            $("body").css("overflow-y", "auto");
        }
        return (
            <div className={"react-modal " + (this.props.isVisible ? "" : "d-none")}>
                <div className={"row h-100"}>
                    <div className={"col-12 col-sm-9 col-md-7 col-lg-5 col-xl-4 mx-auto my-auto "  + (this.props.fullHeight ? "h-100" : "")}>
                        <div className={"react-modal__content"}>
                            <div className={"react-modal__nav"}>
                                <div className={"react-modal__close-btn"} onClick={this.props.closeModal}>
                                    <FontAwesomeIcon
                                        icon={faTimes}
                                    />
                                </div>
                                <div className={"react-modal__title"}>
                                    { this.props.title }
                                </div>
                                <div className={"react-modal__btns"}>
                                    { this.props.buttons }
                                </div>
                                <div className={"react-modal__progress-bar"} style={{
                                    width: this.props.progress+"%"
                                }}/>
                            </div>
                            <div className={"react-modal__body"}>
                                { this.props.body }
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        );
    }
};

ReactModal.defaultProps = {
    isVisible: false,
    fullHeight: true
};
