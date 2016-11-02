import React from "react";
import {Navbar, Nav, NavItem, NavDropdown, MenuItem} from 'react-bootstrap';
import { LinkContainer, IndexLinkContainer } from 'react-router-bootstrap';

class NavFuncs extends React.Component {
    render () {
        return (
            <ul>
                <li><a href="/func">Func</a></li>
                <li><a href="/router">Router</a></li>
                <li><a href="/errorFunc">Error Func</a></li>
                <li><a href="/errorRouter">Error Router</a></li>
            </ul>
        );
    }
}

export default class PageLayout extends React.Component {

    render () {
        return (
            <div>
                <Navbar collapseOnSelect>
                    <Navbar.Header>
                        <Navbar.Brand>
                            <a href="#">React-Bootstrap</a>
                        </Navbar.Brand>
                        <Navbar.Toggle />
                    </Navbar.Header>
                    <Navbar.Collapse>
                        <Nav>
                            <IndexLinkContainer to="/">
                                <NavItem>Page One</NavItem>
                            </IndexLinkContainer>
                            <LinkContainer to="/pageTwo">
                                <NavItem>Page Two</NavItem>
                            </LinkContainer>
                            <NavDropdown title="Dropdown" id="basic-nav-dropdown">
                                <LinkContainer to="/pageTwo/subPageOne">
                                    <MenuItem>subPageOne</MenuItem>
                                </LinkContainer>
                                <LinkContainer to="/pageTwo/subPageTwo">
                                    <MenuItem>subPageTwo</MenuItem>
                                </LinkContainer>
                                <LinkContainer to="/pageTwo/identicon.png">
                                    <MenuItem>identicon</MenuItem>
                                </LinkContainer>
                                    <MenuItem divider />
                                <LinkContainer to="/pageTwo">
                                    <MenuItem>Separated link</MenuItem>
                                </LinkContainer>
                            </NavDropdown>
                        </Nav>
                        <Nav pullRight>
                            <NavDropdown title="Dropdown" id="nav-dropdown">
                                <NavFuncs />
                            </NavDropdown>

                        </Nav>
                    </Navbar.Collapse>
                </Navbar>
                <div>{this.props.children}</div>



            </div>
        );
    }
}