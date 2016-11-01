'use strict';

var _react = require('react');

var _react2 = _interopRequireDefault(_react);

var _server = require('react-dom/server');

var _server2 = _interopRequireDefault(_server);

var _express = require('express');

var _express2 = _interopRequireDefault(_express);

var _expressReactRouter = require('express-react-router');

var _routes = require('./routes');

var _routes2 = _interopRequireDefault(_routes);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var Page = _react2.default.createClass({
    displayName: 'Page',
    getUrl: function getUrl() {
        return this.props.req.url;
    },
    getInnerHTML: function getInnerHTML() {
        return { __html: this.props.reactHtml };
    },
    render: function render() {
        return _react2.default.createElement(
            'html',
            null,
            _react2.default.createElement(
                'head',
                null,
                _react2.default.createElement(
                    'title',
                    null,
                    'Example Page - ',
                    this.getUrl()
                )
            ),
            _react2.default.createElement(
                'body',
                null,
                _react2.default.createElement('div', { id: 'reactContent', dangerouslySetInnerHTML: this.getInnerHTML() }),
                _react2.default.createElement('script', { src: '/app.js' })
            )
        );
    }
});

var ErrorPage = _react2.default.createClass({
    displayName: 'ErrorPage',
    render: function render() {
        var err = this.props.err;

        return _react2.default.createElement(
            'html',
            null,
            _react2.default.createElement(
                'head',
                null,
                _react2.default.createElement(
                    'title',
                    null,
                    'Example Error Page'
                )
            ),
            _react2.default.createElement(
                'body',
                null,
                _react2.default.createElement(
                    'div',
                    { id: 'error' },
                    _react2.default.createElement(
                        'h1',
                        null,
                        'Error'
                    ),
                    _react2.default.createElement(
                        'h3',
                        null,
                        err.name
                    ),
                    _react2.default.createElement(
                        'p',
                        null,
                        err.message
                    )
                )
            )
        );
    }
});

// Create Server
var app = (0, _express2.default)();
app.use(function (req, res, next) {
    var url = req.url,
        method = req.method,
        params = req.params,
        query = req.query;

    console.log('[' + url + ']: ', { method: method, params: params, query: query });
    next();
});
app.use((0, _expressReactRouter.handleReactRouter)(_routes2.default, Page, { title: 'Express React Router Example Site' }, function (req) {
    return { url: req.url };
}));
app.use(function (err, req, res, next) {
    // Send to Client
    res.status(500).send('<!DOCTYPE html>' + _server2.default.renderToStaticMarkup(_react2.default.createElement(ErrorPage, { err: err }))
    //TODO, make it so ReactDOMServer is not nessesary
    );
});

// Start Server
app.listen(8080);
console.log('listening on port 8080');