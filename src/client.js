import "babel-polyfill";
import React from "react";
import {render} from "express-react-router/client";
import routes from "./routes";

// Turn on React Dev tools
window.React = React;

// Render react-router to page
render(
    routes,
    window.document.getElementById('reactContent'),
    {
        title: 'Matcha Dating Website'
    },
    () => {
        const url = window.location.pathname;

        document.title = `Matcha Dating Website - ${url}`;
        return {url};
    }
);
