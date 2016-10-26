<?php
session_start();
$_SESSION['logged_on_user'] = "";
sleep(1);
header('Location: index.html')
?>
