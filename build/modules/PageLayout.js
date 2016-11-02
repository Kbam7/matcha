'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _react = require('react');

var _react2 = _interopRequireDefault(_react);

var _reactBootstrap = require('react-bootstrap');

var _reactRouterBootstrap = require('react-router-bootstrap');

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var NavFuncs = function (_React$Component) {
    _inherits(NavFuncs, _React$Component);

    function NavFuncs() {
        _classCallCheck(this, NavFuncs);

        return _possibleConstructorReturn(this, (NavFuncs.__proto__ || Object.getPrototypeOf(NavFuncs)).apply(this, arguments));
    }

    _createClass(NavFuncs, [{
        key: 'render',
        value: function render() {
            return _react2.default.createElement(
                'ul',
                null,
                _react2.default.createElement(
                    'li',
                    null,
                    _react2.default.createElement(
                        'a',
                        { href: '/func' },
                        'Func'
                    )
                ),
                _react2.default.createElement(
                    'li',
                    null,
                    _react2.default.createElement(
                        'a',
                        { href: '/router' },
                        'Router'
                    )
                ),
                _react2.default.createElement(
                    'li',
                    null,
                    _react2.default.createElement(
                        'a',
                        { href: '/errorFunc' },
                        'Error Func'
                    )
                ),
                _react2.default.createElement(
                    'li',
                    null,
                    _react2.default.createElement(
                        'a',
                        { href: '/errorRouter' },
                        'Error Router'
                    )
                )
            );
        }
    }]);

    return NavFuncs;
}(_react2.default.Component);

var PageLayout = function (_React$Component2) {
    _inherits(PageLayout, _React$Component2);

    function PageLayout() {
        _classCallCheck(this, PageLayout);

        return _possibleConstructorReturn(this, (PageLayout.__proto__ || Object.getPrototypeOf(PageLayout)).apply(this, arguments));
    }

    _createClass(PageLayout, [{
        key: 'render',
        value: function render() {
            return _react2.default.createElement(
                'div',
                null,
                _react2.default.createElement(
                    _reactBootstrap.Navbar,
                    { collapseOnSelect: true },
                    _react2.default.createElement(
                        _reactBootstrap.Navbar.Header,
                        null,
                        _react2.default.createElement(
                            _reactBootstrap.Navbar.Brand,
                            null,
                            _react2.default.createElement(
                                'a',
                                { href: '#' },
                                'React-Bootstrap'
                            )
                        ),
                        _react2.default.createElement(_reactBootstrap.Navbar.Toggle, null)
                    ),
                    _react2.default.createElement(
                        _reactBootstrap.Navbar.Collapse,
                        null,
                        _react2.default.createElement(
                            _reactBootstrap.Nav,
                            null,
                            _react2.default.createElement(
                                _reactRouterBootstrap.IndexLinkContainer,
                                { to: '/' },
                                _react2.default.createElement(
                                    _reactBootstrap.NavItem,
                                    null,
                                    'Page One'
                                )
                            ),
                            _react2.default.createElement(
                                _reactRouterBootstrap.LinkContainer,
                                { to: '/pageTwo' },
                                _react2.default.createElement(
                                    _reactBootstrap.NavItem,
                                    null,
                                    'Page Two'
                                )
                            ),
                            _react2.default.createElement(
                                _reactBootstrap.NavDropdown,
                                { title: 'Dropdown', id: 'basic-nav-dropdown' },
                                _react2.default.createElement(
                                    _reactRouterBootstrap.LinkContainer,
                                    { to: '/pageTwo/subPageOne' },
                                    _react2.default.createElement(
                                        _reactBootstrap.MenuItem,
                                        null,
                                        'subPageOne'
                                    )
                                ),
                                _react2.default.createElement(
                                    _reactRouterBootstrap.LinkContainer,
                                    { to: '/pageTwo/subPageTwo' },
                                    _react2.default.createElement(
                                        _reactBootstrap.MenuItem,
                                        null,
                                        'subPageTwo'
                                    )
                                ),
                                _react2.default.createElement(
                                    _reactRouterBootstrap.LinkContainer,
                                    { to: '/pageTwo/identicon.png' },
                                    _react2.default.createElement(
                                        _reactBootstrap.MenuItem,
                                        null,
                                        'identicon'
                                    )
                                ),
                                _react2.default.createElement(_reactBootstrap.MenuItem, { divider: true }),
                                _react2.default.createElement(
                                    _reactRouterBootstrap.LinkContainer,
                                    { to: '/pageTwo' },
                                    _react2.default.createElement(
                                        _reactBootstrap.MenuItem,
                                        null,
                                        'Separated link'
                                    )
                                )
                            )
                        ),
                        _react2.default.createElement(
                            _reactBootstrap.Nav,
                            { pullRight: true },
                            _react2.default.createElement(
                                _reactBootstrap.NavDropdown,
                                { title: 'Dropdown', id: 'nav-dropdown' },
                                _react2.default.createElement(NavFuncs, null)
                            )
                        )
                    )
                ),
                _react2.default.createElement(
                    'h4',
                    null,
                    this.props.title,
                    ' - ',
                    this.props.url
                ),
                _react2.default.createElement(
                    'div',
                    null,
                    this.props.children
                )
            );
        }
    }]);

    return PageLayout;
}(_react2.default.Component);

exports.default = PageLayout;