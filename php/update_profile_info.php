<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../vendor/autoload.php';

use GraphAware\Neo4j\Client\ClientBuilder;

$statusMsg = '';

if (isset($_SESSION['logged_on_user'])) {
    try {
        $user = $_SESSION['logged_on_user'];

        // Set up DB connection
        $client = ClientBuilder::create()->addConnection('default', 'http://neo4j:123456@localhost:7474')->build();
        $stack = $client->stack();

        // Push each updated field to the stack and then execute all at once
        // FIRSTNAME
        if (isset($_POST['firstname']) && strlen($_POST['firstname']) && ($_POST['firstname'] !== $user['firstname'])) {
            $stack->push('MATCH (u:User {username: {uname}}) SET u.firstname = {new_fname};',
                                ['uname' => $user['username'], 'new_fname' => $_POST['firstname']], 's_firstname');
            $statusMsg .= '<div class="alert alert-success">First name updated.</div>';
        }

        // LASTNAME
        if (isset($_POST['lastname']) && strlen($_POST['lastname']) && ($_POST['lastname'] !== $user['lastname'])) {
            $stack->push('MATCH (u:User {username: {uname}}) SET u.lastname = {new_lname};',
                                ['uname' => $user['username'], 'new_lname' => $_POST['lastname']], 's_lastname');
            $statusMsg .= '<div class="alert alert-success">Last name updated.</div>';
        }

        // USERNAME
        if (isset($_POST['username']) && strlen($_POST['username']) && ($_POST['username'] !== $user['username'])) {
            $stack->push('MATCH (u:User {username: {uname}}) SET u.username = {new_uname};',
                                ['uname' => $user['username'], 'new_uname' => $_POST['username']], 's_username');
            $statusMsg .= '<div class="alert alert-success">User name updated.</div>';

            // Update $_SESSION later
            $user['username'] = $_POST['username'];
        }

        // EMAIL ADDRESS
        if (isset($_POST['email']) && strlen($_POST['email'])) {
            if ($_POST['email'] !== $user['email']) {
                // Generate hash
                $uniqueHash = md5(uniqid());
                // Set hash and new email in DB
                $stack->push('MATCH (u:User {username: {uname}}) SET u += {details};',
                                    ['uname' => $user['username'],
                                        'details' => ['hash' => $uniqueHash,
                                                        'email' => $_POST['email'],
                                                        'active' => 0,
                                                    ],
                                    ], 's_email');
                // Send validation email
                sendValidationEmail($_POST['email'], $uniqueHash);
                $statusMsg .= '<div class="alert alert-success">Email Address updated.<br />'
                            .'Check your new email address for the verification link.</div>';
            }
        }

        // PASSWORD
        if (isset($_POST['password']) && strlen($_POST['password'])
            && isset($_POST['password2']) && strlen($_POST['password2'])
                 && isset($_POST['curr_password']) && strlen($_POST['curr_password'])) {
            $tmp = hash('whirlpool', $_POST['curr_password']);
            if ($tmp !== $user['password'] || $_POST['password'] !== $_POST['password2']) {
                $statusMsg .= '<div class="alert alert-warning">User password <b>NOT</b> updated.</div>';
            } else {
                $stack->push('MATCH (u:User {username: {uname}}) SET u.password = {new_pwd};',
                                    ['uname' => $user['username'], 'new_pwd' => hash('whirlpool', $_POST['password'])], 's_pwd');
                $statusMsg .= '<div class="alert alert-success">User password updated.</div>';
            }
        }

        // GENDER
        if (isset($_POST['gender']) && strlen($_POST['gender'])) {
            if (!isset($user['gender']) || $_POST['gender'] !== $user['gender']) {
                $stack->push('MATCH (u:User {username: {uname}}) SET u.gender = {new_gender};',
                        ['uname' => $user['username'], 'new_gender' => $_POST['gender']], 's_gender');
                $statusMsg .= '<div class="alert alert-success">Gender updated.</div>';
            }
        }

        // SEXUAL PREFERENCE - LOGIC
        if (isset($_POST['sex_pref_m']) || isset($_POST['sex_pref_f'])) {
            // Set default for $curr
            $curr = [];
            // Check if there is already a value for the users sex_pref
            if (isset($user['sex_pref'])) {
                // Creates array with sexual preference
                $curr = array_filter(explode(',', $user['sex_pref']), 'ctype_graph');
            }

            // Check if sex_pref is not already in the list
            if (isset($_POST['sex_pref_m']) && !in_array('men', $curr, true)) {
                // Add it to the array
                array_push($curr, 'men');
                $statusMsg .= '<div class="alert alert-success">Sexual Preference updated.</div>';
            } elseif (!isset($_POST['sex_pref_m']) && in_array('men', $curr, true)) {
                // remove it from array
                array_splice($curr, array_search('men', $curr), 1);
                $statusMsg .= '<div class="alert alert-success">Sexual Preference updated.</div>';
            }

            if (isset($_POST['sex_pref_f']) && !in_array('women', $curr, true)) {
                // Add it to the array
                array_push($curr, 'women');
                $statusMsg .= '<div class="alert alert-success">Sexual Preference updated.</div>';
            } elseif (!isset($_POST['sex_pref_f']) && in_array('women', $curr, true)) {
                // remove it from array
                array_splice($curr, array_search('women', $curr), 1);
                $statusMsg .= '<div class="alert alert-success">Sexual Preference updated.</div>';
            }

            // Check if we need to implode the array or if we just have one value
            if (in_array('men', $curr, true) && in_array('women', $curr, true)) {
                $new_sex_pref = implode(',', $curr);
            } elseif (!in_array('men', $curr, true) && in_array('women', $curr, true)) {
                $new_sex_pref = 'women';
            } elseif (in_array('men', $curr, true) && !in_array('women', $curr, true)) {
                $new_sex_pref = 'men';
            } else {
                $new_sex_pref = '';
            }

            // SEXUAL PREFERENCE - UPDATE
            $stack->push('MATCH (u:User {username: {uname}}) SET u.sex_pref = {new_sex_pref};',
                                    ['uname' => $user['username'], 'new_sex_pref' => $new_sex_pref], 's_sex_pref');
        }

        // BIOGRAPHY
        if (isset($_POST['bio']) && strlen($_POST['bio'])) {
            // If the tags have been updated
            if (!isset($user['bio']) || $_POST['bio'] !== $user['bio']) {
                $stack->push('MATCH (u:User {username: {uname}}) SET u.bio = {new_bio};',
                                    ['uname' => $user['username'], 'new_bio' => $_POST['bio']], 's_bio');
                $statusMsg .= '<div class="alert alert-success">Bio updated.</div>';
            }
        }

        // PICTURES
        if (isset($_POST['pictures']) && strlen($_POST['pictures'])) {
            $stack->push('MATCH (u:User {username: {uname}}) SET u.pictures = {new_pictures};',
                                ['uname' => $user['username'], 'new_pictures' => $_POST['pictures']], 's_pictures');
            $statusMsg .= '<div class="alert alert-success">Pictures updated.</div>';
        }

        // LOCATION DETAILS
            // LATITUDE
            if (isset($_POST['latitude']) && strlen($_POST['latitude'])) {
                $stack->push('MATCH (u:User {username: {uname}}) SET u.latitude = {new_latitude};',
                                    ['uname' => $user['username'], 'new_latitude' => $_POST['latitude']], 's_latitude');
                $statusMsg .= '<div class="alert alert-success">Latitude updated.</div>';
            }

            // LONGITUDE
            if (isset($_POST['longitude']) && strlen($_POST['longitude'])) {
                $stack->push('MATCH (u:User {username: {uname}}) SET u.longitude = {new_longitude};',
                                    ['uname' => $user['username'], 'new_longitude' => $_POST['longitude']], 's_longitude');
                $statusMsg .= '<div class="alert alert-success">Longitude updated.</div>';
            }

        // Get updated user info
        $stack->push('MATCH (u:User {username: {uname}}) RETURN ID(u) as uid, u AS user;',
                        ['uname' => $user['username']], 's_user_update');

        // Run query stack
        $results = $client->runStack($stack);

        // Select the `s_user_update` result and get the returned record. i.e the user and uid
        $updates = $results->get('s_user_update')->getRecord();

        // Get User node values. Returns an array of property:value pairs.
        $user = $updates->get('user')->values();

        // Assign 'uid' field to `user` array
        $user['uid'] = $updates->get('uid');

        // Check if users profile is filled out enough
        // gender, interested, location and one picture
        $fields_to_check = array('gender', 'sex_pref', 'latitude', 'longitude', 'bio', 'profile_pic');
        $flag = 1;
        foreach ($fields_to_check as $key) {
            if (!array_key_exists($key, $user) || empty($user[$key])) {
                $flag = 0;
            }
            //echo $key.' '.$flag.'\n';
        }
        //print_r($user);

        // Update profile status
        $results = $client->run('MATCH (u:User {username: {uname}}) SET u.profile_complete = {value} RETURN u AS user;',
                        ['uname' => $user['username'], 'value' => $flag]);
        $updates = $results->getRecord();
        $user = $updates->get('user')->values();

        // Update session
        $_SESSION['logged_on_user'] = $user;

        // Build response
        $response = array('status' => true, 'statusMsg' => $statusMsg, 'user' => $user);
    } catch (Exception $e) {

        // Log error
        error_log($e, 3, dirname(__DIR__).'/log/errors.log');

        // Build response
        $response = array('status' => false,
                        'statusMsg' => "<div class=\"alert alert-danger\"><b><u>Error Message :</u></b><br /> '.$e->getMessage().' <br /><br /> <b><u>For error details, check :</u></b><br /> ".dirname(__DIR__).'/log/errors.log'.'</div>', );
    }
} else {
    // Build response
    $response = array('status' => false, 'statusMsg' => '<div class="alert alert-danger">Invalid Request</div>');
}

// JSON encode `response` and echo the JSON string
echo json_encode($response);

// Function to send Validation email when changing email address
function sendValidationEmail($newemail, $uniqueHash)
{
    $user = $_SESSION['logged_on_user'];

    // send email to old email
    $subject = 'Notification | Changed Email';
    $message = '

        Hey '.$user['firstname'].' '.$user['lastname'].',

        We have suspended your account for "'.$user['email'].'" due to changing your email address.
        Check your new email address\' inbox for your activation link.

        Please contact site admin if you suspect you have been hacked.

    ';

    $headers = 'From:noreply@matcha.co.za'."\r\n";
    mail($user['email'], $subject, $message, $headers);

    // send email to new email
    $message = '
        Hey '.$user['firstname'].' '.$user['lastname'].',

        We have removed your email address "'.$user['email'].'" and temporarily deactivated your account.
        To verify this new email address and reactivate your Matcha account, please follow the link below.

        Click this link to activate your account:
        http://localhost:8080/matcha/views/verify.php?email='.$newemail.'&hash='.$uniqueHash.'

        Please contact site admin if you suspect you have been hacked.
    ';

    $headers = 'From:noreply@matcha.co.za'."\r\n";
    mail($newemail, $subject, $message, $headers);
}
