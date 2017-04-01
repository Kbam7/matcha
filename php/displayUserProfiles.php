<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../vendor/autoload.php';

use GraphAware\Neo4j\Client\ClientBuilder;

$user = $_SESSION['logged_on_user'];

    $client = ClientBuilder::create()->addConnection('default', 'http://neo4j:123456@localhost:7474')->build();

    $results = $client->run('MATCH (u:User {username:{uname}, profile_complete:1, active:1}), (u2:User {profile_complete:1, active:1})'
                        .' MATCH (u)-[:HAS_INTEREST]->(:Interest)<-[:HAS_INTEREST]-(u2)'
                        .' WHERE NOT EXISTS((u)-[:BLOCKED]-(u2))'
                        //.' RETURN DISTINCT collect(u2) AS suggestions',
                        .' RETURN DISTINCT u2',
                        ['uname' => $user['username']]);

    $profs = [];
    $records = $results->getRecords();
    if (!empty($records)){
        foreach ($records as $record) {
            $user_prof = $record->get('u2');
            if (!empty($user_prof)) {
                $profs[] = $user_prof->values();
                $statusMsg = '<div class="alert alert-success">Users found</div>';
                $response = array('status' => true, 'statusMsg' => $statusMsg, 'users' => $profs);
            } else {
                $statusMsg = '<div class="alert alert-danger">No users found</div>';
                $response = array('status' => false, 'statusMsg' => $statusMsg);
            }
        }
    } else {
        $statusMsg = '<div class="alert alert-danger">No users found</div>';
        $response = array('status' => false, 'statusMsg' => $statusMsg);
    }
    echo json_encode($response);
