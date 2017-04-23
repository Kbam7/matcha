<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../vendor/autoload.php';
use GraphAware\Neo4j\Client\ClientBuilder;

// Dispatcher for POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['displayGallery']) && isset($_POST['username'])) {
        if ($_POST['displayGallery'] === '1') {
            displayCamagruUserGallery($_POST['username']);
        } elseif ($_POST['displayGallery'] === '2') {
            displayUserGallery($_POST['username']);
        }
    }
}

// Return user profile
function getUserProfile($username)
{
    $client = ClientBuilder::create()->addConnection('default', 'http://neo4j:123456@localhost:7474')->build();

    $results = $client->run('MATCH (u:User {username:{uname}}) RETURN u AS user;',
                        ['uname' => $username]);

    return $results->getRecord()->get('user')->values();
}

function getProfilePictureSrc($a_user)
{
    if (isset($a_user['profile_pic'])) {
        echo '/matcha/assets/uploads/thumbnails/'.$a_user['profile_pic'];
    } else {
        echo '../assets/img/default_pp.png';
    }
}

function displayUsersTags($a_user)
{
    if (isset($a_user['tags'])) {
        $tags = explode(',', $a_user['tags']);
        if (!empty($tags) && !(empty($tags[0]))) {
            foreach ($tags as $tag) {
                echo '<span>'.$tag.'</span>';
            }
        }
    } else {
        echo '<p>No Tags found</p>';
    }
}

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
