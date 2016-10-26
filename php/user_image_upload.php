<?php

session_start();
include '../config/database.php';

if (!isset($_SESSION['logged_on_user'])) {
    $response = array('status' => false, 'statusMsg' => '<p class="danger">Please log in to upload an image.</p>');
    die(json_encode($response));
}
// Check if image file is a actual image or fake image
if (isset($_POST['submit']) && $_POST['submit'] === '1') {
    $title = 'No Title Given';

    if (isset($_POST['imgTitle'])) {
        $title = $_POST['imgTitle'];
    }
    if ($_POST['uploadStatus'] === 'initial_upload') {
        $dir = '../uploads/';
        $imageFileType = pathinfo($_FILES['userfile']['name'], PATHINFO_EXTENSION);
        $file = $dir.uniqid().'.'.$imageFileType;

        $check = getimagesize($_FILES['userfile']['tmp_name']);
        if ($check === false) {
            $response = array('status' => false, 'statusMsg' => '<p class="warning">Select a valid image to upload. <br />E.G:   JPG, JPEG, PNG or GIF');
            die(json_encode($response));
        }

        // Check if directory exists
        if (!file_exists($dir)) {
            if (!mkdir($dir, 0777)) {
                $response = array('status' => false, 'statusMsg' => '<p class="danger">Unable to create directory for images. Cannot save your image.<br />Please make sure you have rights for the directory " '.$dir.' "</p>');
                die(json_encode($response));
            }
        }
        if (file_exists($file)) {
            $response = array('status' => false, 'statusMsg' => "<p class=\"warning\">The file you want to upload already exists. '".$file."'</p>");
            die(json_encode($response));
        }

        // Check file size not bigger than 10mb or 0 bytes
        if ($_FILES['userfile']['size'] > 10000000) {
            $response = array('status' => false, 'statusMsg' => "<p class=\"warning\">Your file is too large. Maximum size of '10mb' allowed.</p>");
            die(json_encode($response));
        } elseif ($_FILES['userfile']['size'] == 0) {
            $response = array('status' => false, 'statusMsg' => '<p class="warning">Your file has no size. Please select a valid image.</p>');
            die(json_encode($response));
        }

        // Allow certain file formats
        if ($uploadOk && $imageFileType != 'jpg' && $imageFileType != 'png' &&
          $imageFileType != 'jpeg' && $imageFileType != 'gif') {
            $response = array('status' => false, 'statusMsg' => '<p class="warning">Only JPG, JPEG, PNG & GIF files are allowed.</p>');
            die(json_encode($response));
        }

    //        if (move_uploaded_file($_FILES['userfile']['tmp_name'], $file)) {
    //        $newImg = imagecreatefromstring(file_get_contents($_FILES['userfile']['tmp_name']));
    //        $newImg = imagescale(imagecreatefromstring(file_get_contents($_FILES['userfile']['tmp_name'])), 640);

            if (imagepng(imagescale(imagecreatefromstring(file_get_contents($_FILES['userfile']['tmp_name'])), 640), $file)) {
                $statusMsg = '<p class="info">Image uploaded. Now select an overlay!</p>';
                $response = array('status' => true, 'statusMsg' => $statusMsg, 'newFile' => 'uploads/'.basename($file), 'imgTitle' => $title);
            } else {
                $response = array('status' => false, 'statusMsg' => '<p class="warning">Oops! There was an error resizing and saving the file..</p>');
            }
    }/* --- END OF INITIAL_UPLOAD --- */
        elseif ($_POST['uploadStatus'] === 'overwrite_with_new') {
            if (isset($_POST['imgSrc']) && isset($_POST['overlay'])) {
                $file = $_POST['imgSrc'];
                if ($overlay = imagecreatefrompng('../'.$_POST['overlay'])) {
                    if ($im = imagecreatefrompng($file)) {
                        if (imagealphablending($im, true) && imagesavealpha($im, true)) {
                            if (imagealphablending($overlay, true) && imagesavealpha($overlay, true)) {
                                if (imagecopy($im, $overlay, 0, 0, 0, 0, 640, 480)) {
                                    if (imagepng($im, '../uploads/'.basename($file))) {
                                        try {
                                            $dbname = 'camagru';
                                            $conn = new PDO("$DB_DSN;dbname=$dbname", $DB_USER, $DB_PASSWORD);
                                            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                                            $sql = $conn->prepare('INSERT INTO `images`(`userid`, `title`, `path`) VALUES (:uid, :title, :imgpath)');

                                            $sql->execute(['uid' => $_SESSION['logged_on_user']['id'], 'title' => $title, 'imgpath' => $file]);

                                            //$response = array('status' => true, 'statusMsg' => 'Made it here');
                                            $statusMsg .= '<p class="success">New image uploaded.</p>';
                                            $response = array('status' => true, 'statusMsg' => $statusMsg, 'newFile' => 'uploads/'.basename($file), 'imgTitle' => $title);
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
                    $statusMsg .= '<p class="danger">Could not create the image object from overlay image. Please try again.<br />If the problem persists, please contact the site administrator.</p>';
                    $response = array('status' => false, 'statusMsg' => $statusMsg);
                }
            } else {
                $response = array('status' => false, 'statusMsg' => '<p class="danger">Could not find image or overlay image. Your image was not sucessfully added!</p>');
            }
        } else {
            $response = array('status' => false, 'statusMsg' => '<p class="danger">Unrecognised uploadStatus message</p>');
        }
} else {
    $response = array('status' => false, 'statusMsg' => '<p class="danger">Could not find data sent via POST method</p>');
}
    echo json_encode($response);

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
