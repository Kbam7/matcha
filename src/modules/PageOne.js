import React from "react";
import {Jumbotron, Grid, Row, Col, Button} from 'react-bootstrap';

export default class PageOne extends React.Component {
    render () {
        return (
            <div>
                <Jumbotron>
                    <h1>Hello, world!</h1>
                    <p>This is a simple hero unit, a simple jumbotron-style component for calling extra attention to featured content or information.</p>
                    <p><Button bsStyle="primary">Learn more</Button></p>
                </Jumbotron>
                <Grid>
                    <Row className="show-grid">
                        <Col sm={4} md={4}>
                            <h2>Heading</h2>
                            <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
                            <p><a className="btn btn-default" href="#" role="button">View details &raquo;</a></p>
                        </Col>
                        <Col sm={4} md={4}>
                            <h2>Heading</h2>
                            <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
                            <p><a className="btn btn-default" href="#" role="button">View details &raquo;</a></p>
                        </Col>
                        <Col sm={4} md={4}>
                            <h2>Heading</h2>
                            <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
                            <p><a className="btn btn-default" href="#" role="button">View details &raquo;</a></p>
                        </Col>
                    </Row>

                    <Row className="show-grid">
                        <Col sm={4} md={4}>
                            <h2>Heading</h2>
                            <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
                            <p><a className="btn btn-default" href="#" role="button">View details &raquo;</a></p>
                        </Col>
                        <Col sm={4} md={4}>
                            <h2>Heading</h2>
                            <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
                            <p><a className="btn btn-default" href="#" role="button">View details &raquo;</a></p>
                        </Col>
                        <Col sm={4} md={4}>
                            <h2>Heading</h2>
                            <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
                            <p><a className="btn btn-default" href="#" role="button">View details &raquo;</a></p>
                        </Col>
                    </Row>
                    <Row className="show-grid">
                        <Col sm={4} md={4}>
                            <h2>Heading</h2>
                            <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
                            <p><a className="btn btn-default" href="#" role="button">View details &raquo;</a></p>
                        </Col>
                        <Col sm={4} md={4}>
                            <h2>Heading</h2>
                            <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
                            <p><a className="btn btn-default" href="#" role="button">View details &raquo;</a></p>
                        </Col>
                        <Col sm={4} md={4}>
                            <h2>Heading</h2>
                            <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
                            <p><a className="btn btn-default" href="#" role="button">View details &raquo;</a></p>
                        </Col>
                    </Row>
                </Grid>
            </div>
        )
    }
};
