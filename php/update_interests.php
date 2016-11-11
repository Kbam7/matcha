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

        // Set up DB connection
        $client = ClientBuilder::create()->addConnection('default', 'http://neo4j:123456@localhost:7474')->build();

//        echo '<pre>';
//        print_r($_POST);

        // ADD TAGS
        if (isset($_POST['add_interest']) && !empty($_POST['add_interest'])) {
            // Check if there is already a relationship and interest node
            $stack = $client->stack();
            $stack->push('MATCH (u:User {username:{username}})-[:HAS_INTEREST]->(i:Interest {title:{tag}}) RETURN u AS user;',
                            ['username' => $user['username'], 'tag' => $_POST['add_interest']], 's_user');

            $stack->push('MATCH (:User {username:{username}})-[r:HAS_INTEREST]->(:Interest) RETURN count(r) AS n_tags;',
                            ['username' => $user['username'], 'tag' => $_POST['add_interest']], 's_n_tags');

            $result = $client->runStack($stack);
            // record is used to see if there is already a relationship or not
            $record = $result->get('s_user')->getRecord();
            // n_tags counts how many tags/relationships the user has
            $n_tags = $result->get('s_n_tags')->getRecord()->get('n_tags');

            // If relationship not exists && tags is less than 5
            if (empty($record) && $n_tags < 5) {
                // Create CSV
                if (isset($user['tags']) && !empty($user['tags'])) {
                    $csv = $user['tags'].','.$_POST['add_interest'];
                } else {
                    $csv = $_POST['add_interest'];
                }

                // Save new CSV to :User node
                $client->run('MATCH (u:User {username:{username}}) SET u.tags = {tags}',
                            ['username' => $user['username'], 'tags' => $csv]);

                // Create node and relationship
                $result = $client->run('MATCH (u:User {username:{username}}) '
                                        .'MERGE (i:Interest {title:{tag}}) '
                                        .'MERGE (u)-[r:HAS_INTEREST {since:{time}}]->(i) RETURN r AS rel;',
                                        ['username' => $user['username'], 'tag' => $_POST['add_interest'], 'time' => time()]);
                $record = $result->getRecord();
                if (!empty($record)) {
                    $statusMsg .= '<p class="alert alert-success">User interests updated</p>';
                } else {
                    $statusMsg .= '<p class="alert alert-warning">There was an error. User interests <b>NOT</b> changed.</p>';
                    $response = array('status' => false, 'statusMsg' => $statusMsg);
                    die(json_encode($response));
                }
            } else {
                if ($n_tags >= 5) {
                    $statusMsg .= '<p class="alert alert-warning">You have reached the <b>maximum of 5</b> interests.</p>';
                } else {
                    $statusMsg .= '<p class="alert alert-warning">User interest already exists.</p>';
                }
                $response = array('status' => false, 'statusMsg' => $statusMsg);
                die(json_encode($response));
            }
        }
        // REMOVE TAGS
        elseif (isset($_POST['remove_interest']) && !empty($_POST['remove_interest'])) {
            // Check if there is a relationship and interest node
            $result = $client->run('MATCH (u:User {username:{username}})-[:HAS_INTEREST]->(i:Interest {title:{tag}}) RETURN u AS user;',
                            ['username' => $user['username'], 'tag' => $_POST['remove_interest']]);
            $record = $result->getRecord();

            // Remove relationship if exists
            if (!empty($record)) {
                // Remove value from CSV
                if (isset($user['tags']) && !empty($user['tags'])) {
                    // Make array
                    $arr = explode(',', $user['tags']);
                    if (in_array($_POST['remove_interest'], $arr, true)) {
                        // remove string from array
                        array_splice($arr, array_search($_POST['remove_interest'], $arr, true), 1);
                    }
                    // Make CSV
                    $csv = implode(',', $arr);
                    // Update Tags CSV in :User node
                    $client->run('MATCH (u:User {username: {username}}) SET u.tags = {new_tags};',
                                        ['username' => $user['username'], 'new_tags' => $csv]);
                }
                // Remove relationship
                $result = $client->run('MATCH (u:User {username:{username}})-[r:HAS_INTEREST]->(:Interest {title:{tag}}) DELETE r;',
                                ['username' => $user['username'], 'tag' => $_POST['remove_interest']]);

                $statusMsg .= '<p class="alert alert-success">User interest deleted.</p>';
            }
        }

        // Get updated user info
        $results = $client->run('MATCH (u:User {username: {uname}}) RETURN u AS user;',
                        ['uname' => $user['username']]);
        $updates = $results->getRecord();
        $user = $updates->get('user')->values();

        // Check if users profile is filled out enough
        $flag = 1;
        if (!isset($user['tags']) || empty($user['tags'] || $user['tags'] == '')) {
            $flag = 0;
        }

        // If profile status has changed, update it
        if ($user['profile_complete'] !== $flag) {
            // Update profile status
            $results = $client->run('MATCH (u:User {username: {uname}}) SET u.profile_complete = {value} RETURN u AS user;',
                            ['uname' => $user['username'], 'value' => $flag]);
            $updates = $results->getRecord();
            $user = $updates->get('user')->values();
        }
        // Update session
        $_SESSION['logged_on_user'] = $user;

        // Build response
        $response = array('status' => true, 'statusMsg' => $statusMsg);
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

function countInterests()
{
}
