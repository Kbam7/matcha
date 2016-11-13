<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../vendor/autoload.php';
use GraphAware\Neo4j\Client\ClientBuilder;

include '../php/image_upload_helpers.php';

$statusMsg = '';

if (!isset($_SESSION['logged_on_user'])) {
    $response = array('status' => false, 'statusMsg' => '<div class="alert alert-danger">Please log in to upload an image.</div>');
    die(json_encode($response));
} else {
    $user = $_SESSION['logged_on_user'];
}

if (isset($_POST['submit']) && $_POST['submit'] === '1') {
    if (user_image_count() === 5) {
        $response = array('status' => false, 'statusMsg' => '<div class="alert alert-warning">Maximum amount of images reached. Please delete some images.');
        die(json_encode($response));
    }

    $title = 'No Title Given';
    $desc = 'No Description Given';

    if (isset($_POST['imgTitle'])) {
        $title = $_POST['imgTitle'];
    }
    if (isset($_POST['imgDesc'])) {
        $desc = $_POST['imgDesc'];
    }

    $dir = '../assets/uploads/';
    $file = $dir.uniqid().'.png';

    // Check if file returns size
    $img_info = getimagesize($_FILES['userfile']['tmp_name']);
    if ($img_info === false) {
        $response = array('status' => false, 'statusMsg' => '<div class="alert alert-warning">Select a valid image to upload. <br />E.G:   JPG, JPEG, PNG or GIF');
        die(json_encode($response));
    }

    $imageFileType = pathinfo($_FILES['userfile']['name'], PATHINFO_EXTENSION);

    // Check if directory exists already
    if (!file_exists($dir)) {
        if (!mkdir($dir, 0777)) {
            $response = array('status' => false, 'statusMsg' => '<div class="alert alert-danger">Unable to create directory for images. Cannot save your image.<br />Please make sure you have rights for the directory " '.$dir.' "</div>');
            die(json_encode($response));
        }
    }

    // Check if file exists already
    if (file_exists($file)) {
        $response = array('status' => false, 'statusMsg' => "<div class=\"alert alert-warning\">The file you want to upload already exists. '".$file."'</div>");
        die(json_encode($response));
    }

    // Check file size not bigger than 10mb or 0 bytes
    if ($_FILES['userfile']['size'] > 10000000) {
        $response = array('status' => false, 'statusMsg' => "<div class=\"alert alert-warning\">Your file is too large. Maximum size of '10mb' allowed.</div>");
        die(json_encode($response));
    } elseif ($_FILES['userfile']['size'] == 0) {
        $response = array('status' => false, 'statusMsg' => '<div class="alert alert-warning">Your file has no size. Please select a valid image.</div>');
        die(json_encode($response));
    }

    // Allow certain file formats
    if ($imageFileType != 'jpg' && $imageFileType != 'png' &&
      $imageFileType != 'jpeg' && $imageFileType != 'gif') {
        $response = array('status' => false, 'statusMsg' => '<div class="alert alert-warning">Only JPG, JPEG, PNG & GIF files are allowed.</div>');
        die(json_encode($response));
    }

    /* NOTICE */
    /* Currently the user upload image does not receive an overlay if the overlay is selected.  */
    /* The users image is first scaled to 640x480 and then any overlay given will be used. */

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

    // Save the image
    if (save_image($overlay, $_FILES['userfile']['tmp_name'], $file)) {
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
            // Incase profile pictue is updated
            $updated_user = $record->get('user')->values();

        /*    // Assign 'uid' field to `user` array
            $user['uid'] = $updates->get('uid');*/

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
} else {
    $response = array('status' => false, 'statusMsg' => '<div class="alert alert-danger">Could not find data sent via POST method</div>');
}
echo json_encode($response);
