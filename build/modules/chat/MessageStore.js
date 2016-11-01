'use strict';

var EventEmitter = require('events').EventEmitter;

var emitter = new EventEmitter();

var messages = [];

module.exports = {
    getMessages: function getMessages() {
        return messages.concat();
    },

    subscribe: function subscribe(callback) {
        emitter.on('update', callback);
    },

    unsubscribe: function unsubscribe(callback) {
        emitter.off('update', callback);
    },

    newMessage: function newMessage(message) {
        messages.push(message);
        emitter.emit('update');
    }
};