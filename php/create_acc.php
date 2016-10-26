<?php

session_start();

include '../config/database.php';

$statusMsg = '';

if ($_POST['submit'] === '1' && $_POST['fname'] && $_POST['lname'] && $_POST['uname'] && $_POST['email'] && $_POST['passwd']) {
    try {
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $uname = $_POST['uname'];
        $email = $_POST['email'];
        $passwd = hash('whirlpool', $_POST['passwd']);

        $dbname = 'camagru';
        $conn = new PDO("$DB_DSN;dbname=$dbname", $DB_USER, $DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if (validNewUser($conn, $uname, $email) == true) {

            $uniqueHash = md5(uniqid());

            $sql = $conn->prepare('INSERT INTO `users` (`hash`, `firstname`, `lastname`, `username`, `email`, `password`) VALUES (:hash, :fname, :lname, :uname, :email, :passwd);');
            $sql->execute(['hash' => $uniqueHash, 'fname' => $fname, 'lname' => $lname, 'uname' => $uname, 'email' => $email, 'passwd' => $passwd]);

            // send email to user
            $subject = 'Signup | Verification'; // Give the email a subject
            $message = "

                Hey ".$fname."".$lname.",

                Thanks for signing up!
                Your account has been created, you can login with the following credentials after you have activated your account by pressing the url below.

                ------------------------

                Username:   ".$uname."
                Email   :   ".$email."

                ------------------------

                Please click this link to activate your account:
                http://localhost:8080/kbamping/camagru/verify.php?email=".$email."&hash=".$uniqueHash."

            "; // Our message above including the link

            $headers = 'From:noreply@camagru.co.za' . "\r\n"; // Set from headers
    //        $headers .= 'Content-type: text/html' . "\r\n"; // Set from headers
            mail($email, $subject, $message, $headers); // Send our email



            $statusMsg .= '<p class="success">Yay! You have been sent a validation email, please check your email for the verification link.</p>';
            $response = array('status' => true, 'statusMsg' => $statusMsg);
        } else {
            $response = array('status' => false, 'statusMsg' => $statusMsg);
        }
        echo json_encode($response);
    } catch (PDOException $e) {
        error_log($e, 3, dirname(__DIR__).'/log/errors.log');
        $response = array('status' => false,
                        'statusMsg' => "<p class=\"danger\"><b><u>Error Message :</u></b><br /> '.$e.' <br /><br /> <b><u>For error details, check :</u></b><br /> ".dirname(__DIR__).'/log/errors.log'.'</p>', );

        echo json_encode($response);
    }
    $conn = null;
} else {
    $response = array('status' => false, 'statusMsg' => '<p class="danger">Invalid data sent via POST method</p>');
    echo json_encode($response);
}

function validNewUser($conn, $uname, $email)
{
    global $statusMsg;

    $flag = true;
    $results = $conn->query('SELECT `username`, `email` FROM `users`;');

    while ($row = $results->fetch(PDO::FETCH_ASSOC)) {
        if (strcasecmp($row['username'], $uname) == 0) {
            $statusMsg .= '<p class="warning">The username "'.$uname.'" is already in use!</p>';
            $flag = false;
        }
        if (strcasecmp($row['email'], $email) == 0) {
            $statusMsg .= '<p class="warning">The email address "'.$email.'" is already in use!</p>';
            $flag = false;
        }
        if ($flag == false) {
            break;
        }
    }

    return $flag;
}
