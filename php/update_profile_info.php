<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../vendor/autoload.php';

use GraphAware\Neo4j\Client\ClientBuilder;

$statusMsg = '';

if (isset($_SESSION['logged_on_user']) && $_POST['submit'] === '1') {
    try {
        $user = $_SESSION['logged_on_user'];

        // Initialise to current username
        $new_uname = $user['username'];

        // Set up DB connection
        $client = ClientBuilder::create()->addConnection('default', 'http://neo4j:123456@localhost:7474')->build();
        $stack = $client->stack();

        // Push each updated field to the stack and then execute all at once
        // FIRSTNAME
        if (isset($_POST['firstname']) && strlen($_POST['firstname']) && ($_POST['firstname'] !== $user['firstname'])) {
            $stack->push('MATCH (u:User {username: {uname}}) SET u.firstname = {new_fname};',
                                ['uname' => $user['username'], 'new_fname' => $_POST['firstname']], 's_firstname');
            $statusMsg .= '<p class="alert alert-success">First name updated.</p>';
        }

        // LASTNAME
        if (isset($_POST['lastname']) && strlen($_POST['lastname']) && ($_POST['lastname'] !== $user['lastname'])) {
            $stack->push('MATCH (u:User {username: {uname}}) SET u.lastname = {new_lname};',
                                ['uname' => $user['username'], 'new_lname' => $_POST['lastname']], 's_lastname');
            $statusMsg .= '<p class="alert alert-success">Last name updated.</p>';
        }

        // USERNAME
        if (isset($_POST['username']) && strlen($_POST['username']) && ($_POST['username'] !== $user['username'])) {
            $stack->push('MATCH (u:User {username: {uname}}) SET u.username = {new_uname};',
                                ['uname' => $user['username'], 'new_uname' => $_POST['username']], 's_username');
            $statusMsg .= '<p class="alert alert-success">User name updated.</p>';

            // Update $_SESSION later
            $new_uname = $_POST['username'];
        }

        // EMAIL ADDRESS
        if (isset($_POST['email']) && strlen($_POST['email'])) {
            $stack->push('MATCH (u:User {username: {uname}}) SET u.email = {new_email};',
                                ['uname' => $user['username'], 'new_email' => $_POST['email']], 's_email');
            $statusMsg .= '<p class="alert alert-success">Email Address updated.</p>';
        }

        // PASSWORD
        if (isset($_POST['password']) && strlen($_POST['password'])) {
            $stack->push('MATCH (u:User {username: {uname}}) SET u.password = {new_pwd};',
                                ['uname' => $user['username'], 'new_pwd' => $_POST['password']], 's_pwd');
            $statusMsg .= '<p class="alert alert-success">User password updated.</p>';
        }

        // GENDER
        if (isset($_POST['gender']) && strlen($_POST['gender'])) {
            if (isset($user['gender']) && $_POST['gender'] !== $user['gender']) {
                $stack->push('MATCH (u:User {username: {uname}}) SET u.gender = {new_gender};',
                        ['uname' => $user['username'], 'new_gender' => $_POST['gender']], 's_gender');
                $statusMsg .= '<p class="alert alert-success">Gender updated.</p>';
            }
        }

        // SEXUAL PREFERENCE - LOGIC
            $curr = [];
            // Check if there is already a value for the users sex_pref
            if (isset($user['sex_pref'])) {
                // Creates array with sexual preference
                $curr = explode(',', $user['sex_pref']);
            }

            // Check if sex_pref is not already in the list
            if (isset($_POST['sex_pref_m']) && !in_array('men', $curr, true)) {
                // Add it to the array
                array_push($curr, 'men');
                $statusMsg .= '<p class="alert alert-success">Sexual Preference updated.</p>';
            } elseif (!isset($_POST['sex_pref_m']) && in_array('men', $curr, true)) {
                // remove it from array
                array_splice($curr, array_search('men', $curr), 1);
                $statusMsg .= '<p class="alert alert-success">Sexual Preference updated.</p>';
            }

        if (isset($_POST['sex_pref_f']) && !in_array('women', $curr, true)) {
            // Add it to the array
                    array_push($curr, 'women');
            $statusMsg .= '<p class="alert alert-success">Sexual Preference updated.</p>';
        } elseif (!isset($_POST['sex_pref_f']) && in_array('women', $curr, true)) {
            // remove it from array
                array_splice($curr, array_search('women', $curr), 1);
            $statusMsg .= '<p class="alert alert-success">Sexual Preference updated.</p>';
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

        // BIOGRAPHY
        if (isset($_POST['bio']) && strlen($_POST['bio'])) {
            $stack->push('MATCH (u:User {username: {uname}}) SET u.bio = {new_bio};',
                                ['uname' => $user['username'], 'new_bio' => $_POST['bio']], 's_bio');
            $statusMsg .= '<p class="alert alert-success">Bio updated.</p>';
        }

        // TAGS
        if (isset($_POST['tags']) && strlen($_POST['tags'])) {
            $stack->push('MATCH (u:User {username: {uname}}) SET u.tags = {new_tags};',
                                ['uname' => $user['username'], 'new_tags' => $_POST['tags']], 's_tags');
            $statusMsg .= '<p class="alert alert-success">Tags updated.</p>';
        }

        // PICTURES
        if (isset($_POST['pictures']) && strlen($_POST['pictures'])) {
            $stack->push('MATCH (u:User {username: {uname}}) SET u.pictures = {new_pictures};',
                                ['uname' => $user['username'], 'new_pictures' => $_POST['pictures']], 's_pictures');
            $statusMsg .= '<p class="alert alert-success">Pictures updated.</p>';
        }

        // LOCATION DETAILS
            // LATITUDE
            if (isset($_POST['latitude']) && strlen($_POST['latitude'])) {
                $stack->push('MATCH (u:User {username: {uname}}) SET u.latitude = {new_latitude};',
                                    ['uname' => $user['username'], 'new_latitude' => $_POST['latitude']], 's_latitude');
                $statusMsg .= '<p class="alert alert-success">Latitude updated.</p>';
            }

            // LONGITUDE
            if (isset($_POST['longitude']) && strlen($_POST['longitude'])) {
                $stack->push('MATCH (u:User {username: {uname}}) SET u.longitude = {new_longitude};',
                                    ['uname' => $user['username'], 'new_longitude' => $_POST['longitude']], 's_longitude');
                $statusMsg .= '<p class="alert alert-success">Longitude updated.</p>';
            }

        // Get updated user info
        $stack->push('MATCH (u:User {username: {uname}}) RETURN ID(u) as uid, u AS user;',
                        ['uname' => $new_uname], 's_user_update');

        // Run query stack
        $results = $client->runStack($stack);

        // Update $_SESSION with updated info from the DB
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
            if (!array_key_exists($key, $user)) {
                $flag = 0;
            }
        }
        $user['profile_complete'] = $flag;

        // Update session
        $_SESSION['logged_on_user'] = $user;

        // Build response
        $response = array('status' => true, 'statusMsg' => $statusMsg, 'user' => $user);
    } catch (Exception $e) {

        // Log error
        error_log($e, 3, dirname(__DIR__).'/log/errors.log');

        // Build response
        $response = array('status' => false,
                        'statusMsg' => "<p class=\"alert alert-danger\"><b><u>Error Message :</u></b><br /> '.$e->getMessage().' <br /><br /> <b><u>For error details, check :</u></b><br /> ".dirname(__DIR__).'/log/errors.log'.'</p>', );
    }
} else {
    // Build response
    $response = array('status' => false, 'statusMsg' => '<p class="alert alert-danger">Invalid Request</p>');
}

// JSON encode `response` and echo the JSON string
echo json_encode($response);
