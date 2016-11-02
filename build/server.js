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

var _Page = require('./modules/default_pages/Page');

var _Page2 = _interopRequireDefault(_Page);

var _ErrorPage = require('./modules/default_pages/ErrorPage');

var _ErrorPage2 = _interopRequireDefault(_ErrorPage);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

// Create Server


/*
var neo4j = require('node-neo4j');
var db = new neo4j('http://neo4j:123456@localhost:7474');

db.cypherQuery('CREATE (somebody:Person { name: "kbam7", from: "donno", age: 21 }) RETURN somebody', function(err, result){
    if(err) {
        console.log(err);
        throw err;
    }

    console.log(result);
    console.log(result.data); // delivers an array of query results
    console.log(result.columns); // delivers an array of names of objects getting returned
});
*/

//import path from 'path';
var app = (0, _express2.default)();

//app.use(express.static(path.join(__dirname, 'public')));

app.use(function (req, res, next) {
    var url = req.url,
        method = req.method,
        params = req.params,
        query = req.query;

    console.log('[' + url + ']: ', { method: method, params: params, query: query });
    next();
});
app.use((0, _expressReactRouter.handleReactRouter)(_routes2.default, _Page2.default, { title: 'Matcha Dating Website' }, function (req) {
    return { url: req.url };
}));
app.use(function (err, req, res, next) {
    // Send to Client
    res.status(500).send('<!DOCTYPE html>' + _server2.default.renderToStaticMarkup(_react2.default.createElement(_ErrorPage2.default, { err: err }))
    //TODO, make it so ReactDOMServer is not nessesary
    );
});

// Start Server
app.listen(8080);
console.log('listening on port 8080');