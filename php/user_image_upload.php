<?php

session_start();

require_once '../vendor/autoload.php';
use GraphAware\Neo4j\Client\ClientBuilder;

if (!isset($_SESSION['logged_on_user'])) {
    $response = array('status' => false, 'statusMsg' => '<p class="alert alert-danger">Please log in to upload an image.</p>');
    die(json_encode($response));
}

if (isset($_POST['submit']) && $_POST['submit'] === '1') {
    if (user_image_count() > 5) {
        $response = array('status' => false, 'statusMsg' => '<p class="alert alert-warning">Maximum amount of images reached. Please delete some images.');
        die(json_encode($response));
    }

    $title = 'No Title Given';

    if (isset($_POST['imgTitle'])) {
        $title = $_POST['imgTitle'];
    }
    if ($_POST['uploadStatus'] === 'initial_upload') {
        $imageFileType = pathinfo($_FILES['userfile']['name'], PATHINFO_EXTENSION);
        $dir = '../assets/uploads/';
        $file = $dir.uniqid().'.'.$imageFileType;

        $check = getimagesize($_FILES['userfile']['tmp_name']);
        if ($check === false) {
            $response = array('status' => false, 'statusMsg' => '<p class="alert alert-warning">Select a valid image to upload. <br />E.G:   JPG, JPEG, PNG or GIF');
            die(json_encode($response));
        }

        // Check if directory exists
        if (!file_exists($dir)) {
            if (!mkdir($dir, 0777)) {
                $response = array('status' => false, 'statusMsg' => '<p class="alert alert-danger">Unable to create directory for images. Cannot save your image.<br />Please make sure you have rights for the directory " '.$dir.' "</p>');
                die(json_encode($response));
            }
        }
        if (file_exists($file)) {
            $response = array('status' => false, 'statusMsg' => "<p class=\"alert alert-warning\">The file you want to upload already exists. '".$file."'</p>");
            die(json_encode($response));
        }

        // Check file size not bigger than 10mb or 0 bytes
        if ($_FILES['userfile']['size'] > 10000000) {
            $response = array('status' => false, 'statusMsg' => "<p class=\"alert alert-warning\">Your file is too large. Maximum size of '10mb' allowed.</p>");
            die(json_encode($response));
        } elseif ($_FILES['userfile']['size'] == 0) {
            $response = array('status' => false, 'statusMsg' => '<p class="alert alert-warning">Your file has no size. Please select a valid image.</p>');
            die(json_encode($response));
        }

        // Allow certain file formats
        if ($uploadOk && $imageFileType != 'jpg' && $imageFileType != 'png' &&
          $imageFileType != 'jpeg' && $imageFileType != 'gif') {
            $response = array('status' => false, 'statusMsg' => '<p class="alert alert-warning">Only JPG, JPEG, PNG & GIF files are allowed.</p>');
            die(json_encode($response));
        }

    //        if (move_uploaded_file($_FILES['userfile']['tmp_name'], $file)) {
    //        $newImg = imagecreatefromstring(file_get_contents($_FILES['userfile']['tmp_name']));
    //        $newImg = imagescale(imagecreatefromstring(file_get_contents($_FILES['userfile']['tmp_name'])), 640);

            if (imagepng(imagescale(imagecreatefromstring(file_get_contents($_FILES['userfile']['tmp_name'])), 640), $file)) {
                $statusMsg = '<p class="alert alert-info">Image uploaded. Now select an overlay!</p>';
                $response = array('status' => true, 'statusMsg' => $statusMsg, 'newFile' => '/matcha/assets/uploads/'.basename($file), 'imgTitle' => $title);
            } else {
                $response = array('status' => false, 'statusMsg' => '<p class="alert alert-warning">Oops! There was an error resizing and saving the file..</p>');
            }
    }/* --- END OF INITIAL_UPLOAD --- */
        elseif ($_POST['uploadStatus'] === 'overwrite_with_new') {
            $user = $_SESSION['logged_on_user'];

            if (isset($_POST['imgSrc']) && isset($_POST['overlay'])) {
                $file = $_POST['imgSrc'];
                if ($overlay = imagecreatefrompng($_POST['overlay'])) {
                    if ($im = imagecreatefrompng($file)) {
                        if (imagealphablending($im, true) && imagesavealpha($im, true)) {
                            if (imagealphablending($overlay, true) && imagesavealpha($overlay, true)) {
                                if (imagecopy($im, $overlay, 0, 0, 0, 0, 640, 480)) {
                                    if (imagepng($im, '../assets/uploads/'.basename($file))) {
                                        try {
                                            $client = ClientBuilder::create()->addConnection('default', 'http://neo4j:123456@localhost:7474')->build();

                                            $stack = $client->stack();

                                            // Create new image and relationship for user
                                            $stack->push('MATCH (u:User {username:{uname}})'
                                                        .'MERGE (u)-[:UPLOADED {timestamp:{time}}]->'
                                                                    .'(img:Image {timestamp:{time}, title:{title}, description:{desc}, filename:{filename}})'
                                                        .'RETURN img',
                                                        ['uname' => $user['username'], 'time' => time(), 'title' => '<Add title here>',
                                                            'desc' => '<Add description here>', 'filename' => basename($file), ],
                                                        'new_img');

                                            $results = $client->runStack($stack);

                                            $record = $results->get('new_img')->getRecord();
                                            $img = $record->get('img')->values();

                                            $statusMsg .= '<p class="alert alert-success">New image uploaded.</p>';

                                            // Returns the title and path to the new image to be displayed on the front end
                                            $response = array('status' => true, 'statusMsg' => $statusMsg, 'image' => $img);
                                        } catch (Exception $e) {
                                            $statusMsg .= '<p class="alert alert-danger"><b><u>Error Message :</u></b><br /> '.$e.' <br /><br /> <b><u>For error details, check :</u></b><br /> '.dirname(__DIR__).'/log/errors.log</p>';
                                            $response = array('status' => false, 'statusMsg' => $statusMsg);
                                            error_log($e, 3, dirname(__DIR__).'/log/errors.log');
                                        }
                                        $conn = null;
                                    } else {
                                        $statusMsg .= '<p class="alert alert-danger">Could not save the image after merging. Please try again.<br />If the problem persists, please contact the site administrator.</p>';
                                        $response = array('status' => false, 'statusMsg' => $statusMsg);
                                    }
                                } else {
                                    $statusMsg .= '<p class="alert alert-danger">Could not copy images(merging problem). Please try again.<br />If the problem persists, please contact the site administrator.</p>';
                                    $response = array('status' => false, 'statusMsg' => $statusMsg);
                                }
                            } else {
                                $statusMsg .= '<p class="alert alert-danger">Could not set blend alpha or save alpha for overlay image. Please try again.<br />If the problem persists, please contact the site administrator.</p>';
                                $response = array('status' => false, 'statusMsg' => $statusMsg);
                            }
                        } else {
                            $statusMsg .= '<p class="alert alert-danger">Could not set blend alpha or save alpha for webcam image. Please try again.<br />If the problem persists, please contact the site administrator.</p>';
                            $response = array('status' => false, 'statusMsg' => $statusMsg);
                        }
                        imagedestroy($im);
                        imagedestroy($overlay);
                    } else {
                        $statusMsg .= '<p class="alert alert-danger">Could not create the image object from base64 data(webcam image). Please try again.<br />If the problem persists, please contact the site administrator.</p>';
                        $response = array('status' => false, 'statusMsg' => $statusMsg);
                    }
                } else {
                    $statusMsg .= '<p class="alert alert-danger">Could not create the image object from overlay image. Please try again.<br />If the problem persists, please contact the site administrator.</p>';
                    $response = array('status' => false, 'statusMsg' => $statusMsg);
                }
            } else {
                $response = array('status' => false, 'statusMsg' => '<p class="alert alert-danger">Could not find image or overlay image. Your image was not sucessfully added!</p>');
            }
        } else {
            $response = array('status' => false, 'statusMsg' => '<p class="alert alert-danger">Unrecognised uploadStatus message</p>');
        }
} else {
    $response = array('status' => false, 'statusMsg' => '<p class="alert alert-danger">Could not find data sent via POST method</p>');
}
    echo json_encode($response);

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

/*
function makeImageObj($url, $fileType)
{
    $imgObj = null;

    if ($fileType === 'jpg' || $fileType === 'jpeg') {
        $imgObj = imagecreatefromjpeg($url);
    } elseif ($fileType === 'png') {
        $imgObj = imagecreatefrompng($url);
    } elseif ($fileType === 'gif') {
        $imgObj = imagecreatefromgif($url);
    }

    return $imgObj;
}
*/
