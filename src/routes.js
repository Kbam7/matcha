import path from "path";
import express from "express";
import React from "react";
import {Router, Route, IndexRoute, Link, browserHistory} from "react-router";

/*--------------------------------------------------------------------------------------------------------------------*/
//  --- React Router Components ---
/*--------------------------------------------------------------------------------------------------------------------*/


import PageOne from "./modules/PageOne";
import PageTwo from "./modules/PageTwo";
import SubPageOne from "./modules/SubPageOne";
import SubPageTwo from "./modules/SubPageTwo";
import NotFound from "./modules/NotFound";
import NotFoundPageTwo from "./modules/NotFoundPageTwo";

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


/*--------------------------------------------------------------------------------------------------------------------*/
//  --- Routers / Funcs / Files ---
/*--------------------------------------------------------------------------------------------------------------------*/
let func, router, errFunc, errRouter, appSrc, identiconSrc, faviconSrc, filesSrc;

if (typeof window === 'undefined') {  // Preform only on the server

    func = function (req, res) {
        res.send({test: 'response'});
    };

    router = express.Router();
    router.use(func);
    errFunc = function (req, res) {
        throw new Error('Test Error');
    };
    errRouter = express.Router();
    errRouter.use(errFunc);

    appSrc = path.join(__dirname, './app.js');
    identiconSrc = path.join(__dirname, '../public/identicon.png');
    faviconSrc = path.join(__dirname, '../public/favicon.ico');
    filesSrc = path.join(__dirname, '../public/');
}

/*--------------------------------------------------------------------------------------------------------------------*/
//  --- Create Route ---
/*--------------------------------------------------------------------------------------------------------------------*/
export default (
    <Router history={browserHistory}>
        <Route path="/" component={PageLayout}>
            <IndexRoute component={PageOne}/>
            <Route path="pageTwo" component={PageTwo}>
                <Route path="subPageOne" component={SubPageOne}/>
                <Route path="subPageTwo">
                    <IndexRoute component={SubPageTwo}/>
                </Route>
                <Route path="*" component={NotFoundPageTwo}/>
                <Route path="identicon.png" src={identiconSrc}/>
            </Route>
            <Route path="*" component={NotFound}/>
            <Route path="favicon.ico" src={faviconSrc}/>
            <Route path="app.js" src={appSrc}/>
            <Route path="files" src={filesSrc}/>
            <Route path="func" use={func}/>
            <Route path="router" use={router}/>
            <Route path="errorFunc" use={errFunc}/>
            <Route path="errorRouter" use={errRouter}/>
        </Route>
    </Router>
);
