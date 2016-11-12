<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../vendor/autoload.php';

use GraphAware\Neo4j\Client\ClientBuilder;

include '../php/image_upload_helpers.php';

$statusMsg = '';

if (isset($_SESSION['logged_on_user']) && isset($_POST['confirmed']) && $_POST['confirmed'] === '1' && isset($_POST['img'])) {
    try {
        $user = $_SESSION['logged_on_user'];
        $img = dirname(__DIR__).'/assets/uploads/'.$_POST['img'].'.png';
        $img_tn = dirname(__DIR__).'/assets/uploads/thumbnails/tn_'.$_POST['img'].'.png';

        // Check if files exists and then delete them
        if (file_exists($img)) {
            if (!unlink(dirname(__DIR__).'/assets/uploads/'.$_POST['img'].'.png')) {
                $statusMsg .= '<div class="alert alert-warning">The image was found but not deleted. Image needs to be manually removed.</div>';
            }
        }
        if (file_exists($img_tn)) {
            if (!unlink(dirname(__DIR__).'/assets/uploads/thumbnails/tn_'.$_POST['img'].'.png')) {
                $statusMsg .= '<div class="alert alert-warning">The image thumbnail was found but not deleted. Image needs to be manually removed.</div>';
            }
        }

        // Set up DB connection
        $client = ClientBuilder::create()->addConnection('default', 'http://neo4j:123456@localhost:7474')->build();

        $client->run('MATCH (img:Image {filename:{filename}}) OPTIONAL MATCH (img)-[r]-() DELETE img, r', ['filename' => $_POST['img'].'.png']);

        // Check if deleted image was profile pic
        if (isset($user['profile_pic']) && !empty($user['profile_pic'])) {
            if ($user['profile_pic'] === 'tn_'.$_POST['img'].'.png') {
                // get remaining images and set pp as first one
                $results = $client->run('MATCH (img:Image)<-[:UPLOADED]-(:User {username:{uname}}) RETURN collect(img) AS imgs, count(img) AS n_imgs', ['uname' => $user['username']]);
                $record = $results->getRecord();
                $imgs = $record->get('imgs');
                $n_imgs = $record->get('n_imgs');
                if ($n_imgs > 0 && $imgs[0]) {
                    // There are images left, set first one as new pp
                    $results = $client->run('MATCH (u:User {username:{uname}}) SET u.profile_pic = {img} RETURN u AS user', ['uname' => $user['username'], 'img' => $imgs[0]->value('thumbnail')]);
                    $record = $results->getRecord();
                    $updated_user = $record->get('user')->values();
                    // Update session
                    $_SESSION['logged_on_user'] = $updated_user;
                } else {
                    // No more pics left
                    $results = $client->run('MATCH (u:User {username:{uname}}) REMOVE u.profile_pic RETURN u AS user', ['uname' => $user['username']]);
                    $record = $results->getRecord();
                    $updated_user = $record->get('user')->values();
                    // Update session
                    $_SESSION['logged_on_user'] = $updated_user;
                }
            }
        }

        $statusMsg .= '<div class="alert alert-success">The image has been removed.</div>';

        // Build response
        $response = array('status' => true, 'statusMsg' => $statusMsg);
    } catch (Exception $e) {
        // Log error
        error_log($e, 3, dirname(__DIR__).'/log/errors.log');
        // Build response
        $response = array('status' => false,
                        'statusMsg' => "<div class=\"alert alert-danger\"><b><u>Error Message :</u></b><br /> '.$e.' <br /><br /> <b><u>For error details, check :</u></b><br /> ".dirname(__DIR__).'/log/errors.log'.'</div>', );
    }
} else {
    // Build response
    $response = array('status' => false, 'statusMsg' => '<div class="alert alert-danger">Invalid Request</div>');
}

// JSON encode `response` and echo the JSON string
echo json_encode($response);
