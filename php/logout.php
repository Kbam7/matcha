<?php

session_start();
$_SESSION['logged_on_user'] = null;
sleep(1);
header('Location: ../index.php');
