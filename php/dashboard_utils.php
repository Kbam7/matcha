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

        // LIKE USER
        if (isset($_POST['like_user']) && !empty($_POST['like_user'])) {
            $stack = $client->stack();

            // Check if users have blocked eachother
            $stack->push('MATCH (u:User {username:{username}})-[r:BLOCKED]-(u2:User {username:{like_user}}) RETURN r;',
                            ['username' => $user['username'], 'like_user' => $_POST['like_user']], 'blocked');
            
            // Check if :LIKE relationship already exists
            $stack->push('MATCH (u:User {username:{username}})-[r:LIKES]->(u2:User {username:{like_user}}) RETURN r;',
                            ['username' => $user['username'], 'like_user' => $_POST['like_user']], 'already_liked');

            $result = $client->runStack($stack);
            $blocked = $result->get('blocked')->getRecord();
            $already_liked = $result->get('already_liked')->getRecord();

            // If not already liked, and nobody is blocked
            if (empty($already_liked) && empty($blocked)) {
                // Create relationship
                $result = $client->run('MATCH (u:User {username:{user1}}), (u2:User {username:{user2}}) '
                                        .'MERGE (u)-[r:LIKES {since:{time}}]->(u2) RETURN r;',
                                        ['user1' => $user['username'], 'user2' => $_POST['like_user'], 'time' => time()]);
                $record = $result->getRecord();
                if (!empty($record)) {
                    $statusMsg .= '<div class="alert alert-success">You like '. $_POST['like_user'] .'</div>';
                } else {
                    $statusMsg .= '<div class="alert alert-warning">There was an error.</div>';
                    $response = array('status' => false, 'statusMsg' => $statusMsg);
                    die(json_encode($response));
                }
            } else {
                if ($blocked) {
                    $statusMsg .= '<div class="alert alert-warning">One of the users have been blocked. Cannot like.</div>';
                } else {
                    $statusMsg .= '<div class="alert alert-warning">You already like this user</div>';
                }
                $response = array('status' => false, 'statusMsg' => $statusMsg);
                die(json_encode($response));
            }
        }
        // UNLIKE USER
        if (isset($_POST['unlike_user']) && !empty($_POST['unlike_user'])) {
            $stack = $client->stack();

            // Check if users have blocked eachother
            $stack->push('MATCH (u:User {username:{username}})-[r:BLOCKED]-(u2:User {username:{unlike_user}}) RETURN r;',
                            ['username' => $user['username'], 'unlike_user' => $_POST['unlike_user']], 'blocked');
            
            // Check if :LIKE relationship exists
            $stack->push('MATCH (u:User {username:{username}})-[r:LIKES]->(u2:User {username:{unlike_user}}) RETURN r;',
                            ['username' => $user['username'], 'unlike_user' => $_POST['unlike_user']], 'rel_exists');

            $result = $client->runStack($stack);
            $blocked = $result->get('blocked')->getRecord();
            $rel_exists = $result->get('rel_exists')->getRecord();

            // If relationship exists, and nobody is blocked
            if (!empty($rel_exists)) {
                // delete relationship
                $stack->push('MATCH (u:User {username:{user1}})-[r:LIKES]->(u2:User {username:{user2}}) '
                                        .'DELETE r;', ['user1' => $user['username'], 'user2' => $_POST['unlike_user']]);

                // Check if relationship still exists
                $stack->push('MATCH (u:User {username:{user1}})-[r:LIKES]->(u2:User {username:{user2}}) '
                                        .'RETURN r;', ['user1' => $user['username'], 'user2' => $_POST['unlike_user']], 'rel');                                        

                $result = $client->runStack($stack);                                        
                $record = $result->get('rel')->getRecord();

                if (empty($record)) {
                    $statusMsg .= '<div class="alert alert-success">You no longer like '. $_POST['unlike_user'] .'</div>';
                } else {
                    $statusMsg .= '<div class="alert alert-warning">There was an error.</div>';
                    $response = array('status' => false, 'statusMsg' => $statusMsg);
                    die(json_encode($response));
                }
            } else {
                $statusMsg .= '<div class="alert alert-warning">You have already unliked '. $_POST['unlike_user'] .'</div>';
                $response = array('status' => false, 'statusMsg' => $statusMsg);
                die(json_encode($response));
            }
        }
        // BLOCK USER
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

                $statusMsg .= '<div class="alert alert-success">User interest deleted.</div>';
            }
        }

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
