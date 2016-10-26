<?php

session_start();
include 'auth.php';

$login = $_POST['login'];
$passwd = $_POST['passwd'];

if ($login === '' || $login === null || $passwd === '' || $passwd === null) {
    $_SESSION['errors'] = array('No login or password entered.');
    $_SESSION['logged_on_user'] = null;
    header('Location: ../index.php');
} elseif ($user = auth($login, $passwd)) {
    $_SESSION['logged_on_user'] = $user;
    header('Location: ../home.php');
} else {
    $_SESSION['logged_on_user'] = null;
    header('Location: ../index.php');
}
