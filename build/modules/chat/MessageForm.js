'use strict';

var React = require('react');

module.exports = React.createClass({
    displayName: 'exports',

    getInitialState: function getInitialState() {
        return {
            input: ''
        };
    },

    submit: function submit(ev) {
        ev.preventDefault();

        this.props.onSend(this.state.input);

        this.setState({
            input: ''
        });
    },

    updateInput: function updateInput(ev) {
        this.setState({ input: ev.target.value });
    },

    render: function render() {
        return React.createElement(
            'form',
            { onSubmit: this.submit },
            React.createElement('input', { value: this.state.input, onChange: this.updateInput, type: 'text' }),
            React.createElement('input', { type: 'submit', value: 'Send' })
        );
    }
});