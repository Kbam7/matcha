import React from 'react';

export default class Page extends React.Component {
    getUrl() {
        return this.props.req.url;
    }
    getInnerHTML() {
        return {__html: this.props.reactHtml};
    }
    render() {
        return (
            <html lang="en">
            <head>
                <meta charSet="utf-8" />
                <meta httpEquiv="X-UA-Compatible" content="IE=edge" />
                <meta name="viewport" content="width=device-width, initial-scale=1" />
                <meta name="description" content="" />
                <meta name="author" content="" />
                <title>Matcha - {this.getUrl()}</title>
                <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/latest/css/bootstrap.min.css" />
            </head>
            <body>
            <div id="reactContent" dangerouslySetInnerHTML={this.getInnerHTML()}/>
            <script src="/app.js"></script>
            </body>
            </html>
        );
    }
};