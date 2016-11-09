<?php

require_once '../vendor/autoload.php';
use GraphAware\Neo4j\Client\ClientBuilder;

// Gets number of images currently uploaded by user
function user_image_count()
{
    $user = $_SESSION['logged_on_user'];

    try {
        $client = ClientBuilder::create()->addConnection('default', 'http://neo4j:123456@localhost:7474')->build();

        $stack = $client->stack();

        // Create new image and relationship for user
        $stack->push('MATCH (u:User {username:{uname}})-[:UPLOADED]->(img:Image)'
                    .'RETURN count(img) AS count', ['uname' => $user['username']],
                    'img_count');

        $results = $client->runStack($stack);

        $record = $results->get('img_count')->getRecord();
        $count = $record->get('count');

        return $count;
    } catch (Exception $e) {
        error_log($e, 3, dirname(__DIR__).'/log/errors.log');

        return 10;
    }
}

// Resizes and Saves new image as well as thumbnails
function save_image($overlay, $src, $destination)
{
    global $statusMsg;

    // Set default padding for images
    $padd_x = 0;
    $padd_y = 0;

    // Create blank image object
    $blank_img = imagecreatetruecolor(640, 480);
    // Make the background transparent
    $transparent_blk = imagecolorallocatealpha($blank_img, 0, 0, 0, 127);
    imagecolortransparent($blank_img, $transparent_blk);

    // Get image dimensions
    $img_info = list($width, $height) = getimagesize($src);
    // Create image object
    $new_img = imagecreatefromstring(file_get_contents($src));

    // If width greater than 640px, scale image to 640 width
    if ($img_info[0] > 640) {
        $new_img = imagescale($new_img, 640);
    }

    // Check if x padding needed
    if ($img_info[0] < 640) {
        $padd_x = round((640 - $img_info[0]) / 2);
    }
    // Check if y padding needed
    if ($img_info[0] < 480) {
        $padd_y = round((480 - $img_info[1]) / 2);
    }

    // Get updated dimensions
    $img_info = list($width, $height) = getimagesize($src);

    // Copy image to blank canvas
    if (!imagecopy($blank_img, $new_img, $padd_x, $padd_y, 0, 0, $img_info[0], $img_info[1])) {
        return false;
    }

    // Apply overlay if defined
    if ($overlay !== null) {
        if (!imagecopy($blank_img, $overlay, 0, 0, 0, 0, 640, 480)) {
            return false;
        }
    }

    // Save image as png
    if (!imagepng($blank_img, $destination)) {
        return false;
    }

    // Cleanup
    imagedestroy($new_img);

    /* CREATE THUMBNAIL IMAGE */
    /*------------------------*/
    $dir = '../assets/uploads/thumbnails/';
    $tn_file = $dir.'tn_'.basename($destination);

    // Check if directory exists already
    if (!file_exists($dir)) {
        if (!mkdir($dir, 0777)) {
            $statusMsg .= '<p class="alert alert-danger">Unable to create directory for image thumbnails. Cannot save your image.<br />Please make sure you have rights for the directory "'.$dir.'"</p>';
            $response = array('status' => false, 'statusMsg' => $statusMsg);

            return false;
        }
    }
    // Check if file exists already
    if (file_exists($tn_file)) {
        $statusMsg .= '<p class="alert alert-warning">The file you want to upload already exists. "'.$tn_file.'"</p>';
        $response = array('status' => false, 'statusMsg' => $statusMsg);

        return false;
    }

    // Scale image to 320 width
    $new_img = imagescale($blank_img, 320);

    // Cleanup
    imagedestroy($blank_img);

    // Create blank image object
    $blank_img = imagecreatetruecolor(320, 240);

    // Make the background transparent
    $transparent_blk = imagecolorallocatealpha($blank_img, 0, 0, 0, 127);
    imagecolortransparent($blank_img, $transparent_blk);

    // Copy image to blank canvas
    if (!imagecopy($blank_img, $new_img, 0, 0, 0, 0, 320, 240)) {
        return false;
    }

    // Save thumbnail image as png
    if (!imagepng($blank_img, $tn_file)) {
        unlink($destination);

        return false;
    }

    // Cleanup
    imagedestroy($new_img);
    imagedestroy($blank_img);

    return true;
}
