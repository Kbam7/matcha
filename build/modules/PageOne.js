"use strict";

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _react = require("react");

var _react2 = _interopRequireDefault(_react);

var _reactBootstrap = require("react-bootstrap");

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var PageOne = function (_React$Component) {
    _inherits(PageOne, _React$Component);

    function PageOne() {
        _classCallCheck(this, PageOne);

        return _possibleConstructorReturn(this, (PageOne.__proto__ || Object.getPrototypeOf(PageOne)).apply(this, arguments));
    }

    _createClass(PageOne, [{
        key: "render",
        value: function render() {
            return _react2.default.createElement(
                "div",
                null,
                _react2.default.createElement(
                    _reactBootstrap.Jumbotron,
                    null,
                    _react2.default.createElement(
                        "h1",
                        null,
                        "Hello, world!"
                    ),
                    _react2.default.createElement(
                        "p",
                        null,
                        "This is a simple hero unit, a simple jumbotron-style component for calling extra attention to featured content or information."
                    ),
                    _react2.default.createElement(
                        "p",
                        null,
                        _react2.default.createElement(
                            _reactBootstrap.Button,
                            { bsStyle: "primary" },
                            "Learn more"
                        )
                    )
                ),
                _react2.default.createElement(
                    _reactBootstrap.Grid,
                    null,
                    _react2.default.createElement(
                        _reactBootstrap.Row,
                        { className: "show-grid" },
                        _react2.default.createElement(
                            _reactBootstrap.Col,
                            { sm: 4, md: 4 },
                            _react2.default.createElement(
                                "h2",
                                null,
                                "Heading"
                            ),
                            _react2.default.createElement(
                                "p",
                                null,
                                "Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. "
                            ),
                            _react2.default.createElement(
                                "p",
                                null,
                                _react2.default.createElement(
                                    "a",
                                    { className: "btn btn-default", href: "#", role: "button" },
                                    "View details \xBB"
                                )
                            )
                        ),
                        _react2.default.createElement(
                            _reactBootstrap.Col,
                            { sm: 4, md: 4 },
                            _react2.default.createElement(
                                "h2",
                                null,
                                "Heading"
                            ),
                            _react2.default.createElement(
                                "p",
                                null,
                                "Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. "
                            ),
                            _react2.default.createElement(
                                "p",
                                null,
                                _react2.default.createElement(
                                    "a",
                                    { className: "btn btn-default", href: "#", role: "button" },
                                    "View details \xBB"
                                )
                            )
                        ),
                        _react2.default.createElement(
                            _reactBootstrap.Col,
                            { sm: 4, md: 4 },
                            _react2.default.createElement(
                                "h2",
                                null,
                                "Heading"
                            ),
                            _react2.default.createElement(
                                "p",
                                null,
                                "Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. "
                            ),
                            _react2.default.createElement(
                                "p",
                                null,
                                _react2.default.createElement(
                                    "a",
                                    { className: "btn btn-default", href: "#", role: "button" },
                                    "View details \xBB"
                                )
                            )
                        )
                    ),
                    _react2.default.createElement(
                        _reactBootstrap.Row,
                        { className: "show-grid" },
                        _react2.default.createElement(
                            _reactBootstrap.Col,
                            { sm: 4, md: 4 },
                            _react2.default.createElement(
                                "h2",
                                null,
                                "Heading"
                            ),
                            _react2.default.createElement(
                                "p",
                                null,
                                "Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. "
                            ),
                            _react2.default.createElement(
                                "p",
                                null,
                                _react2.default.createElement(
                                    "a",
                                    { className: "btn btn-default", href: "#", role: "button" },
                                    "View details \xBB"
                                )
                            )
                        ),
                        _react2.default.createElement(
                            _reactBootstrap.Col,
                            { sm: 4, md: 4 },
                            _react2.default.createElement(
                                "h2",
                                null,
                                "Heading"
                            ),
                            _react2.default.createElement(
                                "p",
                                null,
                                "Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. "
                            ),
                            _react2.default.createElement(
                                "p",
                                null,
                                _react2.default.createElement(
                                    "a",
                                    { className: "btn btn-default", href: "#", role: "button" },
                                    "View details \xBB"
                                )
                            )
                        ),
                        _react2.default.createElement(
                            _reactBootstrap.Col,
                            { sm: 4, md: 4 },
                            _react2.default.createElement(
                                "h2",
                                null,
                                "Heading"
                            ),
                            _react2.default.createElement(
                                "p",
                                null,
                                "Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. "
                            ),
                            _react2.default.createElement(
                                "p",
                                null,
                                _react2.default.createElement(
                                    "a",
                                    { className: "btn btn-default", href: "#", role: "button" },
                                    "View details \xBB"
                                )
                            )
                        )
                    ),
                    _react2.default.createElement(
                        _reactBootstrap.Row,
                        { className: "show-grid" },
                        _react2.default.createElement(
                            _reactBootstrap.Col,
                            { sm: 4, md: 4 },
                            _react2.default.createElement(
                                "h2",
                                null,
                                "Heading"
                            ),
                            _react2.default.createElement(
                                "p",
                                null,
                                "Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. "
                            ),
                            _react2.default.createElement(
                                "p",
                                null,
                                _react2.default.createElement(
                                    "a",
                                    { className: "btn btn-default", href: "#", role: "button" },
                                    "View details \xBB"
                                )
                            )
                        ),
                        _react2.default.createElement(
                            _reactBootstrap.Col,
                            { sm: 4, md: 4 },
                            _react2.default.createElement(
                                "h2",
                                null,
                                "Heading"
                            ),
                            _react2.default.createElement(
                                "p",
                                null,
                                "Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. "
                            ),
                            _react2.default.createElement(
                                "p",
                                null,
                                _react2.default.createElement(
                                    "a",
                                    { className: "btn btn-default", href: "#", role: "button" },
                                    "View details \xBB"
                                )
                            )
                        ),
                        _react2.default.createElement(
                            _reactBootstrap.Col,
                            { sm: 4, md: 4 },
                            _react2.default.createElement(
                                "h2",
                                null,
                                "Heading"
                            ),
                            _react2.default.createElement(
                                "p",
                                null,
                                "Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. "
                            ),
                            _react2.default.createElement(
                                "p",
                                null,
                                _react2.default.createElement(
                                    "a",
                                    { className: "btn btn-default", href: "#", role: "button" },
                                    "View details \xBB"
                                )
                            )
                        )
                    )
                )
            );
        }
    }]);

    return PageOne;
}(_react2.default.Component);

exports.default = PageOne;
;