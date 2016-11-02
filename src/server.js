import React from 'react';
import ReactDOMServer from 'react-dom/server';
import express from 'express';
//import path from 'path';
import {handleReactRouter} from 'express-react-router';

import routes from './routes';


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

import Page from './modules/default_pages/Page';
import ErrorPage from './modules/default_pages/ErrorPage';

// Create Server
let app = express();

//app.use(express.static(path.join(__dirname, 'public')));

app.use((req, res, next) => {
    const {url, method, params, query} = req;
    console.log(`[${url}]: `, {method, params, query});
    next();
});
app.use(
    handleReactRouter(routes, Page, {title: 'Matcha Dating Website'}, (req) => {
        return {url: req.url};
    })
);
app.use((err, req, res, next) => {
    // Send to Client
    res.status(500).send(
        '<!DOCTYPE html>' +
        ReactDOMServer.renderToStaticMarkup(<ErrorPage err={err}/>)
        //TODO, make it so ReactDOMServer is not nessesary
    );
});

// Start Server
app.listen(8080);
console.log('listening on port 8080');


