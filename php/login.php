<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include 'auth.php';

$login = $_POST['login'];
$passwd = $_POST['passwd'];

if ($login === '' || $login === null || $passwd === '' || $passwd === null) {
    $_SESSION['errors'] = array('No login or password entered.');
    unset($_SESSION['logged_on_user']);
    header('Location: ../index.php');
} elseif ($user = auth($login, $passwd)) {
    $_SESSION['logged_on_user'] = $user;
    session_regenerate_id(true);
    header('Location: ../views/home.php');
} else {
    unset($_SESSION['logged_on_user']);
    header('Location: ../index.php');
}
