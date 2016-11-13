<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../vendor/autoload.php';
use GraphAware\Neo4j\Client\ClientBuilder;

include '../php/image_upload_helpers.php';

$statusMsg = '';

if (!isset($_SESSION['logged_on_user'])) {
    $response = array('status' => false, 'statusMsg' => '<div class="alert alert-danger">Please log in to take photos.</div>');
    die(json_encode($response));
} else {
    $user = $_SESSION['logged_on_user'];
}

// Check for form data
if (isset($_POST['submit']) && isset($_POST['image'])) {
    if (user_image_count() === 5) {
        $response = array('status' => false, 'statusMsg' => '<div class="alert alert-warning">Maximum amount of images reached. Please delete some images.');
        die(json_encode($response));
    }

    $user = $_SESSION['logged_on_user'];
    $dir = '../assets/uploads/';
    $file = $dir.uniqid().'.png';

    if (!file_exists($dir)) {
        if (!mkdir($dir, 0777)) {
            $statusMsg .= '<div class="alert alert-danger">Unable to create directory for images. Cannot save your image.<br />Please make sure you have rights for the directory " '.$dir.' "</div>';
            $response = array('status' => false, 'statusMsg' => $statusMsg);
            die(json_encode($response));
        }
    }

    if (file_exists($file)) {
        $statusMsg .= "<div class=\"alert alert-warning\">The file you want to upload already exists. '".$file."'</div>";
        $response = array('status' => false, 'statusMsg' => $statusMsg);
        die(json_encode($response));
    }

    // Base64 encoded image from webcam
    $img = $_POST['image'];
    // A title for the image
    $title = 'ImageTitleHere';
    // A short caption
    $desc = 'Image caption/description';

    // Remove excess text
    $img = str_replace('data:image/png;base64,', '', $img);
    $img = str_replace(' ', '+', $img);

    // Save base64 data as temp png image to use in save_image function
    $img = imagecreatefromstring(base64_decode($img));
    // Create and save alpha channel
    imagealphablending($img, true);
    imagesavealpha($img, true);
    // Save temp image
    imagepng($img, $dir.'tmp_base64img.png');

    // Cleanup image object
    imagedestroy($img);

    // Check for overlay
    $overlay = null;
    if (isset($_POST['overlay']) && file_exists($_POST['overlay'])) {
        $overlay = imagecreatefrompng($_POST['overlay']);
        if (!$overlay) {
            $overlay = null;
        } elseif (!(imagealphablending($overlay, true) && imagesavealpha($overlay, true))) {
            $overlay = null;
        }
    } elseif (isset($_POST['overlay'])) {
        $response = array('status' => false, 'statusMsg' => '<div class="alert alert-warning">Cannot find the selected overlay image. Image not uploaded.</div>');
        die(json_encode($response));
    }

    // Save the image to server and apply overlay if present
    if (save_image($overlay, $dir.'tmp_base64img.png', $file)) {
        // Successfully resized and saved image

        // Save to database
        try {
            $client = ClientBuilder::create()->addConnection('default', 'http://neo4j:123456@localhost:7474')->build();

            $stack = $client->stack();

            // Create new image and relationship for user and return the image object
            $stack->push('MATCH (u:User {username:{uname}})'
                        .'MERGE (u)-[:UPLOADED {timestamp:{time}}]->'
                            .'(img:Image {timestamp:{time}, title:{title}, description:{desc}, filename:{filename}, thumbnail:{tn}})',
                        ['uname' => $user['username'], 'time' => time(), 'title' => $title,
                            'desc' => $desc, 'filename' => basename($file), 'tn' => 'tn_'.basename($file), ],
                        'create_img');

            // save first image as profile picture
            if (user_image_count() === 0) {
                $stack->push('MATCH (u:User {username:{uname}})-[:UPLOADED]->(img:Image {filename:{filename}})'
                            .' SET u.profile_pic = img.thumbnail',
                            ['uname' => $user['username'], 'filename' => basename($file)],
                            'set_pp');
            }

            $stack->push('MATCH (u:User {username:{uname}})-[:UPLOADED]->(img:Image {filename:{filename}})'
                        .'RETURN img, u as user',
                        ['uname' => $user['username'], 'filename' => basename($file)],
                        'new_img');

            $results = $client->runStack($stack);

            $record = $results->get('new_img')->getRecord();
            $img = $record->get('img')->values();
            $updated_user = $record->get('user')->values();

            // Check if users profile is filled out enough
            // gender, interested, location and one picture
            $fields_to_check = array('gender', 'sex_pref', 'latitude', 'longitude', 'bio', 'profile_pic');
            $flag = 1;
            foreach ($fields_to_check as $key) {
                if (!array_key_exists($key, $updated_user) || empty($updated_user[$key])) {
                    $flag = 0;
                }
            }
            // Update profile status
            $results = $client->run('MATCH (u:User {username: {uname}}) SET u.profile_complete = {value} RETURN u AS user;',
                            ['uname' => $updated_user['username'], 'value' => $flag]);
            $updates = $results->getRecord();
            $updated_user = $updates->get('user')->values();

            // Update session
            $_SESSION['logged_on_user'] = $updated_user;

            $statusMsg .= '<div class="alert alert-success">New image uploaded.</div>';
            $response = array('status' => true, 'statusMsg' => $statusMsg, 'image' => $img, 'username' => $user['username']);
        } catch (Exception $e) {
            $statusMsg .= '<div class="alert alert-danger"><b><u>Error Message :</u></b><br /> '.$e.' <br /><br /> <b><u>For error details, check :</u></b><br /> '.dirname(__DIR__).'/log/errors.log</div>';
            $response = array('status' => false, 'statusMsg' => $statusMsg);
            error_log($e, 3, dirname(__DIR__).'/log/errors.log');
        }
    } else {
        $statusMsg .= '<div class="alert alert-warning">Oops! There was an error resizing and saving the file..</div>';
        $response = array('status' => false, 'statusMsg' => $statusMsg);
    }

    // Destroy overlay image object
    if ($overlay) {
        imagedestroy($overlay);
    }
    // remove temp image
    unlink(dirname(__DIR__).'/assets/uploads/tmp_base64img.png');
} else {
    $statusMsg .= '<div class="alert alert-danger">The form data was not received. Something weird has happened. . .</div>';
    $response = array('status' => false, 'statusMsg' => $statusMsg);
}

echo json_encode($response);
