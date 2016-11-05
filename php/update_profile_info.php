<?php

session_start();

require_once '../vendor/autoload.php';

use GraphAware\Neo4j\Client\ClientBuilder;

$statusMsg = '';

if (isset($_SESSION['logged_on_user']) && $_POST['submit'] === '1') {
    try {
        $user = $_SESSION['logged_on_user'];

        // Initialise to current username
        $new_uname = $user['username'];

        // Set up DB connection
        $client = ClientBuilder::create()
            ->addConnection('default', 'http://neo4j:123456@localhost:7474')
            ->build();

        $stack = $client->stack();

        if ($_POST['firstname'] && strlen($_POST['firstname']) && ($_POST['firstname'] !== $user['firstname'])) {
            $stack->push('MATCH (u:User {username: {uname}}) SET u.firstname = {new_fname};',
                                ['uname' => $user['username'], 'new_fname' => $_POST['firstname']], 's_firstname');
            $statusMsg .= '<p class="alert alert-success">First name updated.</p>';
        }

        if ($_POST['lastname'] && strlen($_POST['lastname']) && ($_POST['lastname'] !== $user['lastname'])) {
            $stack->push('MATCH (u:User {username: {uname}}) SET u.lastname = {new_lname};',
                                ['uname' => $user['username'], 'new_lname' => $_POST['lastname']], 's_lastname');
            $statusMsg .= '<p class="alert alert-success">Last name updated.</p>';
        }

        if ($_POST['username'] && strlen($_POST['username']) && ($_POST['username'] !== $user['username'])) {
            $stack->push('MATCH (u:User {username: {uname}}) SET u.username = {new_uname};',
                                ['uname' => $user['username'], 'new_uname' => $_POST['username']], 's_username');
            $statusMsg .= '<p class="alert alert-success">User name updated.</p>';

            // Update $_SESSION later
            $new_uname = $_POST['username'];
        }

        if ($_POST['email'] && strlen($_POST['email'])) {
            $stack->push('MATCH (u:User {username: {uname}}) SET u.email = {new_email};',
                                ['uname' => $user['username'], 'new_email' => $_POST['email']], 's_email');
            $statusMsg .= '<p class="alert alert-success">Email Address updated.</p>';
        }

        if ($_POST['password'] && strlen($_POST['password'])) {
            $stack->push('MATCH (u:User {username: {uname}}) SET u.password = {new_pwd};',
                                ['uname' => $user['username'], 'new_pwd' => $_POST['password']], 's_pwd');
            $statusMsg .= '<p class="alert alert-success">User password updated.</p>';
        }

        if ($_POST['gender'] && strlen($_POST['gender'])) {
            $stack->push('MATCH (u:User {username: {uname}}) SET u.gender = {new_gender};',
                                ['uname' => $user['username'], 'new_gender' => $_POST['gender']], 's_gender');
            $statusMsg .= '<p class="alert alert-success">Gender updated.</p>';
        }

        if ($_POST['sex_pref'] && strlen($_POST['sex_pref'])) {

            // Check if there is already a value
            if (isset($user['sex_pref'])) {
                $curr = explode(',', $user['sex_pref']);
            } else {
                $curr = [];
            }

            // Check if sex_pref is not already in the list
            if (!in_array($_POST['sex_pref'], $curr, true)) {
                // Add it to the array
                array_push($curr, $_POST['sex_pref']);
                $new_sex_pref = (count($curr) > 1) ? implode(',', $curr) : $curr[0];

                $stack->push('MATCH (u:User {username: {uname}}) SET u.sex_pref = {new_sex_pref};',
                                    ['uname' => $user['username'], 'new_sex_pref' => $new_sex_pref], 's_sex_pref');
                $statusMsg .= '<p class="alert alert-success">Sexual Preference updated. -- '.$new_sex_pref.'</p>';
            }
        }
        if ($_POST['bio'] && strlen($_POST['bio'])) {
            $stack->push('MATCH (u:User {username: {uname}}) SET u.bio = {new_bio};',
                                ['uname' => $user['username'], 'new_bio' => $_POST['bio']], 's_bio');
            $statusMsg .= '<p class="alert alert-success">Bio updated.</p>';
        }
        if ($_POST['tags'] && strlen($_POST['tags'])) {
            $stack->push('MATCH (u:User {username: {uname}}) SET u.tags = {new_tags};',
                                ['uname' => $user['username'], 'new_tags' => $_POST['tags']], 's_tags');
            $statusMsg .= '<p class="alert alert-success">Tags updated.</p>';
        }
        if ($_POST['pictures'] && strlen($_POST['pictures'])) {
            $stack->push('MATCH (u:User {username: {uname}}) SET u.pictures = {new_pictures};',
                                ['uname' => $user['username'], 'new_pictures' => $_POST['pictures']], 's_pictures');
            $statusMsg .= '<p class="alert alert-success">Pictures updated.</p>';
        }
        if ($_POST['latitude'] && strlen($_POST['latitude'])) {
            $stack->push('MATCH (u:User {username: {uname}}) SET u.latitude = {new_latitude};',
                                ['uname' => $user['username'], 'new_latitude' => $_POST['latitude']], 's_latitude');
            $statusMsg .= '<p class="alert alert-success">Latitude updated.</p>';
        }
        if ($_POST['longitude'] && strlen($_POST['longitude'])) {
            $stack->push('MATCH (u:User {username: {uname}}) SET u.longitude = {new_longitude};',
                                ['uname' => $user['username'], 'new_longitude' => $_POST['longitude']], 's_longitude');
            $statusMsg .= '<p class="alert alert-success">Longitude updated.</p>';
        }

        // Get updated user info
        $stack->push('MATCH (u:User {username: {uname}}) RETURN u AS user;',
                        ['uname' => $new_uname], 's_user_update');

        // Run query stack
        $results = $client->runStack($stack);

        // Update $_SESSION with updated info from the DB
        $user = $results->get('s_user_update')->getRecord()->get('user')->values();
        $_SESSION['logged_on_user'] = $user;

        $response = array('status' => true, 'statusMsg' => $statusMsg, 'user' => $user);
    } catch (Exception $e) {
        error_log($e, 3, dirname(__DIR__).'/log/errors.log');
        $response = array('status' => false,
                        'statusMsg' => "<p class=\"alert alert-danger\"><b><u>Error Message :</u></b><br /> '.$e->getMessage().' <br /><br /> <b><u>For error details, check :</u></b><br /> ".dirname(__DIR__).'/log/errors.log'.'</p>', );
    }
} else {
    $response = array('status' => false, 'statusMsg' => '<p class="alert alert-danger">Invalid Request</p>');
}
echo json_encode($response);
