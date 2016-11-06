<?php

// retrieve `:Image :UPLOADED` by `User` LIMIT 5
require_once '../vendor/autoload.php';

use GraphAware\Neo4j\Client\ClientBuilder;

function displayUserGallery($username)
{
    $client = ClientBuilder::create()->addConnection('default', 'http://neo4j:123456@localhost:7474')->build();

    $results = $client->run('MATCH (img:Image)<-[:UPLOADED]-(:User {username:{uname}}) RETURN img LIMIT 5',
                        ['uname' => $username]);

    foreach ($results->getRecords() as $record) {
        $img = $record->get('img')->values();
        echo '
                <div class="col-sm-12">
                    <span class="label label-primary">'.$img['title'].'</span>
                    <span class="label label-default"><small>'.date('j F Y, g:i a', $img['timestamp']).'</small></span>
                    <img class="gallery-img" src="'.$img['path'].'" alt="'.$img['title'].'" title="'.$img['title'].'" />
                    <hr />
                </div>
            ';
    }
}
