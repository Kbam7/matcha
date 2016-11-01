import React from "react";
import {Link} from "react-router";

export default class PageLayout extends React.Component {
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