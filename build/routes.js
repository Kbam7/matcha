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

var _PageLayout = require("./modules/PageLayout");

var _PageLayout2 = _interopRequireDefault(_PageLayout);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

/*
class PageLayout extends React.Component {
    render () {
        return (
            <div>
                <h4>{this.props.title} - {this.props.url}</h4>
                <ul>
                    <li><Link to="/">Page One</Link></li>
                    <li>
                        <Link to="/pageTwo">Page Two</Link>
                        <ul>
                            <li><Link to="/pageTwo/subPageOne">Subpage One</Link></li>
                            <li><Link to="/pageTwo/subPageTwo">Subpage Two</Link></li>
                            <li><a href="/pageTwo/identicon.png">indenticon</a></li>
                        </ul>
                    </li>
                    <li><a href="/func">Func</a></li>
                    <li><a href="/router">Router</a></li>
                    <li><a href="/errorFunc">Error Func</a></li>
                    <li><a href="/errorRouter">Error Router</a></li>
                </ul>
                <div>{this.props.children}</div>
            </div>
        );
    }
}
*/

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

/*--------------------------------------------------------------------------------------------------------------------*/
//  --- React Router Components ---
/*--------------------------------------------------------------------------------------------------------------------*/

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
        { path: "/", component: _PageLayout2.default },
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