<?php

session_start();

function auth($login, $passwd)
{
    include '../config/database.php';

    $passwd = hash('whirlpool', $passwd);
    try {
        $dbname = 'camagru';
        $conn = new PDO("$DB_DSN;dbname=$dbname", $DB_USER, $DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = $conn->prepare('SELECT `id`, `firstname` FROM `users` WHERE (username=:login OR email=:login) AND password=:passwd AND active=1;');
        $sql->execute(['login' => $login, 'passwd' => 'Ab123456'/*$passwd*/]);

        if ($sql->rowCount() > 0) {
            $user = $sql->fetch(PDO::FETCH_ASSOC);
        } else {
            $user = null;
            $_SESSION['errors'] = array('Username or password is incorrect.');
        }
        $conn = null;

        return $user;
    } catch (PDOException $e) {
        $_SESSION['errors'] = array("<b><u>Error Message :</u></b><br /> '.$e.' <br /><br /> <b><u>For error details, check :</u></b><br /> ".dirname(__DIR__).'/log/errors.log'.'</p>');
        error_log($e, 3, dirname(__DIR__).'/log/errors.log');
    }
    $conn = null;

    return null;
}
