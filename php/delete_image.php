<?php

session_start();

require_once '../vendor/autoload.php';

use GraphAware\Neo4j\Client\ClientBuilder;

$statusMsg = '';

if (isset($_SESSION['logged_on_user']) && isset($_POST['confirmed']) && $_POST['confirmed'] === '1' && isset($_POST['img'])) {
    try {
        if (!unlink('../assets/uploads/'.isset($_POST['img']).'.png')) {
            $response = array('status' => false, 'statusMsg' => '<p class="alert alert-warning">The image was not deleted because it cannot be found.</p>');
            die(json_encode($response));
        }
        if (!unlink('../assets/uploads/thumbnails/tn_'.isset($_POST['img']).'.png')) {
            $response = array('status' => false, 'statusMsg' => '<p class="alert alert-warning">The image thumbnail was not deleted because its thumbnail cannot be found.</p>');
            die(json_encode($response));
        }

        $user = $_SESSION['logged_on_user'];

        // Set up DB connection
        $client = ClientBuilder::create()->addConnection('default', 'http://neo4j:123456@localhost:7474')->build();

        $result = $client->run('MATCH (img:Image {filename:{filename}}) OPTIONAL MATCH (img)-[r]-() DELETE img, r', ['filename' => $_POST['img'].'.png']);

        // Build response
        $response = array('status' => true, 'statusMsg' => '<p class="alert alert-success">The image has been deleted.</p>');
    } catch (Exception $e) {
        // Log error
        error_log($e, 3, dirname(__DIR__).'/log/errors.log');
        // Build response
        $response = array('status' => false,
                        'statusMsg' => "<p class=\"alert alert-danger\"><b><u>Error Message :</u></b><br /> '.$e->getMessage().' <br /><br /> <b><u>For error details, check :</u></b><br /> ".dirname(__DIR__).'/log/errors.log'.'</p>', );
    }
} else {
    // Build response
    $response = array('status' => false, 'statusMsg' => '<p class="alert alert-danger">Invalid Request</p>');
}

// JSON encode `response` and echo the JSON string
echo json_encode($response);
