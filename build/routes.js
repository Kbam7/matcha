"use strict";

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _path = require("path");

var _path2 = _interopRequireDefault(_path);

var _express = require("express");

var _express2 = _interopRequireDefault(_express);

var _react = require("react");

var _react2 = _interopRequireDefault(_react);

var _reactRouter = require("react-router");

var _PageOne = require("./modules/PageOne");

var _PageOne2 = _interopRequireDefault(_PageOne);

var _PageTwo = require("./modules/PageTwo");

var _PageTwo2 = _interopRequireDefault(_PageTwo);

var _SubPageOne = require("./modules/SubPageOne");

var _SubPageOne2 = _interopRequireDefault(_SubPageOne);

var _SubPageTwo = require("./modules/SubPageTwo");

var _SubPageTwo2 = _interopRequireDefault(_SubPageTwo);

var _NotFound = require("./modules/NotFound");

var _NotFound2 = _interopRequireDefault(_NotFound);

var _NotFoundPageTwo = require("./modules/NotFoundPageTwo");

var _NotFoundPageTwo2 = _interopRequireDefault(_NotFoundPageTwo);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

/*--------------------------------------------------------------------------------------------------------------------*/
//  --- React Router Components ---
/*--------------------------------------------------------------------------------------------------------------------*/

var PageLayout = function (_React$Component) {
    _inherits(PageLayout, _React$Component);

    function PageLayout() {
        _classCallCheck(this, PageLayout);

        return _possibleConstructorReturn(this, (PageLayout.__proto__ || Object.getPrototypeOf(PageLayout)).apply(this, arguments));
    }

    _createClass(PageLayout, [{
        key: "render",
        value: function render() {
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
    }]);

    return PageLayout;
}(_react2.default.Component);

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
        { path: "/", component: PageLayout },
        _react2.default.createElement(_reactRouter.IndexRoute, { component: _PageOne2.default }),
        _react2.default.createElement(
            _reactRouter.Route,
            { path: "pageTwo", component: _PageTwo2.default },
            _react2.default.createElement(_reactRouter.Route, { path: "subPageOne", component: _SubPageOne2.default }),
            _react2.default.createElement(
                _reactRouter.Route,
                { path: "subPageTwo" },
                _react2.default.createElement(_reactRouter.IndexRoute, { component: _SubPageTwo2.default })
            ),
            _react2.default.createElement(_reactRouter.Route, { path: "*", component: _NotFoundPageTwo2.default }),
            _react2.default.createElement(_reactRouter.Route, { path: "identicon.png", src: identiconSrc })
        ),
        _react2.default.createElement(_reactRouter.Route, { path: "*", component: _NotFound2.default }),
        _react2.default.createElement(_reactRouter.Route, { path: "favicon.ico", src: faviconSrc }),
        _react2.default.createElement(_reactRouter.Route, { path: "app.js", src: appSrc }),
        _react2.default.createElement(_reactRouter.Route, { path: "files", src: filesSrc }),
        _react2.default.createElement(_reactRouter.Route, { path: "func", use: func }),
        _react2.default.createElement(_reactRouter.Route, { path: "router", use: router }),
        _react2.default.createElement(_reactRouter.Route, { path: "errorFunc", use: errFunc }),
        _react2.default.createElement(_reactRouter.Route, { path: "errorRouter", use: errRouter })
    )
);