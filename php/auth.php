<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../vendor/autoload.php';
use GraphAware\Neo4j\Client\ClientBuilder;

function auth($login, $passwd, $is_hashed)
{
    if (!$is_hashed) {
        $passwd = hash('whirlpool', $passwd);
    }
    try {
        $client = ClientBuilder::create()
                    ->addConnection('default', 'http://neo4j:123456@localhost:7474')
                    ->build();

        $result = $client->run('MATCH (u:User) WHERE ((u.username={login}) OR (u.email={login})) AND u.password={passwd} AND u.active=1 '
                                .'RETURN ID(u) AS id, u AS user, count(u) AS n_users;',
                                ['login' => $login, 'passwd' => $passwd]);

        if ($record = $result->getRecord()) {
            $n_users = $record->get('n_users');

            if ($n_users === 1) {
                $user = $record->get('user')->values();
                $user['uid'] = $record->get('id');
            } else {
                $user = null;
                $_SESSION['errors'] = array('Duplicate accounts detected. Access Denied!');
            }
        } else {
            $user = null;
            $_SESSION['errors'] = array('Login or password is incorrect.');
        }

        return $user;
    } catch (Exception $e) {
        $_SESSION['errors'] = array("<b><u>Error Message :</u></b><br /> '.$e.' <br /><br /> <b><u>For error details, check :</u></b><br /> ".dirname(__DIR__).'/log/errors.log'.'</p>');
        error_log($e, 3, dirname(__DIR__).'/log/errors.log');
    }

    return null;
}
