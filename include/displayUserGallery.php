<?php

session_start();

// retrieve `:Image :UPLOADED` by `User` LIMIT 5
require_once '../vendor/autoload.php';

use GraphAware\Neo4j\Client\ClientBuilder;

// Displays users gallery on Camagru page
function displayCamagruUserGallery($username)
{
    $client = ClientBuilder::create()->addConnection('default', 'http://neo4j:123456@localhost:7474')->build();

    $results = $client->run('MATCH (img:Image)<-[:UPLOADED]-(:User {username:{uname}}) RETURN img LIMIT 5',
                        ['uname' => $username]);

    foreach ($results->getRecords() as $record) {
        $img = $record->get('img')->values();
        echo '
                <div class="col-xs-12" id="img_'.basename($img['filename'], '.png').'">
                    <div>
                        <span class="label label-primary">'.$img['title'].'</span>
                        <span class="label label-default"><small>'.date('j F Y, g:i a', $img['timestamp']).'</small></span>
        ';
        if ($_SESSION['logged_on_user']['username'] === $username) {
            echo '
                        <button type="button" id="'.basename($img['filename'], '.png').'" class="btn btn-xs btn-danger delete_image_btn pull-right" title="Delete Image" ><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>
                ';
        }
        echo '
                    </div>
                    <a href="/matcha/views/user_image.php?img='.basename($img['filename'], '.png').'" title="'.$img['title'].'">
                        <img class="gallery-img" src="/matcha/assets/uploads/'.$img['filename'].'" alt="'.$img['title'].'" title="'.$img['title'].'" />
                    </a>
                    <hr />
                </div>
            ';
    }
}

// Displays users gallery
function displayUserGallery($username)
{
    $client = ClientBuilder::create()->addConnection('default', 'http://neo4j:123456@localhost:7474')->build();

    $results = $client->run('MATCH (img:Image)<-[:UPLOADED]-(:User {username:{uname}}) RETURN img LIMIT 5',
                        ['uname' => $username]);

    foreach ($results->getRecords() as $record) {
        $img = $record->get('img')->values();
        echo '
            <div class="col-xs-12 col-sm-6" id="img_'.basename($img['filename'], '.png').'">
                <div class="settings-gallery-item">
                    <div>
                        <span class="label label-primary">'.$img['title'].'</span>
                        <span class="label label-default"><small>'.date('j F Y, g:i a', $img['timestamp']).'</small></span>
        ';
        if ($_SESSION['logged_on_user']['username'] === $username) {
            echo '
                        <button type="button" id="'.basename($img['filename'], '.png').'" class="btn btn-xs btn-danger delete_image_btn  pull-right" title="Delete Image" ><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>
                ';
        }
        echo '
                    </div>
                <a href="/matcha/views/user_image.php?img='.basename($img['filename'], '.png').'" title="'.$img['title'].'">
                    <img class="gallery-img" src="/matcha/assets/uploads/'.$img['filename'].'" alt="'.$img['title'].'" title="'.$img['title'].'" />
                </a>
            </div>
            <hr />
        </div>
            ';
    }
}
