'use strict';

var React = require('react');

var ChatMessage = require('./ChatMessage');

module.exports = React.createClass({
    displayName: 'exports',

    render: function render() {
        var messages = this.props.messages.map(function (msg) {
            return React.createElement(ChatMessage, { message: msg });
        });

        return React.createElement(
            'div',
            null,
            messages
        );
    }
});