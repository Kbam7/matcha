import React from 'react';

export default class PageTwo extends React.Component {
    render() {
        return (
            <div>
                <div>PageTwo - YEESS!!</div>
                <div>{this.props.children}</div>
            </div>
        );
    }
};