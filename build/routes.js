"use strict";

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _path = require("path");

var _path2 = _interopRequireDefault(_path);

var _express = require("express");

var _express2 = _interopRequireDefault(_express);

var _react = require("react");

var _react2 = _interopRequireDefault(_react);

var _reactRouter = require("react-router");

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

/*--------------------------------------------------------------------------------------------------------------------*/
//  --- React Router Components ---
/*--------------------------------------------------------------------------------------------------------------------*/
var PageWrapper = _react2.default.createClass({
    displayName: "PageWrapper",

    render: function render() {
        return _react2.default.createElement(
            "div",
            null,
            _react2.default.createElement(
                "h4",
                null,
                this.props.title,
                " - ",
                this.props.url
            ),
            _react2.default.createElement(
                "ul",
                null,
                _react2.default.createElement(
                    "li",
                    null,
                    _react2.default.createElement(
                        _reactRouter.Link,
                        { to: "/" },
                        "Page One"
                    )
                ),
                _react2.default.createElement(
                    "li",
                    null,
                    _react2.default.createElement(
                        _reactRouter.Link,
                        { to: "/pageTwo" },
                        "Page Two"
                    ),
                    _react2.default.createElement(
                        "ul",
                        null,
                        _react2.default.createElement(
                            "li",
                            null,
                            _react2.default.createElement(
                                _reactRouter.Link,
                                { to: "/pageTwo/subPageOne" },
                                "Subpage One"
                            )
                        ),
                        _react2.default.createElement(
                            "li",
                            null,
                            _react2.default.createElement(
                                _reactRouter.Link,
                                { to: "/pageTwo/subPageTwo" },
                                "Subpage Two"
                            )
                        ),
                        _react2.default.createElement(
                            "li",
                            null,
                            _react2.default.createElement(
                                "a",
                                { href: "/pageTwo/identicon.png" },
                                "indenticon"
                            )
                        )
                    )
                ),
                _react2.default.createElement(
                    "li",
                    null,
                    _react2.default.createElement(
                        "a",
                        { href: "/func" },
                        "Func"
                    )
                ),
                _react2.default.createElement(
                    "li",
                    null,
                    _react2.default.createElement(
                        "a",
                        { href: "/router" },
                        "Router"
                    )
                ),
                _react2.default.createElement(
                    "li",
                    null,
                    _react2.default.createElement(
                        "a",
                        { href: "/errorFunc" },
                        "Error Func"
                    )
                ),
                _react2.default.createElement(
                    "li",
                    null,
                    _react2.default.createElement(
                        "a",
                        { href: "/errorRouter" },
                        "Error Router"
                    )
                )
            ),
            _react2.default.createElement(
                "div",
                null,
                this.props.children
            )
        );
    }
});
var PageOne = _react2.default.createClass({
    displayName: "PageOne",

    render: function render() {
        return _react2.default.createElement(
            "div",
            null,
            "PageOne"
        );
    }
});
var PageTwo = _react2.default.createClass({
    displayName: "PageTwo",

    render: function render() {
        return _react2.default.createElement(
            "div",
            null,
            _react2.default.createElement(
                "div",
                null,
                "PageTwo"
            ),
            _react2.default.createElement(
                "div",
                null,
                this.props.children
            )
        );
    }
});
var SubPageOne = _react2.default.createClass({
    displayName: "SubPageOne",

    render: function render() {
        return _react2.default.createElement(
            "div",
            null,
            "SubPageOne"
        );
    }
});
var SubPageTwo = _react2.default.createClass({
    displayName: "SubPageTwo",

    render: function render() {
        return _react2.default.createElement(
            "div",
            null,
            "SubPageTwo"
        );
    }
});

var NotFound = _react2.default.createClass({
    displayName: "NotFound",

    render: function render() {
        return _react2.default.createElement(
            "div",
            null,
            "404"
        );
    }
});
var NotFoundPageTwo = _react2.default.createClass({
    displayName: "NotFoundPageTwo",

    render: function render() {
        return _react2.default.createElement(
            "div",
            null,
            "Page Two 404"
        );
    }
});

/*--------------------------------------------------------------------------------------------------------------------*/
//  --- Routers / Funcs / Files ---
/*--------------------------------------------------------------------------------------------------------------------*/
var func = void 0,
    router = void 0,
    errFunc = void 0,
    errRouter = void 0,
    appSrc = void 0,
    identiconSrc = void 0,
    faviconSrc = void 0,
    filesSrc = void 0;

if (typeof window === 'undefined') {
    // Preform only on the server

    func = function func(req, res) {
        res.send({ test: 'response' });
    };

    router = _express2.default.Router();
    router.use(func);
    errFunc = function errFunc(req, res) {
        throw new Error('Test Error');
    };
    errRouter = _express2.default.Router();
    errRouter.use(errFunc);

    appSrc = _path2.default.join(__dirname, './app.js');
    identiconSrc = _path2.default.join(__dirname, '../public/identicon.png');
    faviconSrc = _path2.default.join(__dirname, '../public/favicon.ico');
    filesSrc = _path2.default.join(__dirname, '../public/');
}

/*--------------------------------------------------------------------------------------------------------------------*/
//  --- Create Route ---
/*--------------------------------------------------------------------------------------------------------------------*/
exports.default = _react2.default.createElement(
    _reactRouter.Router,
    { history: _reactRouter.browserHistory },
    _react2.default.createElement(
        _reactRouter.Route,
        { path: "/", component: PageWrapper },
        _react2.default.createElement(_reactRouter.IndexRoute, { component: PageOne }),
        _react2.default.createElement(
            _reactRouter.Route,
            { path: "pageTwo", component: PageTwo },
            _react2.default.createElement(_reactRouter.Route, { path: "subPageOne", component: SubPageOne }),
            _react2.default.createElement(
                _reactRouter.Route,
                { path: "subPageTwo" },
                _react2.default.createElement(_reactRouter.IndexRoute, { component: SubPageTwo })
            ),
            _react2.default.createElement(_reactRouter.Route, { path: "*", component: NotFoundPageTwo }),
            _react2.default.createElement(_reactRouter.Route, { path: "identicon.png", src: identiconSrc })
        ),
        _react2.default.createElement(_reactRouter.Route, { path: "*", component: NotFound }),
        _react2.default.createElement(_reactRouter.Route, { path: "favicon.ico", src: faviconSrc }),
        _react2.default.createElement(_reactRouter.Route, { path: "app.js", src: appSrc }),
        _react2.default.createElement(_reactRouter.Route, { path: "files", src: filesSrc }),
        _react2.default.createElement(_reactRouter.Route, { path: "func", use: func }),
        _react2.default.createElement(_reactRouter.Route, { path: "router", use: router }),
        _react2.default.createElement(_reactRouter.Route, { path: "errorFunc", use: errFunc }),
        _react2.default.createElement(_reactRouter.Route, { path: "errorRouter", use: errRouter })
    )
);