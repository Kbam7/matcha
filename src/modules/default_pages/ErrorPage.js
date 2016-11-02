import React from 'react';

export default class ErrorPage extends React.Component {
    render() {
        const {err} = this.props;
        return (
            <html>
            <head>
                <title>Matcha - Error Page</title>
            </head>
            <body>
            <div id="error">
                <h1>Error</h1>
                <h3>{err.name}</h3>
                <p>
                    {err.message}
                </p>
            </div>
            </body>
            </html>
        );
    }
};