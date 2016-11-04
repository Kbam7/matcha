<?php

require_once '../vendor/autoload.php';

use GraphAware\Neo4j\Client\ClientBuilder;

$client = ClientBuilder::create()
    ->addConnection('default', 'http://neo4j:123456@localhost:7474')
    ->build();

//    $query = 'MATCH (n:Person) RETURN n, n.name as name;';
//    $result = $client->run($query);

    $result = $client->run('MATCH (n) WHERE n.name="JJ" RETURN count(n) as NumN;');

    if ($result) {
        print_r($result);
        echo '<br  /><br  />YIIPPP<br  /><br  />';
    } else {
        echo 'NOPEE <br  /><br  />';
    }

   foreach ($result->getRecords() as $record) {
       print_r($record);
       echo '<br  /><br  />'.$record->get('NumN').'<br  /><br  />';
   }

//    print_r($record->labels(); // nodes returned are automatically hydrated to Node objects
/*    print_r($record.values());
    echo '<br  />';
    echo '<br  />';
/*    print_r($record.value('Person'));
*/
    //echo '<br  />'.$results[0];
