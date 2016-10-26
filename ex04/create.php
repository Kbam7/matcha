<?php

if ((!$_POST['login'] || !$_POST['passwd']) || $_POST['submit'] !== "OK")
  echo "ERROR\n";
else if ($_POST['submit'] === "OK")
{
  $flag = 0;
  $path = "../private/";
  $file = $path."passwd";
  if (!file_exists($path))
    mkdir($path, 0777);
  if (file_exists($file))
  {
    $accounts = unserialize(file_get_contents("../private/passwd"));
    foreach ($accounts as $acc) {
      if ($acc['login'] === $_POST['login']){
        $flag = 1;
        echo "ERROR\n";
        break ;
      }
    }
  }
  if ($flag == 0){
    $passwd = hash('whirlpool', $_POST['passwd']);
    $accounts[] = array('login' => $_POST['login'], 'passwd' => $passwd);
    if (file_put_contents($file, serialize($accounts)))
      echo "OK\n";
    else
      echo "ERROR\n";
  }
}
header('Location: index.html');

?>
