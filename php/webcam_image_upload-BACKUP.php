<?php

session_start();

$statusMsg = '';

if (!$_SESSION['logged_on_user']) {
    $statusMsg .= '<p class="danger">Please log in to take photos.</p>';
    $response = array('status' => false, 'statusMsg' => $statusMsg);
} else {
    if (isset($_POST['submit']) && isset($_POST['image']) && isset($_POST['overlay'])) {
        include '../config/database.php';

        $dir = '../uploads/';
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

        $img = $_POST['image'];
        $title = 'ImageTitleHere';
        $uid = $_SESSION['logged_on_user']['id'];

        $img = str_replace('data:image/png;base64,', '', $img);
        $img = str_replace(' ', '+', $img);
        $data = base64_decode($img);

        if ($overlay = imagecreatefrompng('../'.$_POST['overlay'])) {
            if ($im = imagecreatefromstring($data)) {
                if (imagealphablending($im, true) && imagesavealpha($im, true)) {
                    if (imagealphablending($overlay, true) && imagesavealpha($overlay, true)) {
                        if (imagecopy($im, $overlay, 0, 0, 0, 0, 480, 360)) {
                            if (imagepng($im, $file)) {
                                try {
                                    $dbname = 'camagru';
                                    $conn = new PDO("$DB_DSN;dbname=$dbname", $DB_USER, $DB_PASSWORD);
                                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                                    $sql = $conn->prepare('INSERT INTO `images`(`userid`, `title`, `path`) VALUES (:uid, :title, :imgpath)');

                                    $sql->execute(['uid' => $_SESSION['logged_on_user']['id'], 'title' => $title, 'imgpath' => $file]);

                                    //$response = array('status' => true, 'statusMsg' => 'Made it here');
                                    $statusMsg .= '<p class="success">New image uploaded.</p>';
                                    $response = array('status' => true, 'statusMsg' => $statusMsg);
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
        $statusMsg .= '<p class="danger">The form data was not received. Something weird has happened. . .</p>';
        $response = array('status' => false, 'statusMsg' => $statusMsg);
    }
}
echo json_encode($response);
