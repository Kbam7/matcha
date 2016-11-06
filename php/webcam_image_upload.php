<?php

session_start();

require_once '../vendor/autoload.php';
use GraphAware\Neo4j\Client\ClientBuilder;

$statusMsg = '';

if (!$_SESSION['logged_on_user']) {
    $statusMsg .= '<p class="danger">Please log in to take photos.</p>';
    $response = array('status' => false, 'statusMsg' => $statusMsg);
} else {
    if (isset($_POST['submit']) && isset($_POST['image']) && isset($_POST['overlay'])) {
        $user = $_SESSION['logged_on_user'];

        $dir = '../assets/uploads/';
        $file = $dir.uniqid().'.png';

        if (!file_exists($dir)) {
            if (!mkdir($dir, 0777)) {
                $statusMsg .= '<p class="danger">Unable to create directory for images. Cannot save your image.<br />Please make sure you have rights for the directory " '.$dir.' "</p>';
                $response = array('status' => false, 'statusMsg' => $statusMsg);
                die(json_encode($response));
            }
        }

        if (file_exists($file)) {
            $statusMsg .= "<p class=\"warning\">The file you want to upload already exists. '".$file."'</p>";
            $response = array('status' => false, 'statusMsg' => $statusMsg);
            die(json_encode($response));
        }

        // Base64 encoded image from webcam
        $img = $_POST['image'];
        // A title for the image
        $title = 'ImageTitleHere';
        // A short caption
        $img_desc = 'Image caption/description';

        // Remove excess text
        $img = str_replace('data:image/png;base64,', '', $img);
        $img = str_replace(' ', '+', $img);

        // Decode string
        $data = base64_decode($img);

        // Get overlay image path from $_POST
        if ($overlay = imagecreatefrompng($_POST['overlay'])) {

            // Create image obj from base64 decoded data
            if ($im = imagecreatefromstring($data)) {

                // Alpha layer setup for overlay and image
                if (imagealphablending($im, true) && imagesavealpha($im, true)) {
                    if (imagealphablending($overlay, true) && imagesavealpha($overlay, true)) {

                        // Copy overlay onto image staring from top left corner to bottom right corners
                        if (imagecopy($im, $overlay, 0, 0, 0, 0, 640, 480)) {

                            // Save the merged image object as a png image
                            if (imagepng($im, $file)) {

                                // Connect to DB, add image details to DB
                                try {
                                    $client = ClientBuilder::create()->addConnection('default', 'http://neo4j:123456@localhost:7474')->build();

                                    $stack = $client->stack();

                                        // Create new image and relationship for user
                                        $stack->push('MATCH (u:User {username:{uname}}) MERGE (u)-[:UPLOADED {timestamp:{time}}]->(img:Image {timestamp:{time}, title:{title}, description:{desc}, path:{path}}) RETURN img',
                                                    ['uname' => $user['username'], 'time' => time(), 'title' => $title, 'desc' => $img_desc, 'path' => $file],
                                                    'new_img');

                                    $results = $client->runStack($stack);

                                    $record = $results->get('new_img')->getRecord();
                                    $img = $record->get('img')->values();

/*
                                    $dbname = 'camagru';
                                    $conn = new PDO("$DB_DSN;dbname=$dbname", $DB_USER, $DB_PASSWORD);
                                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                                    $sql = $conn->prepare('INSERT INTO `images`(`userid`, `title`, `path`) VALUES (:uid, :title, :imgpath)');

                                    $sql->execute(['uid' => $_SESSION['logged_on_user']['username'], 'title' => $title, 'imgpath' => $file]);
*/
                                    //$response = array('status' => true, 'statusMsg' => 'Made it here');
                                    $statusMsg .= '<p class="success">New image uploaded. -- </p>';

                                    // Returns the title and path to the new image to be displayed on the front end
                                    $response = array('status' => true, 'statusMsg' => $statusMsg, 'image' => $img);
                                } catch (PDOException $e) {
                                    $statusMsg .= '<p class="danger"><b><u>Error Message :</u></b><br /> '.$e.' <br /><br /> <b><u>For error details, check :</u></b><br /> '.dirname(__DIR__).'/log/errors.log</p>';
                                    $response = array('status' => false, 'statusMsg' => $statusMsg);
                                    error_log($e, 3, dirname(__DIR__).'/log/errors.log');
                                }
                                $conn = null;
                            } else {
                                $statusMsg .= '<p class="danger">Could not save the image after merging. Please try again.<br />If the problem persists, please contact the site administrator.</p>';
                                $response = array('status' => false, 'statusMsg' => $statusMsg);
                            }
                        } else {
                            $statusMsg .= '<p class="danger">Could not copy images(merging problem). Please try again.<br />If the problem persists, please contact the site administrator.</p>';
                            $response = array('status' => false, 'statusMsg' => $statusMsg);
                        }
                    } else {
                        $statusMsg .= '<p class="danger">Could not set blend alpha or save alpha for overlay image. Please try again.<br />If the problem persists, please contact the site administrator.</p>';
                        $response = array('status' => false, 'statusMsg' => $statusMsg);
                    }
                } else {
                    $statusMsg .= '<p class="danger">Could not set blend alpha or save alpha for webcam image. Please try again.<br />If the problem persists, please contact the site administrator.</p>';
                    $response = array('status' => false, 'statusMsg' => $statusMsg);
                }
                imagedestroy($im);
                imagedestroy($overlay);
            } else {
                $statusMsg .= '<p class="danger">Could not create the image object from base64 data(webcam image). Please try again.<br />If the problem persists, please contact the site administrator.</p>';
                $response = array('status' => false, 'statusMsg' => $statusMsg);
            }
        } else {
            $statusMsg .= '<p class="danger">Could not create the image object from overlay image. Please try again.<br />If the problem persists, please contact the site administrator. -- '.$_POST['overlay'].'</p>';
            $response = array('status' => false, 'statusMsg' => $statusMsg);
        }
    } else {
        $statusMsg .= '<p class="danger">The form data was not received. Something weird has happened. . .</p>';
        $response = array('status' => false, 'statusMsg' => $statusMsg);
    }
}
echo json_encode($response);
