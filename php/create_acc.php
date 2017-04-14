<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../vendor/autoload.php';

use GraphAware\Neo4j\Client\ClientBuilder;

$statusMsg = '';

if ($_POST['submit'] === '1' && $_POST['fname'] && $_POST['lname'] && $_POST['uname'] && $_POST['user_age'] && $_POST['email'] && $_POST['passwd']) {
    try {
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $uname = $_POST['uname'];
        $age = $_POST['user_age'];
        $email = $_POST['email'];
        $passwd = hash('whirlpool', $_POST['passwd']);

        $client = ClientBuilder::create()->addConnection('default', 'http://neo4j:123456@localhost:7474')->build();

        if (validNewUser($client, $uname, $email) == true) {
            $uniqueHash = md5(uniqid());

            // Create the new user
            $result = $client->run('CREATE (n:User) SET n += {details}',
                ['details' => ['active' => 0, 'hash' => $uniqueHash,
                                'firstname' => $fname, 'lastname' => $lname,
                                'username' => $uname, 'password' => $passwd,
                                'email' => $email, 'profile_complete' => 0,
                                'fame' => 0, 'age' => $age, ]]
            );

            // send email to user
            $subject = 'Signup | Verification'; // Give the email a subject
            $message = '

                Hey '.$fname.' '.$lname.',

                Thanks for signing up!
                Your account has been created, you can login with the
                following credentials after you have activated your
                account by visiting the url below.

                ------------------------

                Username:   '.$uname.'
                Email   :   '.$email.'
                Password:   *your-password*

                ------------------------

                Please click this link to activate your account:
                http://localhost:8080/matcha/views/verify.php?email='.$email.'&hash='.$uniqueHash.'

            ';

            $headers = 'From:noreply@matcha.co.za'."\r\n"; // Set from headers
    //        $headers .= 'Content-type: text/html' . "\r\n"; // Set from headers
            mail($email, $subject, $message, $headers);

            $statusMsg .= '<div class="alert alert-success">Yay! You have been sent a validation email, please check your email for the verification link.</div>';
            $response = array('status' => true, 'statusMsg' => $statusMsg);
        } else {
            $response = array('status' => false, 'statusMsg' => $statusMsg);
        }
        echo json_encode($response);
    } catch (Exception $e) {
        error_log($e, 3, dirname(__DIR__).'/log/errors.log');
        $response = array('status' => false,
                        'statusMsg' => "<div class=\"alert alert-danger\"><b><u>Error Message :</u></b><br /> '.$e->getMessage().' <br /><br /> <b><u>For error details, check :</u></b><br /> ".dirname(__DIR__).'/log/errors.log'.'</div>', );

        echo json_encode($response);
    }
    $conn = null;
} else {
    $response = array('status' => false, 'statusMsg' => '<div class="alert alert-danger">Invalid data sent via POST method</div>');
    echo json_encode($response);
}

function validNewUser($client, $uname, $email)
{
    global $statusMsg;
    $flag = true;

    $stack = $client->stack();
    $stack->push('MATCH (u:User) WHERE u.username={uname} RETURN count(u) AS count', ['uname' => $uname], 'check_user');
    $stack->push('MATCH (u:User) WHERE u.email={email} RETURN count(u) AS count', ['email' => $email], 'check_email');
    $results = $client->runStack($stack);

    // Get count of results. If > 0, the user or email address already exists
    $user_ret = $results->get('check_user')->getRecord()->value('count');
    $email_ret = $results->get('check_email')->getRecord()->value('count');

    if ($user_ret) {
        $statusMsg .= '<div class="alert alert-warning">The username "'.$uname.'" is already in use!</div>';
        $flag = false;
    }

    if ($email_ret) {
        $statusMsg .= '<div class="alert alert-warning">The email address "'.$email.'" is already in use!</div>';
        $flag = false;
    }

    return $flag;
}
