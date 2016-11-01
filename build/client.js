"use strict";

require("babel-polyfill");

var _react = require("react");

var _react2 = _interopRequireDefault(_react);

var _client = require("express-react-router/client");

var _routes = require("./routes");

var _routes2 = _interopRequireDefault(_routes);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

// Turn on React Dev tools
window.React = _react2.default;

// Render react-router to page
(0, _client.render)(_routes2.default, window.document.getElementById('reactContent'), {
    title: 'Express React Router Example Site'
}, function () {
    var url = window.location.pathname;

    document.title = "Example Page - " + url;
    return { url: url };
});