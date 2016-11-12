<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../vendor/autoload.php';

use GraphAware\Neo4j\Client\ClientBuilder;

$user = $_SESSION['logged_on_user'];

    $client = ClientBuilder::create()->addConnection('default', 'http://neo4j:123456@localhost:7474')->build();

    $results = $client->run('MATCH (u:User {username:{uname}, profile_complete:1, active:1}), (u2:User {profile_complete:1, active:1})'
                        .' MATCH (u)-[:HAS_INTEREST]->(i:Interest)<-[:HAS_INTEREST]-(u2)'
                        .' RETURN collect(u2) AS suggestions',
                        ['uname' => $user['username']]);

    $profs = [];
    $record = $results->getRecord();
    foreach ($record->get('suggestions') as $user_prof) {
        $profs[] = $user_prof->values();
    }

    $statusMsg = '<div class="alert alert-success">Users found</div>';
    $response = array('status' => true, 'statusMsg' => $statusMsg, 'users' => $profs);
    echo json_encode($response);
