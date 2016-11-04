<?php

include 'debugger.php'; // DEBUG
require_once '../vendor/autoload.php';

use GraphAware\Neo4j\Client\ClientBuilder;

$client = ClientBuilder::create()
    ->addConnection('default', 'http://neo4j:123456@localhost:7474')
    ->build();

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

$result = $client->run('MATCH (u:User) WHERE ((u.username={login}) OR (u.email={login})) AND u.password={passwd}'
                        .'RETURN u AS user, count(u) AS n_users;',
                        ['login' => 'kbam7', 'passwd' => '0529489a0c6223c06e7c2e1b7266b17c00a62555a84e76c53452c414ebcacaeb7046a3aadd35f3b3cecb7d4399fbc199ab3585c342ff92f3f27c2b889d4fb48b']);

$record = $result->getRecord();

$n_users = $record->get('user')->values();
print_r($record);
console_log($n_users); // DEBUG
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
