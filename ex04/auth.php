<?php

function auth($login, $passwd){
  $accounts = unserialize(file_get_contents("private/passwd"));
  foreach ($accounts as $acc) {
    if ($acc['login'] === $login)
      if ($acc['passwd'] === hash('whirlpool', $passwd))
        return (true);
  }
  return (false);
}

?>
