<?php

session_start();
session_unset();
session_destroy();
setcookie(session_name(), '', 0, '/');
session_regenerate_id(true);
sleep(1);
header('Location: ../index.php');
