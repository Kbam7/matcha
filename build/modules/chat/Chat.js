'use strict';

var React = require('react');

var MessageList = require('./MessageList');
var MessageForm = require('./MessageForm');
var MessageStore = require('./MessageStore');

module.exports = React.createClass({
	displayName: 'exports',

	getInitialState: function getInitialState() {
		return {
			messages: MessageStore.getMessages()
		};
	},

	componentWillMount: function componentWillMount() {
		MessageStore.subscribe(this.updateMessages);
	},

	componentWillUnmount: function componentWillUnmount() {
		MessageStore.unsubscribe(this.updateMessages);
	},

	updateMessages: function updateMessages() {
		this.setState({
			messages: MessageStore.getMessages()
		});
	},

	onSend: function onSend(newMessage) {
		MessageStore.newMessage(newMessage);
	},
	render: function render() {
		return React.createElement(
			'div',
			null,
			React.createElement(MessageList, { messages: this.state.messages }),
			React.createElement(MessageForm, { onSend: this.onSend })
		);
	}
});