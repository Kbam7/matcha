<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include 'debugger.php'; // DEBUG
require_once '../vendor/autoload.php';

use GraphAware\Neo4j\Client\ClientBuilder;

$user = $_SESSION['logged_on_user'];

$client = ClientBuilder::create()->addConnection('default', 'http://neo4j:123456@localhost:7474')->build();

$results = $client->run('MATCH (u:User {username: "kbam7"})-[rel_int:HAS_INTEREST]->(i:Interest)<-[rel_int2:HAS_INTEREST]-(u2:User) ' 
                        .'WHERE NOT EXISTS((u)-[:BLOCKED]-(u2)) AND NOT EXISTS((u)-[:LIKES]-(u2)) '
                        .'OPTIONAL MATCH (u)-[r]-(u2) '
                        .'RETURN DISTINCT u2 as user, r as rel, i as interest, rel_int, rel_int2',
                        ['uname' => $user['username']]);

    $profs = [];
    $records = $results->getRecords();
    if (!empty($records)){
        foreach ($records as $record) {

            $user_prof = $record->get('user');
            $user_rel = $record->get('rel');
            $interest = $record->get('interest');
            $rel_int = $record->get('rel_int');
            $rel_int2 = $record->get('rel_int2');

            echo '<u>user_prof</u><br>';
            print_r($user_prof);
            if (!empty($user_rel)){
                echo '<br><br><u>user_rel</u><br>';
                print_r($user_rel);
                echo '<br><br><u>user_rel type</u><br>';
                print_r($user_rel->type());
                echo '<br><br><u>user_rel startNodeIdentity</u><br>';
                print_r($user_rel->startNodeIdentity());
                echo '<br><br><u>user_rel endNodeIdentity</u><br>';
                print_r($user_rel->endNodeIdentity());
            } else {
                echo '<br><br><u>user_rel</u><br>';
                echo '<br><br><u>user_rel type</u><br>';
                echo '<br><br><u>user_rel startNodeIdentity</u><br>';
                echo '<br><br><u>user_rel endNodeIdentity</u><br>';
            }
            echo '<br><br><u>interest</u><br>';
            print_r($interest);
            echo '<br><br><u>rel_int</u><br>';
            print_r($rel_int);
            echo '<br><br><u>rel_int2</u><br>';
            print_r($rel_int2);
            echo '<br><br>';


            if (!empty($user_prof)) {
                $profs[] = $user_prof->values();
                $statusMsg = '<div class="alert alert-success">Users found</div>';
                $response = array('status' => true, 'statusMsg' => $statusMsg, 'users' => $profs);
                echo $statusMsg;
            } else {
                $statusMsg = '<div class="alert alert-danger">No users found</div>';
                $response = array('status' => false, 'statusMsg' => $statusMsg);
                echo $statusMsg;
            }
        }
    } else {
        $statusMsg = '<div class="alert alert-danger">No users found</div>';
        $response = array('status' => false, 'statusMsg' => $statusMsg);
        echo $statusMsg;
    }

/*
    if ($record) {
        $record = $record->get('user')->values();
    }

echo '<pre>';
print_r($record);
echo '<br /><br />';
if (empty($record)) {
    echo 'EMPTY';
}
echo '<br /><br />';
*/
/*
    $stack = $client->stack();

    // Get updated user info
    $stack->push('MATCH (u:User {username: {uname}}) RETURN u AS user;',
                    ['uname' => 'kbam73'], 's_user_update');

    // Run query stack
    $results = $client->runStack($stack);

    // Update $_SESSION with updated info from the DB
    $user = $results->get('s_user_update')->getRecord()->get('user')->values();
    $_SESSION['logged_on_user'] = $user;

    print_r($user);
    print_r($_SESSION['logged_on_user']);
*/

//    $query = 'MATCH (n:Person) RETURN n, n.name as name;';
//    $result = $client->run($query);
/*
    $login = 'kylebamping7@gmail.com';

    $result = $client->run('MATCH (u:User) WHERE (u.username={login}) OR (u.email={login}) RETURN u AS user, count(u) AS n_users;', ['login' => $login]);

    if ($result) {
        print_r($result);

        echo '<br  /><br  />YIIPPP<br  /><br  />';
    } else {
        echo 'NOPEE <br  /><br  />';
    }
*/
/*
$result = $client->run('MATCH (u:User) WHERE ((u.username={login}) OR (u.email={login})) AND u.password={passwd}'
                        .'RETURN u AS user, count(u) AS n_users;',
                        ['login' => 'kbam7', 'passwd' => '0529489a0c6223c06e7c2e1b7266b17c00a62555a84e76c53452c414ebcacaeb7046a3aadd35f3b3cecb7d4399fbc199ab3585c342ff92f3f27c2b889d4fb48b']);

$record = $result->getRecord();

$n_users = $record->get('user')->values();
print_r($record);
console_log($n_users); // DEBUG
*/
/*
    $n_users = $record->get('n_users');
    console_log($n_users); // DEBUG

    /*

   foreach ($result->getRecords() as $record) {




/*
       console_log('HELLOOOO');
       print_r($record->get('user')->values());
       echo '<br  /><br  />';
       echo json_encode($record->get('user')->values());
       echo '<br  /><br  />';
       echo json_encode($record->get('n_users'));
*/
      // console_log($record->labels());
    /*   print_r($record->labels());
       echo '<br  /><br  />';
       console_log($record->values());
    /*   print_r($record.values());*/
//       echo '<br  /><br  />'.$record->get('NumN').'<br  /><br  />';
//   }

//    print_r($record->labels(); // nodes returned are automatically hydrated to Node objects
/*    print_r($record.values());
    echo '<br  />';
    echo '<br  />';
/*    print_r($record.value('Person'));
*/
    //echo '<br  />'.$results[0];
