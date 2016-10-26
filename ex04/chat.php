<?php
$path = "../private/";
$file = $path."chat";
if (!file_exists($path))
  exit ("ERROR - NO FILE\n");
if (file_exists($file)){
  $fp = fopen($file, 'c');
  if (flock($fp, LOCK_SH)){
    $messages = unserialize(file_get_contents($file));
    flock($fp, LOCK_UN);
  }
  fclose($fp);
?>
<html>
<head>
  <title>Chat Page</title>
  <meta charset="utf-8" />
  <style>
    body {
      color: #fff;
    }
    ul {
      list-style: none;
    }
    li {
      line-height: 30px;
      margin: 8px 0px 10px;
      border-bottom: 1px solid #333;
    }
    span.login {
      text-transform: uppercase;
      font-size: 13px;
      font-family: monospace;
      letter-spacing: 0.4px;
      margin: 0px 10px;
    }
    span.time {
      border: 1px solid #333;
      padding: 7px 10px;
      color: #555;
    }
  </style>
</head>
<body>
  <section>
    <ul>
<?php
      foreach ($messages as $msg)
        echo "<li>
                <span class='time'>".$msg['time']."</span>
                <span class='login'>".$msg['login']." >> </span>
                <span class='msg'>".$msg['msg']."</span>
              </li>";
?>
    </ul>
  </section>
</body>
</html>

<?php
}

?>
