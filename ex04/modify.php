<?php

function ft_is_user($accounts, $login){
  foreach ($accounts as $acc) {
    if ($acc['login'] === $login)
      return (true);
  }
  return (false);
}

function ft_pw_match($accounts, $login, $oldpw){
  foreach ($accounts as $acc) {
    if ($acc['login'] === $login)
      if ($acc['passwd'] === hash('whirlpool', $oldpw))
        return (true);
  }
  return (false);
}

if (($_POST['login'] && $_POST['oldpw'] && $_POST['newpw'] && $_POST['submit'] === "OK")){
  $path = "../private/";
  $file = $path."passwd";
  $flag = -1;
  if (!file_exists($path))
    mkdir($path, 0777);
  if (file_exists($file)){
    $accounts = unserialize(file_get_contents($file));
    if (ft_is_user($accounts, $_POST['login'])){
      if (ft_pw_match($accounts, $_POST['login'], $_POST['oldpw'])){
        foreach ($accounts as $index => $acc){
          if ($acc['login'] === $_POST['login']){
            $flag = $index;
            break;
          }
        }
        if (flag > -1){
          $accounts[$flag]['passwd'] = hash('whirlpool', $_POST['newpw']);
          if (file_put_contents($file, serialize($accounts))){
            echo "OK\n";
            $flag = 1;
          }
        }
      }
    }
  }
}
if ($flag != 1)
  echo "ERROR\n";
header('Location: index.html');

?>
